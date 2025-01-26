<?php

namespace Fintech\Bell\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
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
            'trigger_code' => $this->trigger_code,
            'name' => $this->name,
            'medium' => $this->medium,
            'trigger_name' => $this->trigger_name,
            'content' => $this->content,
            'enabled' => $this->enabled,
            'template_data' => $this->template_data,
            'recipients' => $this->recipients,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
