<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ServicepricerefResource extends Resource
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
            'servicepricerefid'   => $this->servicepricerefid,
            'servicegrouptype'   => $this->servicegrouptype,
            'servicepricename'    => $this->servicepricename,
            'servicepricefee'    => $this->servicepricefee,
            'servicepricefeenhandan'    => $this->servicepricefeenhandan,
            'servicepricefeebhyt'    => $this->servicepricefeebhyt,
            'servicepricefeenuocngoai'    => $this->servicepricefeenuocngoai
        ];
    }
}