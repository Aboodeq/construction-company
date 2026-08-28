<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHeroSlideRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
