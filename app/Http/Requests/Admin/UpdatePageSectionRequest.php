<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('homepage.edit');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'points' => ['nullable', 'array'],
            'points.*.icon' => ['nullable', 'string', 'max:255'],
            'points.*.title' => ['nullable', 'string', 'max:255'],
            'points.*.description' => ['nullable', 'string'],
        ];
    }
}
