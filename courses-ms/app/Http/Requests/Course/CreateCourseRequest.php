<?php

namespace App\Http\Requests\Course;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCourseRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:courses',
            'description' => 'required|string',
            'duration' => 'required|numeric',
            'rating' => 'required|numeric',
            'status_id' => 'required|integer|exists:statuses,id',
            'difficulty_level_id' => 'required|integer|exists:difficulty_levels,id',
        ];
    }
}
