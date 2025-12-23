<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PorudzbinaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'user'=>$this->whenLoaded('user'),
            'datum'=>$this->datum->format('Y-m-d'),
            'ukupna_cena'=>(float)$this->ukupna_cena,
            'ime'=>$this->ime,
            'prezime'=>$this->prezime,
            'drzava'=>$this->drzava,
            'grad'=>$this->grad,
            'adresa'=>$this->adresa,
            'postanski_broj'=>$this->postanski_broj,
            'telefon'=>$this->telefon,
            'poslato'=>(boolean)$this->poslato,
            'stavke'=>StavkaResource::collection($this->whenLoaded('stavke'))
        ];
    }
}
