<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class output_cutting_detail extends Model
{
    use HasFactory;
    protected $table = 'output_cutting_details';
    protected $primaryKey='id';
    protected $fillable = [
       'type_id',
       'expiry_date',
       'weight',
       'output_cutting_id',
       'outputable_type',
       'outputable_id'
    ];

    public function outputTypes(){
        return $this->belongsTo('App\Models\outPut_Type_Production', 'type_id', 'id');
    }

    public function output_cutting(){
        return $this->belongsTo('App\Models\output_cutting', 'output_cutting_id', 'id');
    }



    /////////////////// morph ////////////////

     //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
     public function outputable(){
        return $this->morphTo();
    }

    public function ZeroFrigeDetail()
    {
        return $this->morphOne('App\Models\ZeroFrigeDetail', 'inputable');
    }
    //inputable method from(manufactoring detail and zero frige detail)
    public function InputManufacturing()
    {
        return $this->morphOne('App\Models\InputManufacturing', 'inputable');
    }

}
