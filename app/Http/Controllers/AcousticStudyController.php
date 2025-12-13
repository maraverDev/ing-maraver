<?php

namespace App\Http\Controllers;

use App\Models\AcousticStudy;
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
}
