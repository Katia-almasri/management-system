<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LakeDetail extends Model
{
    use HasFactory;

    protected $table = 'lake_details';
    protected $primaryKey='id';
    protected $fillable = [
       'lake_id',
       'weight',
       'amount',
       'cur_weight',
       'cur_amount',
       'date_of_destruction',
       'expiration_date',
       'output_slaughter_detail_id'
    ];

    ############################## Begin Relations #############################
    public function lake(){
        return $this->belongsTo('App\Models\Lake', 'lake_id', 'id');
    }

    public function lakeInputsOutputs(){
        return $this->hasMany('App\Models\LakeInputOutput', 'input_id', 'id');
    }

    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
    //الدخل إلى تفاصيل البحرات
    public function inputable(){
        return $this->morphTo();
    }

    public function outputSlaughterDetail(){
        return $this->belongsTo('App\Models\outPut_SlaughterSupervisor_detail', 'output_slaughter_detail_id', 'id');
    }

}
