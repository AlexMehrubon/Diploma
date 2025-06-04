<?php

namespace App\Http\Resources;

use App\Models\Course;
use Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $arCourse = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
            'rating' => $this->rating,
            'about' => $this->about,
            'average_salary' => $this->average_salary,
            'status_id' => $this->status_id,
            'difficulty_level_id' => $this->difficulty_level_id,
            'created_at' => $this->created_at,
            'modules' => ModuleResource::collection($this->modules),
            'tags' => $this->tags,
            'progress' => $this->when($request->bearerToken(), $this->progress),

        ];;
        $course = Course::find($arCourse['id']);

        $arCourse['difficulty_level'] = $course->difficultyLevel->name;
        $arCourse['status'] = $course->status->name;

        $carbonDate = Carbon::parse($arCourse['created_at'])->setTimezone('Europe/Moscow');
        $arCourse['created_at'] = $carbonDate->format('d.m.Y');
        return $arCourse;
    }
}
