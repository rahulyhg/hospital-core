<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SttPhongKham extends Model
{
    //protected $fillable = [];
    
    protected $guarded = ['id'];
    
    protected $table = 'stt_phong_kham';
    
    public $timestamps = false;
}