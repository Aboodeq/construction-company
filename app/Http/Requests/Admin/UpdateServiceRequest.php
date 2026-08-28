<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('services.edit');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'service_category_id' => ['nullable', 'exists:service_categories,id'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_featured' => ['sometimes', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],

            'featured_image' => ['nullable', 'image', 'max:4096'],
            'remove_featured_image' => ['sometimes', 'boolean'],

            'new_images' => ['sometimes', 'array'],
            'new_images.*' => ['image', 'max:4096'],

            'faqs' => ['sometimes', 'array'],
            'faqs.*.id' => ['nullable', 'integer', 'exists:service_faqs,id'],
            'faqs.*.question' => ['nullable', 'string', 'max:255'],
            'faqs.*.answer' => ['nullable', 'string'],

            'process_steps' => ['sometimes', 'array'],
            'process_steps.*.title' => ['nullable', 'string', 'max:255'],
            'process_steps.*.description' => ['nullable', 'string'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
