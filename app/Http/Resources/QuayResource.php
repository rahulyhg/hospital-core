<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class QuayResource extends Resource
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
            'id'                => $this->id,
            'ten'             => $this->ten,
            'khu_vuc_id'          => $this->khu_vuc_id,
            'benh_vien_id'          => $this->benh_vien_id,
            'trang_thai'          => $this->trang_thai
        ];
    }
}
