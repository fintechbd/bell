<?php

namespace Fintech\Bell\Services;

use Fintech\Bell\Handlers\TriggerNotificationHandler;
use Fintech\Bell\Interfaces\NotificationRepository;

/**
 * Class NotificationService
 */
class NotificationService
{
    /**
     * NotificationService constructor.
     */
    public function __construct(private readonly NotificationRepository $notificationRepository) {}

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->notificationRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->notificationRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->notificationRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->notificationRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->notificationRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->notificationRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->notificationRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->notificationRepository->create($filters);
    }

    public function handle(object $event, array $variables): void
    {
        logger()->debug('NotificationService Called');
        (new TriggerNotificationHandler)->handle($event, $variables);
    }
}
