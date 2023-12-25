<?php

namespace Fintech\Bell\Http\Resources;

use Fintech\Core\Supports\Constant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TriggerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($trigger) {
            return [
                'id' => $trigger->getKey() ?? null,
                'name' => $trigger->name ?? null,
                'code' => $trigger->code ?? null,
                'description' => $trigger->description ?? null,
                'trigger_data' => $trigger->trigger_data ?? null,
                'enabled' => $trigger->enabled ?? null,
                'links' => $trigger->links,
                'created_at' => $trigger->created_at,
                'updated_at' => $trigger->updated_at,
            ];
        })->toArray();
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'options' => [
                'dir' => Constant::SORT_DIRECTIONS,
                'per_page' => Constant::PAGINATE_LENGTHS,
                'sort' => ['id', 'name', 'created_at', 'updated_at'],
            ],
            'query' => $request->all(),
        ];
    }
}
