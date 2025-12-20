<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Models\InstallationFile;
use App\Models\SubSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstallationFileController extends Controller
{
    public function store(
        Request $request,
        Installation $installation,
        SubSite $subSite
    ) {
        $this->authorize('update', $installation);

        $data = $request->validate([
            'type' => ['required', 'in:csv,pdf_programming,pdf_installation'],
            'file' => ['required', 'file'],
        ]);

        DB::transaction(function () use ($request, $installation, $subSite, $data) {

            $existing = $installation->files()
                ->where('sub_site_id', $subSite->id)
                ->where('type', $data['type'])
                ->first();

            if ($existing) {
                Storage::disk('public')->delete($existing->file_path);
                $existing->delete();
            }

            $path = $request->file('file')->store(
                "installations/{$installation->id}/{$subSite->id}/{$data['type']}",
                'public'
            );

            InstallationFile::create([
                'installation_id' => $installation->id,
                'sub_site_id' => $subSite->id,
                'type' => $data['type'],
                'original_name' => $request->file('file')->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $request->file('file')->getSize(),
                'mime_type' => $request->file('file')->getMimeType(),
            ]);

            $installation->logs()->create([
                'user_id' => auth()->id(),
                'action' => 'Actualización de archivo',
                'description' => "Archivo {$data['type']} actualizado en {$subSite->name}",
            ]);
        });

        return back()
            ->with('success', 'Archivo guardado correctamente.')
            ->with('open_sub_site_modal', $subSite->id);
    }

    public function download(InstallationFile $file)
    {
        $this->authorize('view', $file->installation);

        if (!Storage::exists($file->file_path)) {
            abort(404);
        }

        return Storage::download(
            $file->file_path,
            $file->original_name
        );
    }

    public function destroy(InstallationFile $file)
    {
        $this->authorize('update', $file->installation);

        Storage::disk('public')->delete($file->file_path);

        $file->installation->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'Eliminación de archivo',
            'description' => "Se eliminó {$file->type} ({$file->original_name})",
        ]);

        $file->delete();

        return back()
            ->with('success', 'Archivo eliminado correctamente.')
            ->with('open_sub_site_modal', $file->sub_site_id);
    }

}
