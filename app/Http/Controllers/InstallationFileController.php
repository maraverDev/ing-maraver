<?php

namespace App\Http\Controllers;

use App\Models\InstallationFile;
use Illuminate\Support\Facades\Storage;

class InstallationFileController extends Controller
{
    public function download(InstallationFile $file)
    {
        if (!Storage::exists($file->file_path)) {
            abort(404);
        }

        return Storage::download(
            $file->file_path,
            $file->original_name
        );
    }
}
