<?php

namespace Fintech\Bell\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->getKey(),
            'type' => $this->data['type'] ?? null,
            'title' => $this->data['title'] ?? null,
            'body' => $this->data['body'] ?? null,
            'image' => $this->data['image'] ?? null,
            'read_at' => $this->read_at,
            'read_at_formatted' => $this->read_at?->diffForHumans(),
            'created_at' => $this->created_at,
            'created_at_formatted' => $this->created_at->diffForHumans(),
        ];
    }
}
