<?php

namespace App\Http\Controllers;

use App\Http\Resources\TehnikaResource;
use App\Models\Tehnika;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TehnikaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(TehnikaResource::collection(Tehnika::all()),200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'naziv'=>['required','string','max:50']
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Validacija nije prosla.',
                'errors'=>$validator->errors()
            ],422);
        }
        $data=$validator->validated();
        $tehnika=Tehnika::create($data);
        return response()->json(new TehnikaResource($tehnika),201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(new TehnikaResource(Tehnika::findOrFail($id)),200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tehnika $tehnika)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tehnika=Tehnika::findOrFail($id);

        $validator=Validator::make($request->all(),[
            'naziv'=>['sometimes','string','max:50']
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Validacija nije prosla.',
                'errors'=>$validator->errors()
            ],422);
        }
        $data=$validator->validated();
        if(empty($data)){
            return response()->json([
                'message' => 'Nema podataka za izmenu.'
            ], 400);
        }
        $tehnika->update($data);
        return response()->json(new TehnikaResource($tehnika),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tehnika=Tehnika::findOrFail($id);
        
        $slikeSamoSaOvomTehnikom=$tehnika->slike()
        ->withCount(['tehnike as brojTehnika'])       //dodaje kljuc brojTehnika u asoc niz koji pravimo cija je vrednost broj tehnika za svaku sliku
        ->get()                                        //pretvaranje rezultata u kolekciju kao $tehnika->slike sa jos brojTehnika
        ->filter(fn($slika)=>$slika->brojTehnika===1); // ostaje samo slike sa 1 tehnikom (ovom), kombinacija arrow i closure(anonimne) fje

        if(!$slikeSamoSaOvomTehnikom->isEmpty()){
            $ids=$slikeSamoSaOvomTehnikom->pluck('id')->join(', ');
            return response()->json([
                'message'=>"Brisanje tehnike nije moguce, slike sa id: {$ids} imaju samo ovu tehniku."
            ],422);
        }

        $tehnika->slike()->detach();
        $tehnika->delete();

        return response()->json(['message'=>'Tehnika je obrisana'],200);
    }
}
