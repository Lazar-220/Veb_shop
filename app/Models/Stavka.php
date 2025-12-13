<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stavka extends Model
{
    protected $fillable = [
        'porudzbina_id',
        'slika_id',
        'rb',
        'cena',
        'kolicina'
    ];

    protected $casts = [
        'cena'=>'decimal:2',
    ];

    public function porudzbina(){
        return $this->belongsTo(Porudzbina::class,'porudzbina_id');
    }

    public function slika(){
        return $this->belongsTo(Slika::class,'slika_id');                
    }
}
