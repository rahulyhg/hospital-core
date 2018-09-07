<?php

namespace App\Models\SamplePatients;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SamplePatient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'id_card_no',
        'phone_no',
        'sex',
        'birth_date',
        'height',
        'weight',
        'address',
    ];
    
    protected $dates = [
        //'birth_date',
        'created_at',
        'updated_at',
    ];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('module.sample_patients.table');
    }
}
