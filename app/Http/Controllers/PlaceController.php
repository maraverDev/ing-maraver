<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlaceRequest;
use App\Models\Place;
use App\Models\SubSite;
use App\Models\AcousticStudy;
use Illuminate\Support\Facades\DB;



class PlaceController extends Controller
{
    public function index()
    {
        $sort = request('sort', 'name');
        $direction = request('direction', 'asc');

        $query = Place::withCount('subSites');

        // Lógica de ordenación
        switch ($sort) {
            case 'sub_sites_count':
                $query->orderBy('sub_sites_count', $direction);
                break;
            case 'max_limiters':
                $query->orderBy('max_limiters', $direction);
                break;
            default:
                $query->orderBy('name', $direction);
                break;
        }

        $places = $query->paginate(10)->withQueryString();

        return view('places.index', compact('places'));
    }


    public function store(StorePlaceRequest $request)
    {
        DB::transaction(function () use ($request) {

            $place = Place::create([
                'name' => $request->name,
                'max_limiters' => $request->max_limiters,
            ]);

            foreach ($request->sub_sites as $index => $subSiteData) {

                $subSite = SubSite::create([
                    'place_id' => $place->id,
                    'name' => $subSiteData['name'],
                ]);

                if ($request->hasFile("sub_sites.$index.files")) {
                    $fileNames = [];
                    foreach ($request->file("sub_sites.$index.files") as $file) {
                        $originalName = $file->getClientOriginalName();

                        // Check for duplicates within the current request for this subsite
                        if (in_array($originalName, $fileNames)) {
                            throw new \Exception("No se pueden subir múltiples archivos con el mismo nombre ('{$originalName}') para el mismo sub-sitio.");
                        }
                        $fileNames[] = $originalName;

                        $path = $file->store(
                            "acoustic-studies/{$place->id}/{$subSite->id}"
                        );

                        AcousticStudy::create([
                            'sub_site_id' => $subSite->id,
                            'original_name' => $originalName,
                            'file_path' => $path,
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'uploaded_at' => now(),
                        ]);
                    }
                }
            }

        });
        return redirect()
            ->route('places.index')
            ->with('success', 'Lugar creado correctamente');
    }
    public function show(Place $place)
    {
        $place->load([
            'subSites.acousticStudies'
        ]);

        return view('places.show', compact('place'));
    }
    public function subSites(Place $place)
    {
        return response()->json([
            'max_limiters' => $place->max_limiters,
            'sub_sites' => $place->subSites()->select('id', 'name')->get(),
        ]);
    }

}
