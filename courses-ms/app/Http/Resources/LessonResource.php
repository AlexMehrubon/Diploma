<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'order' => $this->id,
            'module_id' => $this->module_id,
            'is_completed' => $this->when($request->bearerToken(), $this->is_completed),
            'module' => ModuleResource::make($this->module),
            'comments' => $this->comments,
            'comments_count' => $this->comments_count
        ];
    }
}
