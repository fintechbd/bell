<?php

namespace Fintech\Bell\Repositories\Eloquent;

use Fintech\Bell\Interfaces\NotificationRepository as InterfacesNotificationRepository;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class NotificationRepository
 */
class NotificationRepository extends EloquentRepository implements InterfacesNotificationRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.bell.notification_model', \Fintech\Bell\Models\Notification::class));
    }

    /**
     * return a list or pagination of items from
     * filtered options
     *
     * @return Paginator|Collection
     */
    public function list(array $filters = [])
    {
        $query = $this->model->newQuery();

        // Searching
        if (! empty($filters['search'])) {
            if (is_numeric($filters['search'])) {
                $query->where($this->model->getKeyName(), 'like', "%{$filters['search']}%");
            } else {
                $query->where('name', 'like', "%{$filters['search']}%");
                $query->orWhere('notification_data', 'like', "%{$filters['search']}%");
            }
        }

        if (! empty($filters['notifiable_type'])) {
            $query->where('notifiable_type', '=', $filters['notifiable_type']);
        }

        if (! empty($filters['type'])) {
            $query->where('type', '=', $filters['type']);
        }

        if (! empty($filters['user_id'])) {
            $query->where('notifiable_id', '=', $filters['user_id']);
        }

        // Display Trashed
        if (isset($filters['trashed']) && $filters['trashed'] === true) {
            $query->whereNotNull('read_at');
        } else {
            $query->whereNull('read_at');
        }

        // Handle Sorting
        $query->orderBy($filters['sort'] ?? $this->model->getKeyName(), $filters['dir'] ?? 'asc');

        // Execute Output
        return $this->executeQuery($query, $filters);

    }
}
