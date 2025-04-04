<?php

namespace Fintech\Bell\Http\Resources;

use Fintech\Core\Supports\Constant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($notification) {
            return [
                'id' => $notification->getKey(),
                'type' => $notification->data['type'] ?? null,
                'title' => $notification->data['title'] ?? null,
                'body' => $notification->data['body'] ?? null,
                'image' => $notification->data['image'] ?? null,
                'read_at' => $notification->read_at,
                'read_at_formatted' => $notification->read_at?->diffForHumans(),
                'created_at' => $notification->created_at,
                'created_at_formatted' => $notification->created_at->diffForHumans(),
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
