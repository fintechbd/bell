<?php

namespace Fintech\Bell\Services;

use Fintech\Core\Attributes\ListenByTrigger;
use Fintech\Core\Facades\Core;
use Fintech\Core\Listeners\TriggerListener;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * Class TriggerService
 */
class TriggerService
{
    private Collection $triggers;

    /**
     * BankAccountService constructor.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->loadTriggers();
    }

    public function find($id, string $key = 'id')
    {
        return $this->triggers->firstWhere($key, '=', $id);
    }

    public function list(array $filters = [])
    {
        if (! empty($filters['search'])) {
            $this->triggers = $this->triggers->filter(function ($item) use ($filters) {
                return Str::contains($item['name'], $filters['search'], true)
                    || Str::contains($item['description'], $filters['search'], true);
            });
        }

        if (isset($filters['sort'])) {
            $this->triggers = (isset($filters['dir']) && $filters['dir'] == 'asc')
                ? $this->triggers->sortBy($filters['sort'])
                : $this->triggers->sortByDesc($filters['sort']);
        }

        return $this->triggers;
    }

    /**
     * @throws \ReflectionException
     */
    public function sync()
    {
        Cache::forget('system-triggers');

        $this->loadTriggers();
    }

    /**
     * @throws \ReflectionException
     */
    private function loadTriggers(): void
    {
        $eventDispatcher = App::make('events');

        $this->triggers = Cache::remember('system-triggers', HOUR, function () use ($eventDispatcher) {

            $triggers = collect();

            foreach ($eventDispatcher->getRawListeners() as $event => $listeners) {
                if (! in_array(TriggerListener::class, $listeners)) {
                    continue;
                }

                $reflector = new ReflectionClass($event);

                if (empty($reflector->getAttributes(ListenByTrigger::class))) {
                    continue;
                }

                $triggerInfo = $reflector->getAttributes(ListenByTrigger::class)[0]->newInstance();

                $trigger = [
                    'id' => Str::uuid()->toString(),
                    'name' => $triggerInfo->name(),
                    'code' => $event,
                    'anonymous' => $triggerInfo->isAnonymous(),
                    'description' => $triggerInfo->description(),
                    'enabled' => $triggerInfo->enabled(),
                    'variables' => array_map(fn ($variable) => ['name' => $variable->name(), 'description' => $variable->description()], $triggerInfo->variables()),
                    'recipients' => [
                        [
                            'name' => 'Admin',
                            'description' => 'Region Master Administrator',
                            'enabled' => true,
                        ],
                        [
                            'name' => 'Customer',
                            'description' => 'Business Customer/User',
                            'enabled' => true,
                        ],
                    ],
                ];

                $trigger['variables'][] = ['name' => '__timestamp__', 'description' => 'Datetime the Trigger/Event occurred'];

                $this->injectIpInfo($trigger);

                $triggers->push($trigger);
            }

            return $triggers;

        });
    }

    private function injectIpInfo(array &$trigger): void
    {
        if (Core::packageExists('Auth')) {
            $ipExists = array_filter($trigger['variables'], fn ($variable) => $variable['name'] === '__ip__');

            if (! empty($ipExists)) {
                $trigger['variables'][] = ['name' => '__location__', 'description' => 'User Location based on IP address'];
                $trigger['variables'][] = ['name' => '__latitude__', 'description' => 'Map latitude based on IP address'];
                $trigger['variables'][] = ['name' => '__longitude__', 'description' => 'Map longitude based on IP address'];
            }
        }
    }
}
