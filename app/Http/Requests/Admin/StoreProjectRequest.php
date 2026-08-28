<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('projects.create');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'project_category_id' => ['nullable', 'exists:project_categories,id'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'completion_date' => ['nullable', 'date'],
            'duration' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_featured' => ['sometimes', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
