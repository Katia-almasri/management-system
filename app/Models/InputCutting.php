<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputCutting extends Model
{
    use HasFactory;

    protected $table = 'input_cuttings';
    protected $primaryKey='id';
    protected $fillable = [
       'weight',
       'income_date',
       'output_date',
       'cutting_done',
       'output_citting_id',
       'type_id',
       'input_from'

    ];

    public function detail_output_cutiing(){
        return $this->belongsTo('App\Models\output_cutting', 'output_cutting_details', 'id');
    }

    public function output_types(){
        return $this->belongsTo('App\Models\outPut_Type_Production', 'type_id', 'id');
    }
    //morph 
    public function inputable(){
        return $this->morphTo();
    }

    public function ZeroFrigeOutput()
    {
        return $this->morphOne('App\Models\ZeroFrigeOutput', 'outputable');
    }



}
