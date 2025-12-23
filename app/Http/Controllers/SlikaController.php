<?php

namespace App\Http\Controllers;

use App\Http\Resources\SlikaResource;
use App\Models\Slika;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Rules\PostojiPutanjaSlike;

class SlikaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //Request $request
    {
        $slike=Slika::with(['galerija','tehnike'])->get();
        return response()->json(SlikaResource::collection($slike),200);

        // $perPage = (int) $request->get('per_page', 10);
        // $slike = Slika::with(['galerija','tehnike'])
        //             ->paginate($perPage); // automatski čita ?page=#
        // return SlikaResource::collection($slike);
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
            'galerija_id'=>['required','integer','exists:galerija,id'],
            'putanja_fotografije'=>['required','string',new PostojiPutanjaSlike()],
            'cena'=>['required','numeric','min:0'],
            'naziv'=>['required','string','max:50'],
            'visina_cm'=>['required','numeric','min:0'],
            'sirina_cm'=>['required','numeric','min:0'],
            'dostupna'=>['required','boolean'],

            'tehnike'=>['required','array','min:1'], //niz id-jeva tehnika
            'tehnike.*'=>['integer','exists:tehnike,id']
          ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Validacija nije prosla.',
                'errors'=>$validator->errors()
            ],422);
        }
        $data=$validator->validated();

        $tehnike=$data['tehnike'];

        unset($data['tehnike']); //brise tehnike(kljuc asoc niza) iz $data jer nemamo tu kolonu u tabeli slike

        $slika=Slika::create($data);

        $slika->tehnike()->sync($tehnike);

        $slika->load(['galerija','tehnike']); //preko fje tehnike() ucitava iz pivot tabele iz baze tehnike koje su vezane za ovu sliku

        return response()->json(new SlikaResource($slika),201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $slika=Slika::with(['galerija','tehnike'])->findOrFail($id);
        return response()->json(new SlikaResource($slika),200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slika $slika)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $slika=Slika::findOrFail($id);
        $validator=Validator::make($request->all(),[
            'galerija_id'=>['sometimes','integer','exists:galerija,id'],
            'putanja_fotografije'=>['sometimes','string',new PostojiPutanjaSlike()],
            'cena'=>['sometimes','numeric','min:0'],
            'naziv'=>['sometimes','string','max:50'],
            'visina_cm'=>['sometimes','numeric','min:0'],
            'sirina_cm'=>['sometimes','numeric','min:0'],
            'dostupna'=>['sometimes','boolean'],

            'tehnike'=>['sometimes','array','min:1'],
            'tehnike.*'=>['integer','exists:tehnike,id']
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

        if(isset($data['tehnike'])){   //proverava da li postoji kljuc tehnike
            
            $tehnike=$data['tehnike'];
            unset($data['tehnike']);
            $slika->tehnike()->sync($tehnike);
        }
        
        $slika->update($data);

        // $slika->load('tehnike');
        $slika->load(['galerija','tehnike']);
        return response()->json(new SlikaResource($slika),200);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $slika=Slika::findOrFail($id);

        $slika->tehnike()->detach(); //brise iz pivota veze sa ovom slikom i bez cascade
        $slika->delete();

        return response()->json(['message'=>'Slika je obrisana'],200);
    }
}
