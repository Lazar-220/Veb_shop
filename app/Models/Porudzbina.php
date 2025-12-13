<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Porudzbina extends Model
{
    protected $fillable = [
        'user_id',
        'datum',
        'ukupna_cena',
        'ime',
        'prezime',
        'drzava',
        'grad',
        'adresa',
        'postanski_broj',
        'telefon',
        'status'
    ];

    protected $casts = [
        'ukupna_cena'=>'decimal:2',
        'datum'=>'date'
    ];

    public function korisnik(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function stavke(){
        return $this->hasMany(Stavka::class,'porudzbina_id');
    }
}
