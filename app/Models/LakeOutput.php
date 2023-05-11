<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LakeOutput extends Model
{
    use HasFactory;

    protected $table = 'lake_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_date',
       'weight',
       'amount'
    ];

     ############################## Begin Relations #############################
     public function lakeInputsOutputs(){
        return $this->hasMany('App\Models\LakeInputOutput', 'output_id', 'id');
    }

    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
    /////////////////////////////////////// صفري ////////////////////////////////////////
    //الخرج من البحرات

    public function outputable(){
        return $this->morphTo();
    }



}
