<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyStatisticRequest extends FormRequest
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
            'label' => ['required', 'string', 'max:255'],
            'number' => ['required', 'integer', 'min:0'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'icon' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
