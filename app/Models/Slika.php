<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slika extends Model
{
    use HasFactory;
    protected $table='slike';
    protected $fillable = [
        'galerija_id',
        'putanja_fotografije',
        'cena',
        'naziv',
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

    public function tehnike(){
        return $this->belongsToMany(Tehnika::class);
    }

    //^ovo je isto kao:
    // public function tehnike(){
    //     return $this->belongsToMany(Tehnika::class,'slika_tehnika','slika_id','tehnika_id');
    // }

    

    //belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
    // hasOne($related, $foreignKey = null, $localKey = null)
    // hasMany($related, $foreignKey = null, $localKey = null)
    //         class        FK                    PK-id
    // belongsToMany(
    //     $related,                         
    //     $table = null,                naziv pivot tabele
    //     $foreignPivotKey = null,      naziv 1. dela PK, FK
    //     $relatedPivotKey = null,      naziv 2. dela PK, FK
    //     $parentKey = null,            PK-id
    //     $relatedKey = null,           PK-id
    //     $relation = null
    // )
}
