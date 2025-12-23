<?php

use App\Http\Controllers\GalerijaController;
use App\Http\Controllers\PorudzbinaController;
use App\Http\Controllers\SlikaController;
use App\Http\Controllers\TehnikaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// dodaj za dodavanje, izmenu i brisanje prodavca


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::resource('/galerija',GalerijaController::class);


//dodavanje,izmena i brisanja admin
Route::get('/tehnike',[TehnikaController::class,'index']);
Route::get('/tehnike/{id}',[TehnikaController::class,'show']);
Route::post('/tehnike',[TehnikaController::class,'store']);
Route::delete('/tehnike/{id}',[TehnikaController::class,'destroy']);
Route::put('/tehnike/{id}',[TehnikaController::class,'update']);


//dodavanje,izmena i brisanja admin
Route::get('/slike',[SlikaController::class,'index']);
Route::get('/slike/{id}',[SlikaController::class,'show']);
Route::post('/slike',[SlikaController::class,'store']);
Route::delete('/slike/{id}',[SlikaController::class,'destroy']);
Route::put('/slike/{id}',[SlikaController::class,'update']);


//kreiranje kupac, gost; gledanje svojih kupac; gledanje svih, izmena i brisanje admin;
Route::get('/porudzbine',[PorudzbinaController::class,'index']);
Route::get('/porudzbine/{id}',[PorudzbinaController::class,'show']);
Route::post('/porudzbine',[PorudzbinaController::class,'store']);
Route::delete('/porudzbine/{id}',[PorudzbinaController::class,'destroy']);
Route::put('/porudzbine/{id}',[PorudzbinaController::class,'update']);
Route::get('/porudzbine/kupac/{userId}',[PorudzbinaController::class,'vratiSvePorudzbineKupca']);
