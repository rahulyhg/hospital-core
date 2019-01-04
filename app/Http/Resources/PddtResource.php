<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class PddtResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'icd10id'                   => $this->icd10id,
            'icd10code'                 => $this->icd10code,
            'icd10name'                 => $this->icd10name,
            'icd10name_en'              => $this->icd10name_en,
            'icd10type'                 => $this->icd10type,
            'xet_nghiem'                => json_decode($this->xet_nghiem),
            'chan_doan_hinh_anh'        => json_decode($this->chan_doan_hinh_anh),
            'chuyen_khoa'               => json_decode($this->chuyen_khoa),
            'vat_tu'                    => json_decode($this->vat_tu),
            'thuoc'                     => json_decode($this->thuoc),
            'khac'                      => json_decode($this->khac),
            'giai_trinh'                => json_decode($this->giai_trinh),
        ];
    }
}