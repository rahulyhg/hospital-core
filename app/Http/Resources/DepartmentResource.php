<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DepartmentResource extends Resource
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
            'departmentid'      => $this->departmentid,
            'departmentcode'    => $this->departmentcode,
            'departmentname'    => $this->departmentname,
            'departmentgroupid' => $this->departmentgroupid,
            'departmenttype'    => $this->departmenttype
          
        ];
    }
}