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
        $places = Place::withCount('subSites')
            ->orderBy('name')
            ->get();

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
                    foreach ($request->file("sub_sites.$index.files") as $file) {

                        $path = $file->store(
                            "acoustic-studies/{$place->id}/{$subSite->id}"
                        );

                        AcousticStudy::create([
                            'sub_site_id' => $subSite->id,
                            'original_name' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                        ]);
                    }
                }
            }
        });
    }
    public function show(Place $place)
    {
        $place->load([
            'subSites.acousticStudies'
        ]);

        return view('places.show', compact('place'));
    }
}
