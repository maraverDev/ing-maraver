<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Place;

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
            'sub_sites.*' => ['required', 'exists:sub_sites,id'],

            'files' => ['required', 'array'],
            'files.*.csv' => ['required', 'file'],
            'files.*.pdf_programming' => ['required', 'file'],
            'files.*.pdf_installation' => ['required', 'file'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $place = Place::find($this->place_id);

            if ($place && $this->limiters_installed > $place->max_limiters) {
                $validator->errors()->add(
                    'limiters_installed',
                    'El número de limitadores supera el máximo permitido para este lugar.'
                );
            }
        });
    }
}
