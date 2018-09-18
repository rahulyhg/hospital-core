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
            'full_name'         => sprintf('%d %d', $this->first_name,$this->last_name),
            'birth_date'        => $this->birth_date,
            'email'             => $this->email,
            'phone_no'             => $this->phone_no,
        ];
    }
}