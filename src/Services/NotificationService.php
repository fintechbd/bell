<?php

namespace Fintech\Bell\Services;


use Fintech\Bell\Interfaces\NotificationRepository;

/**
 * Class NotificationService
 * @package Fintech\Bell\Services
 *
 */
class NotificationService
{
    /**
     * NotificationService constructor.
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(private readonly NotificationRepository $notificationRepository) { }

    /**
     * @param array $filters
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
}
