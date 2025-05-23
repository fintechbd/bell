<?php

namespace Fintech\Bell\Repositories\Eloquent;

use Fintech\Bell\Interfaces\TemplateRepository as InterfacesTemplateRepository;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TemplateRepository
 */
class TemplateRepository extends EloquentRepository implements InterfacesTemplateRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.bell.template_model', \Fintech\Bell\Models\Template::class));
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
                $query->orWhere('template_data', 'like', "%{$filters['search']}%");
            }
        }

        // Display Trashed
        if (isset($filters['trashed']) && $filters['trashed'] === true) {
            $query->onlyTrashed();
        }

        if (isset($filters['enabled'])) {
            $query->where('enabled', $filters['enabled']);
        }
        if (isset($filters['medium_in'])) {
            $query->whereIn('medium', (array) $filters['medium_in']);
        }

        if (isset($filters['triggered'])) {
            $query->where('template_data->triggered', $filters['triggered']);
        }

        if (isset($filters['scheduled'])) {
            $query->where('template_data->scheduled', $filters['scheduled']);
        }

        if (isset($filters['trigger_code'])) {
            $query->where('trigger_code', '=', $filters['trigger_code']);
        }

        // Handle Sorting
        $query->orderBy($filters['sort'] ?? $this->model->getKeyName(), $filters['dir'] ?? 'asc');

        // Execute Output
        return $this->executeQuery($query, $filters);

    }
}
