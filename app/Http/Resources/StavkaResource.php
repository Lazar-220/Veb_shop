<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StavkaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'porudzbina_id'=>$this->porudzbina_id,
            'slika_id'=>$this->slika_id,
            'slika'=>new SlikaResource($this->whenLoaded('slika')),
            'rb'=>(int)$this->rb,
            'cena'=>(float)$this->cena,
            'kolicina'=>(int)$this->kolicina
        ];
    }
}
