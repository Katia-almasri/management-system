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
       'output_cutting_id'
    ];

    public function outputTypes(){
        return $this->belongsTo('App\Models\outPut_Type_Production', 'type_id', 'id');
    }

    public function detail_output_cutting(){
        return $this->belongsTo('App\Models\output_cutting', 'output_cutting_id', 'id');
    }

    public function Input_manufacturing(){
        return $this->hasOne('App\Models\InputManufacturing', 'output_cutting_det_Id', 'id');
    }

}
