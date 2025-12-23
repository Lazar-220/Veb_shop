<?php

namespace App\Http\Controllers;

use App\Http\Resources\PorudzbinaResource;
use App\Models\Porudzbina;
use App\Models\Stavka;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PorudzbinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $porudzbine=Porudzbina::with(['user','stavke.slika'])->get();
        return response()->json(PorudzbinaResource::collection($porudzbine),200);
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
        //izostavljena drzava jer ce se za sada podrazumevati Srbija, i ukupnaCena jer nju mi racunamo, i rb stavki isto mi postavljamo, i status tj polje poslato (false), i danasnji datum sami unosimo
        $validator=Validator::make($request->all(),[
            'user_id'=>['nullable','integer','exists:users,id'],
            'ime'=>['required','string','max:30'],
            'prezime'=>['required','string','max:30'],
            'grad'=>['required','string','max:30'],
            'adresa'=>['required','string','max:100'],
            'postanski_broj'=>['required','string','max:20'],
            'telefon'=>['required','string','max:30'],

            'stavke'=>['required','array','min:1'],
            'stavke.*.slika_id'=>['required','integer','exists:slike,id'],
            'stavke.*.cena'=>['required','numeric','min:0'],
            'stavke.*.kolicina'=>['required','integer','min:1']
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Validacija nije prosla.',
                'errors'=>$validator->errors()
            ],422);
        }

        $data=$validator->validated();

        DB::beginTransaction();

        try {

            $ukupnaCena=0;

            foreach($data['stavke'] as $stavka){
                
                $ukupnaCena+=$stavka['cena']*$stavka['kolicina'];
            }

            $porudzbina=Porudzbina::create([
                'user_id'=>isset($data['user_id']) ? $data['user_id'] : null,
                'datum'=>now(),             //eloquent na osnovu $casts iz modela sam konkvertuje iz datetime ili string u date koji baza ocekuje
                'ukupna_cena'=>$ukupnaCena,
                'ime'=>$data['ime'],
                'prezime'=>$data['prezime'],
                'grad'=>$data['grad'],
                'adresa'=>$data['adresa'],
                'postanski_broj'=>$data['postanski_broj'],
                'telefon'=>$data['telefon'],
                'poslato'=>false
            ]);

            foreach($data['stavke'] as $index => $stavka){
                Stavka::create([
                    'porudzbina_id'=>$porudzbina->id,
                    'slika_id'=>$stavka['slika_id'],
                    'rb'=>$index+1,
                    'cena'=>$stavka['cena'],
                    'kolicina'=>$stavka['kolicina']
                ]);
                $ukupnaCena+=$stavka['cena']*$stavka['kolicina'];
            }

            DB::commit();

            $porudzbina->load(['user','stavke.slika']);

            return response()->json(new PorudzbinaResource($porudzbina),201);




        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message'=>'Neuspesno kreiranje porudzbine.',
                'error'=>$e->getMessage()
            ],500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $porudzbina=Porudzbina::with(['user','stavke'])->findOrFail($id);
        return response()->json(new PorudzbinaResource($porudzbina),200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Porudzbina $porudzbina)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $porudzbina=Porudzbina::findOrFail($id);

        $validator=Validator::make($request->all(),[
            'user_id'=>['nullable','integer','exists:users,id'],
            'datum'=>['sometimes','date'],
            'ime'=>['sometimes','string','max:30'],
            'prezime'=>['sometimes','string','max:30'],
            'grad'=>['sometimes','string','max:30'],
            'adresa'=>['sometimes','string','max:100'],
            'postanski_broj'=>['sometimes','string','max:20'],
            'telefon'=>['sometimes','string','max:30'],
            'poslato'=>['sometimes','boolean'],
            

            'stavke'=>['sometimes','array','min:1'],
            'stavke.*.slika_id'=>['integer','exists:slike,id'],
            'stavke.*.cena'=>['numeric','min:0'],
            'stavke.*.kolicina'=>['integer','min:1']
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

        DB::beginTransaction();

        try {
            
            if(isset($data['stavke'])){

                $ukupnaCena=0;

                $porudzbina->stavke()->delete();

                foreach($data['stavke'] as $index=>$stavka){
                    $ukupnaCena+=$stavka['cena']*$stavka['kolicina'];
                    Stavka::create([
                        'porudzbina_id'=>$porudzbina->id,
                        'slika_id'=>$stavka['slika_id'],
                        'rb'=>$index+1,
                        'cena'=>$stavka['cena'],
                        'kolicina'=>$stavka['kolicina']
                    ]);
                }
                $data['ukupna_cena']=$ukupnaCena;
                unset($data['stavke']);
            }

            $porudzbina->update($data);

            DB::commit();

            $porudzbina->load(['user','stavke.slika']);

            return response()->json(new PorudzbinaResource($porudzbina),200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message'=>'Neuspesno kreiranje porudzbine.',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $porudzbina=Porudzbina::findOrFail($id);

        $porudzbina->stavke()->delete();

        $porudzbina->delete();

        return response()->json(['message'=>'Porudzbina je obrisana.'],200);
    }

    public function vratiSvePorudzbineKupca($userId){
        $user=User::findOrFail($userId);
        $porudzbine=$user->porudzbine()->with(['stavke.slika'])->get();
        return response()->json(PorudzbinaResource::collection($porudzbine),200);
    }
}
