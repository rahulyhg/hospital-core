<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BhytResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'bhytid'                => $this->bhytid,
            'patientid'             => $this->patientid,
            'hosobenhanid'          => $this->hosobenhanid,
          
        ];
    }
}
