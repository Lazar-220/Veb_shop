<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tehnika extends Model
{
    use HasFactory;
    protected $table = 'tehnike';
    protected $fillable = [
        'naziv'
    ];

    public function slike(){
        return $this->belongsToMany(Slika::class);
    }
}
