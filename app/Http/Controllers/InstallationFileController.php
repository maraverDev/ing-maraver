<?php

namespace App\Http\Controllers;

use App\Models\InstallationFile;
use Illuminate\Support\Facades\Storage;

class InstallationFileController extends Controller
{
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
