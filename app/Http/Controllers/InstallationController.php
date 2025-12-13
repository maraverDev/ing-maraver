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
    public function index()
    {
        $installations = Installation::with('place')
            ->orderByDesc('installation_date')
            ->get();

        $places = Place::with('subSites')->orderBy('name')->get();

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

                $files = $request->files[$subSiteId];

                foreach ($files as $type => $file) {

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
            $installation->logs()->create([
                'user_id' => auth()->id(),
                'action' => 'Creación de instalación',
                'description' => 'Registro inicial de la instalación.',
            ]);

        });
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
