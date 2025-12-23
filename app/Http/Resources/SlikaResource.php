<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlikaResource extends JsonResource
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
            'galerija_id'=>$this->galerija_id,
            'galerija'=>new GalerijaResource($this->whenLoaded('galerija')),
            'putanja_fotografije'=>$this->putanja_fotografije,
            'cena'=>(float)$this->cena,
            'naziv'=>$this->naziv,
            'visina_cm'=>(int)$this->visina_cm,
            'sirina_cm'=>(int)$this->sirina_cm,
            'dostupna'=>(boolean)$this->dostupna,
            'tehnike'=>TehnikaResource::collection($this->whenLoaded('tehnike'))
        ];
    }
}
