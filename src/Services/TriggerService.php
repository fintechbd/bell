<?php

namespace Fintech\Bell\Services;

use Fintech\Core\Attributes\ListenByTrigger;
use Fintech\Core\Listeners\TriggerListener;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use ReflectionClass;

/**
 * Class TriggerService
 */
class TriggerService
{
    private Collection $triggers;

    /**
     * BankAccountService constructor.
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->triggers = new Collection;

        $this->loadTriggers();
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function loadTriggers(): void
    {
        $eventDispatcher = App::make('events');
        dd($eventDispatcher);
        $events = collect($eventDispatcher->getRawListeners());
        $manageableEvents = $events->where(0, '=', TriggerListener::class)->keys();
        $manageableEvents = $manageableEvents->filter(function ($event) {
            $reflector = new ReflectionClass($event);
            return !empty($reflector->getAttributes(ListenByTrigger::class));
        })->values();
        $this->triggers = $manageableEvents->map(function ($event) {
            $reflector = new ReflectionClass($event);
            /**
             * @var ListenByTrigger $triggerInfo
             */
            $triggerInfo = $reflector->getAttributes(ListenByTrigger::class)[0]->newInstance();
            $data['name'] = $triggerInfo->name();
            $data['code'] = $event;
            $data['description'] = $triggerInfo->description();
            $data['enabled'] = $triggerInfo->enabled();
            $data['variables'] = collect($triggerInfo->variables())->map(fn($variable) => ['name' => $variable->name(), 'description' => $variable->description()])->toArray();
        });
    }

    public function find($code)
    {
    }

    public function list(array $filters = [])
    {
        return $this->triggers;
    }

    public function sync(array $options = [])
    {
    }
}
