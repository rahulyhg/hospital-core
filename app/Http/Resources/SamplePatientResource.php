<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SamplePatientResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'full_name'         => sprintf('%s %s', $this->first_name,$this->last_name),
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'birth_date'        => $this->birth_date,
            'email'             => $this->email,
            'phone_no'          => trim($this->phone_no),
            'id_card_no'        => trim($this->id_card_no),
            'address'           => $this->address,
            'sex'               => $this->sex,
            'height'            => $this->height,
            'weight'            => $this->weight
        ];
    }
}