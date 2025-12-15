<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo admin y técnico
        return $this->user()?->hasRole('admin')
            || $this->user()?->hasRole('technician');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180'],
            'max_limiters' => ['required', 'integer', 'min:1', 'max:5'],

            'sub_sites' => ['required', 'array', 'min:1'],
            'sub_sites.*.name' => ['required', 'string', 'max:180'],

            'sub_sites.*.files' => ['nullable', 'array'],
            'sub_sites.*.files.*' => [
                'file',
                'mimes:pdf',
                'max:10240' // 10 MB
            ],
        ];
    }
}
