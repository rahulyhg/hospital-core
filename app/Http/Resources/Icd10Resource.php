<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Icd10Resource extends Resource
{
    public function toArray($request)
    {
        return [
            'icd10id'                   => $this->icd10id,
            'icd10code'                 => $this->icd10code,
            'icd10name'                 => $this->icd10name,
        ];
    }
}