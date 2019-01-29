<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icd10 extends Model
{
    protected $table = 'icd10';

    protected $primaryKey = 'icd10id';

    protected $guarded = ['icd10id'];

    public $timestamps = false;

}
