<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    protected $table = 'khoa';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
