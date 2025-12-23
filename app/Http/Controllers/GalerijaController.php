<?php

namespace App\Http\Controllers;

use App\Http\Resources\GalerijaResource;
use App\Models\Galerija;
use Illuminate\Http\Request;

class GalerijaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(GalerijaResource::collection(Galerija::all()),200);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(new GalerijaResource(Galerija::findOrFail($id)),200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Galerija $galerija)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Galerija $galerija)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Galerija $galerija)
    {
        //
    }
}
