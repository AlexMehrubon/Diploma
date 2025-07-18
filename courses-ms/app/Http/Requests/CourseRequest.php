<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'filter.group' => 'nullable|string',
            'filter.id' => 'nullable|integer',
            'filter.name' => 'nullable|string',
            'filter.text' => 'nullable|string',
            'filter.order' => 'nullable|string',
            'per_page' => 'nullable|integer',
            'page' => 'nullable|integer',
            'filter.tags'=> 'nullable|array',
        ];

    }
}
