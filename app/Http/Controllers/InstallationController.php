<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstallationRequest;
use App\Models\Installation;
use App\Models\InstallationFile;
use Illuminate\Support\Facades\DB;
use App\Models\Place;
use Illuminate\Http\Request;

class InstallationController extends Controller
{
    public function index(Request $request)
    {
        $query = Installation::with([
            'place',
            'creationLog.user',
            'subSites',
        ]);

        // Buscador por nombre de lugar
        if ($request->filled('search')) {
            $query->whereHas('place', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por lugar exacto
        if ($request->filled('place_id')) {
            $query->where('place_id', $request->place_id);
        }

        // Filtro por fecha desde
        if ($request->filled('from')) {
            $query->whereDate('installation_date', '>=', $request->from);
        }

        // Filtro por fecha hasta
        if ($request->filled('to')) {
            $query->whereDate('installation_date', '<=', $request->to);
        }

        $installations = Installation::with([
            'place',
            'creationLog.user',
            'subSites',
            'files',
        ])
            ->orderByDesc('installation_date')
            ->paginate(15)
            ->withQueryString();

        $places = Place::orderBy('name')->get();

        return view('installations.index', compact('installations', 'places'));
    }


    public function store(StoreInstallationRequest $request)
    {
        DB::transaction(function () use ($request) {

            $installation = Installation::create([
                'place_id' => $request->place_id,
                'installation_date' => $request->installation_date,
                'limiters_installed' => $request->limiters_installed,
            ]);

            $installation->subSites()->sync($request->sub_sites);

            foreach ($request->sub_sites as $subSiteId) {

                $uploadedFiles = $request->file("files.$subSiteId");

                foreach ($uploadedFiles as $file) {

                    $name = strtoupper($file->getClientOriginalName());
                    $ext = strtolower($file->getClientOriginalExtension());

                    if ($ext === 'csv') {
                        $type = 'csv';
                    } elseif ($ext === 'pdf' && str_contains($name, 'SET')) {
                        $type = 'pdf_programming';
                    } elseif ($ext === 'pdf' && str_contains($name, 'INS')) {
                        $type = 'pdf_installation';
                    } else {
                        continue; // archivo no reconocido (ya validado antes)
                    }

                    $path = $file->store(
                        "installations/{$installation->id}/{$subSiteId}/{$type}"
                    );

                    InstallationFile::create([
                        'installation_id' => $installation->id,
                        'sub_site_id' => $subSiteId,
                        'type' => $type,
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ]);
                }

            }
            if (empty($request->files)) {
                $installation->logs()->create([
                    'user_id' => auth()->id(),
                    'action' => 'Instalación sin archivos',
                    'description' => 'Se creó la instalación sin subir archivos asociados.',
                ]);
            }

            $installation->logs()->create([
                'user_id' => auth()->id(),
                'action' => 'Creación de instalación',
                'description' => 'Registro inicial de la instalación.',
            ]);
        });

        return redirect()
            ->route('installations.index')
            ->with('success', 'Instalación creada correctamente');
    }
    public function show(Installation $installation)
    {
        $this->authorize('view', $installation);


        $installation->load([
            'place',
            'subSites',
            'files.subSite',
            'logs.user',
            'issues.user',

        ]);

        return view('installations.show', compact('installation'));
    }

    public function updateNotes(Request $request, Installation $installation)
    {
        $this->authorize('update', $installation);

        $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $installation->update([
            'notes' => $request->notes,

        ]);
        $installation->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'Actualización de notas',
            'description' => $request->notes,
        ]);

        return redirect()
            ->route('installations.show', $installation)
            ->with('success', 'Notas actualizadas correctamente.');

    }


}
