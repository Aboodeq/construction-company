<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('projects.edit');
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

            'cover_image' => ['nullable', 'image', 'max:4096'],
            'remove_cover_image' => ['sometimes', 'boolean'],

            'gallery_images' => ['sometimes', 'array'],
            'gallery_images.*' => ['image', 'max:4096'],
            'before_images' => ['sometimes', 'array'],
            'before_images.*' => ['image', 'max:4096'],
            'after_images' => ['sometimes', 'array'],
            'after_images.*' => ['image', 'max:4096'],

            'services' => ['sometimes', 'array'],
            'services.*' => ['integer', 'exists:services,id'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
