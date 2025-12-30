<?php

namespace App\Http\Controllers;

use App\Http\Resources\SlikaResource;
use App\Models\Slika;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Rules\PostojiPutanjaSlike;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\HttpCache\Store;

class SlikaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //Request $request
    {
        $slike=Slika::with(['galerija','tehnike'])->get();
        return response()->json(SlikaResource::collection($slike),200);
    }

    public function allPicturesPaginatedFiltered(Request $request){

        $request->validate([           //isto kao $validator=Validator::make() + if($validator->fails())
            'per_page'   => 'sometimes|integer|min:1|max:20',
            'dostupna'   => 'sometimes|boolean',
            'cena_min'   => 'sometimes|numeric|min:0',
            'cena_max'   => 'sometimes|numeric|min:0',
            'visina_cm'  => 'sometimes|integer|min:1',
            'sirina_cm'  => 'sometimes|integer|min:1',
            'sort_cena'  => 'sometimes|in:asc,desc',
            'tehnike'    => 'sometimes|array|min:1',
            'tehnike.*'  => 'integer|exists:tehnike,id',
        ]);
        
        $perPage=$request->get('per_page',12);

        $query=Slika::with(['tehnike']);
        
        if($request->filled('dostupna')){
            $query->where('dostupna',$request->get('dostupna')); //isto kao $query=$query->where...
        }
        if($request->filled('cena_min')){
            $query->where('cena','>=',$request->get('cena_min'));
        }
        if($request->filled('cena_max')){
            $query->where('cena','<=',$request->get('cena_max'));
        }
        if($request->filled('visina_cm')){
            $query->where('visina_cm',$request->get('visina_cm'));
        }
        if($request->filled('sirina_cm')){
            $query->where('sirina_cm',$request->get('sirina_cm'));
        }


        if ($request->filled('tehnike')) {         //*ispod ove metode je ekvivalentan sql upit
           
            $tehnike = $request->get('tehnike');

            $query->whereHas('tehnike', function ($q) use ($tehnike) {
                $q->whereIn('tehnike.id', $tehnike);
            });

            // foreach ($tehnike as $tehnikaId) {                                //ovo je logika ako zelimo da slika mora da ima sve prosledjene tehnike
            //     $query->whereHas('tehnike', function ($q) use ($tehnikaId) {
            //         $q->where('tehnike.id', $tehnikaId);
            //     });
            // }
        }


        if ($request->filled('sort_cena') &&
            in_array($request->get('sort_cena'), ['asc', 'desc'])) {

                $query->orderBy('cena', $request->get('sort_cena'));
        }

        $paginator=$query->paginate($perPage);

        return SlikaResource::collection($paginator);
    }

    /*
        SELECT DISTINCT slike.*
        FROM slike
        AND EXISTS (
        SELECT 1
        FROM tehnike
        INNER JOIN slika_tehnika
            ON tehnike.id = slika_tehnika.tehnika_id
            WHERE slika_tehnika.slika_id = slike.id
            AND tehnike.id IN (1, 3))

            ili 

        SELECT DISTINCT slike.*
        FROM slike
        JOIN slika_tehnika ON slike.id = slika_tehnika.slika_id
        WHERE slika_tehnika.tehnika_id IN (1,3);
    */

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
            // 'putanja_fotografije'=>['nullable','string',new PostojiPutanjaSlike()],
            'putanja_fotografije'=>['nullable','image','mimes:jpg,png,jpeg','max:2048'], //treba?: php artisan storage:link
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

        if($request->hasFile('putanja_fotografije')){
            $path=$request->file('putanja_fotografije')->store('fotografije','public');
            $data['putanja_fotografije']=$path;
        }

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
            'putanja_fotografije'=>['nullable','image','mimes:jpg,png,jpeg','max:2048'],
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

        if($request->hasFile('putanja_fotografije')){

            if($slika->putanja_fotografije){
                Storage::disk('public')->delete($slika->putanja_fotografije);
            }

            $path=$request->file('putanja_fotografije')->store('fotografije','public');
            $data['putanja_fotografije']=$path;
        }
        
        if(empty($data) && !$request->hasFile('putanja_fotografije')){
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

        // if($slika->putanja_fotografije){      //ovo je ako zelimo da se fotografija obrise prilikom brisanja slike
        //     Storage::disk('public')->delete($slika->putanja_fotografije);
        // }

        $slika->tehnike()->detach(); //brise iz pivota veze sa ovom slikom i bez cascade
        $slika->delete();

        return response()->json(['message'=>'Slika je obrisana'],200);
    }
}
