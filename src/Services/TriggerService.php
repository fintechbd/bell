<?php

namespace Fintech\Bell\Services;

use Fintech\Core\Attributes\ListenByTrigger;
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

    public function find($code)
    {
        return $this->triggers->firstWhere('id', $code);
    }

    public function list(array $filters = [])
    {
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
                if (!in_array(TriggerListener::class, $listeners)) {
                    continue;
                }

                $reflector = new ReflectionClass($event);

                if (empty($reflector->getAttributes(ListenByTrigger::class))) {
                    continue;
                }

                $triggerInfo = $reflector->getAttributes(ListenByTrigger::class)[0]->newInstance();

                $triggers->push([
                    'id' => Str::uuid()->toString(),
                    'name' => $triggerInfo->name(),
                    'code' => $event,
                    'description' => $triggerInfo->description(),
                    'enabled' => $triggerInfo->enabled(),
                    'variables' => array_map(fn($variable) => ['name' => $variable->name(), 'description' => $variable->description()], $triggerInfo->variables()),
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
                    ]
                ]);
            }

            return $triggers;

        });
    }
}
