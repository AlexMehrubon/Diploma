<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
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
            'description' => $this->description,
            'order' => $this->order,
            'course_id' => $this->course_id,
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),
            'progress' => $this->when($request->bearerToken(), $this->progress ? [
                'value' => $this->progress['value'],
                'total_lessons' => $this->progress['total_lessons'],
                'completed_lessons' => $this->progress['completed_lessons'],
            ] : null)
        ];
    }
}
