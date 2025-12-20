<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Place;
use Illuminate\Validation\Validator;

class StoreInstallationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin')
            || $this->user()?->hasRole('technician');
    }

    public function rules(): array
    {
        return [
            'place_id' => ['required', 'exists:places,id'],
            'installation_date' => ['required', 'date'],
            'limiters_installed' => ['required', 'integer', 'min:1'],
            'sub_sites' => ['required', 'array', 'min:1'],
            'sub_sites.*' => ['exists:sub_sites,id'],
            'files' => ['nullable', 'array'],
            'status' => ['required', 'in:pending,completed'], // 👈 NUEVO
        ];
    }


    public function withValidator(Validator $validator)
    {
        // Eliminamos la validación estricta de archivos para permitir guardar instalaciones incompletas
        // El estado 'Completa'/'Incompleta' se calculará dinámicamente según lo que se suba.
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ?? \App\Models\Installation::STATUS_COMPLETED,
        ]);
    }
}
