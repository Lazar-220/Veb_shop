<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slika extends Model
{
    protected $fillable = [
        'galerija_id',
        'putanja_fotografije',
        'cena',
        'naziv',
        'tehnika',
        'visina_cm',
        'sirina_cm',
        'dostupna'
    ];

    protected $casts = [
        'cena'=>'decimal:2',
        'dostupna'=>'boolean'
    ];

    public function stavka(){
        return $this->hasOne(Stavka::class,'slika_id');
    }

    public function galerija(){
        return $this->belongsTo(Galerija::class,'galerija_id');
    }
}
