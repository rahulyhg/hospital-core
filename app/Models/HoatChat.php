<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoatChat extends Model
{
    protected $table = 'hoat_chat';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
