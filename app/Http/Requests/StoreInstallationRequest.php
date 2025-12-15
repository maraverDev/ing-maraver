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
            'files' => ['required', 'array'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            $subSites = $this->input('sub_sites', []);

            foreach ($subSites as $subSiteId) {

                $uploaded = $this->file("files.$subSiteId", []);

                $found = [
                    'csv' => false,
                    'pdf_programming' => false,
                    'pdf_installation' => false,
                ];

                foreach ($uploaded as $file) {
                    $name = strtoupper($file->getClientOriginalName());
                    $ext = strtolower($file->getClientOriginalExtension());

                    if ($ext === 'csv') {
                        $found['csv'] = true;
                    }

                    if ($ext === 'pdf') {

                        if (
                            str_contains($name, 'SET') ||
                            str_contains($name, 'PROG') ||
                            str_contains($name, 'PROGRAM')
                        ) {
                            $found['pdf_programming'] = true;
                        }

                        if (
                            str_contains($name, 'INS') ||
                            str_contains($name, 'INSTAL')
                        ) {
                            $found['pdf_installation'] = true;
                        }
                    }

                }

                foreach ($found as $type => $ok) {
                    if (!$ok) {
                        $validator->errors()->add(
                            "files.$subSiteId",
                            "En el sub-sitio seleccionado debes subir exactamente:
     1 CSV, 1 PDF de programación (SET) y 1 PDF de instalación (INS)."
                        );
                    }
                }
            }
        });
    }

}
