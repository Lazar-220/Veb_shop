<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uloga extends Model
{
    protected $fillable = [
        'naziv',
    ];

    public function korisnici(){
        return $this->hasMany(User::class,'uloga_id');
    }
}
