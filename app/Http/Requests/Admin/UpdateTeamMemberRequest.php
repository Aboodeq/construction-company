<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('team.edit');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'order' => ['nullable', 'integer', 'min:0'],
            'social_links' => ['sometimes', 'array'],
            'social_links.linkedin' => ['nullable', 'url', 'max:255'],
            'social_links.twitter' => ['nullable', 'url', 'max:255'],
            'social_links.instagram' => ['nullable', 'url', 'max:255'],
            'social_links.facebook' => ['nullable', 'url', 'max:255'],

            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['sometimes', 'boolean'],
        ];
    }
}
