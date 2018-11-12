<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PhongResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = array(
            'ma_nhom'    => $this->ma_nhom,
            'ten_nhom'      => $this->ten_nhom
        );
        if (isset($this->id)){
            $array['id'] = $this->id;
            $array['ten_phong'] = $this->ten_phong;
        }
        return $array;
    }
}