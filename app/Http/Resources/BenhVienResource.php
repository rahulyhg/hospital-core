<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BenhVienResource extends Resource
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
            'benhvienid'      => $this->benhvienid,
            'benhvienkcbbd'    => $this->benhvienkcbbd,
            'benhviencode'    => $this->benhviencode,
            'benhvienname'    => $this->benhvienname,
            'benhvienaddress'    => $this->benhvienaddress,
            'benhvienhang'    => $this->benhvienhang,
            'benhvienloai'    => $this->benhvienloai,
            'benhvientuyen'    => $this->benhvientuyen,
            'ghichu'    => $this->ghichu,
            'matinh'    => $this->matinh,
            'mahuyen'    => $this->mahuyen,
            'maxa'    => $this->maxa,
            'version'    => $this->version,
            'sync_flag'    => $this->sync_flag,
            'update_flag'    => $this->update_flag
        ];
    }
}