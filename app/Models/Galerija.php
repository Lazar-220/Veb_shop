<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galerija extends Model
{
    protected $fillable = [
        'naziv',
        'adresa',
        'longitude',
        'latitude'
    ];

    protected $casts = [
        'longitude'=>'decimal:6',
        'latitude'=>'decimal:6'
    ];

    protected function slike(){
        return $this->hasMany(Slika::class,'galerija_id');
    }
}
