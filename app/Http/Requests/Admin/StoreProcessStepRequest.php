<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessStepRequest extends FormRequest
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
            'step_number' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
