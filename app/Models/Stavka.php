<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stavka extends Model
{
    use HasFactory;
    protected $table='stavke';
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
