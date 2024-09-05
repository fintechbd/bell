<?php

namespace Fintech\Bell\Services;

use Fintech\Bell\Interfaces\TriggerRepository;
use Fintech\Core\Attributes\ListenByTrigger;
use Fintech\Core\Listeners\TriggerListener;
use Illuminate\Support\Facades\App;
use ReflectionClass;
use ReflectionException;

/**
 * Class TriggerService
 */
class TriggerService
{
    /**
     * TriggerService constructor.
     */
    public function __construct(public TriggerRepository $triggerRepository) {}

    public function find($id, $onlyTrashed = false)
    {
        return $this->triggerRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->triggerRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->triggerRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->triggerRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->triggerRepository->list($filters);
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->triggerRepository->list($filters);

    }

    public function import(array $filters)
    {
        return $this->triggerRepository->create($filters);
    }

    public function create(array $inputs = [])
    {
        return $this->triggerRepository->create($inputs);
    }

    /**
     * @return mixed
     *
     * @throws ReflectionException
     */
    public function sync()
    {
        $eventDispatcher = App::make('events');

        $events = collect($eventDispatcher->getRawListeners());

        $manageableEvents = $events->where(0, '=', TriggerListener::class)->keys();

        $manageableEvents = $manageableEvents->filter(function ($event) {
            $reflector = new ReflectionClass($event);

            return ! empty($reflector->getAttributes(ListenByTrigger::class));
        })->values();

        $manageableEvents = $manageableEvents->map(function ($event) {

            $reflector = new ReflectionClass($event);

            /**
             * @var ListenByTrigger $triggerInfo
             */
            $triggerInfo = $reflector->getAttributes(ListenByTrigger::class)[0]->newInstance();

            $data['name'] = $triggerInfo->name();
            $data['code'] = $event;
            $data['description'] = $triggerInfo->description();
            $data['enabled'] = $triggerInfo->enabled();
            $data['variables'] = collect($triggerInfo->variables())->map(fn ($variable) => ['name' => $variable->name(), 'description' => $variable->description()])->toArray();
            //            $data['recipients'] = collect($triggerInfo->recipients())->map(fn ($recipient) => ['name' => $recipient->name(), 'description' => $recipient->description()])->toArray();

            return $data;

        });

        return $manageableEvents;
    }
}
