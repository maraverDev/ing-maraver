<?php

namespace App\Http\Controllers;

use App\Models\AcousticStudy;
use App\Models\SubSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AcousticStudyController extends Controller
{
    public function download(AcousticStudy $study)
    {
        if (!Storage::exists($study->file_path)) {
            abort(404);
        }

        return Storage::download(
            $study->file_path,
            $study->original_name
        );
    }

    public function store(Request $request, SubSite $subSite)
    {
        $request->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $place = $subSite->place;

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();

            // Verificar si ya existe un estudio con el mismo nombre original en este sub-sitio
            if ($subSite->acousticStudies()->where('original_name', $originalName)->exists()) {
                return back()->withErrors([
                    'files' => "El archivo '{$originalName}' ya ha sido subido para este sub-sitio. Por favor, cambia el nombre o sube un archivo diferente."
                ])->withInput();
            }

            $path = $file->store(
                "acoustic-studies/{$place->id}/{$subSite->id}"
            );

            AcousticStudy::create([
                'sub_site_id' => $subSite->id,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_at' => now(),
            ]);
        }

        return back()->with('success', 'Estudios subidos correctamente.');
    }
}
