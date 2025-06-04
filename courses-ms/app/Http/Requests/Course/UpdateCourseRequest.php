<?php

namespace App\Http\Requests\Course;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|numeric',
            'rating' => 'nullable|numeric',
            'status_id' => 'nullable|integer|exists:statuses,id',
            'difficulty_level_id' => 'nullable|integer|exists:difficulty_levels,id',
            'about' => 'string',
            'average_salary' => 'numeric',
        ];
    }
}
