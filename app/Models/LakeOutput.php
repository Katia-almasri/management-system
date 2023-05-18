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
       'amount',
        'lake_id'
    ];

     ############################## Begin Relations #############################
     public function lakeInputsOutputs(){
        return $this->hasMany('App\Models\LakeInputOutput', 'output_id', 'id');
    }

    public function lake(){
        return $this->belongsTo('App\Models\Lake', 'lake_id', 'id');
    }

    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
    /////////////////////////////////////// صفري ////////////////////////////////////////
    //الخرج من البحرات

    public function outputable(){
        return $this->morphTo();
    }

     ///////////////////////////////////////////////////////////////////////////
     public function detonatorFrige1Detail(){
        return $this->morphOne('App\Models\DetonatorFrige1Detail', 'inputable');
    }

    public function detonatorFrige2Detail(){
        return $this->morphOne('App\Models\DetonatorFrige2Detail', 'inputable');
    }

    public function detonatorFrige3Detail(){
        return $this->morphOne('App\Models\DetonatorFrige3Detail', 'inputable');
    }

    public function zeroFrigeDetail(){
        return $this->morphOne('App\Models\ZeroFrigeDetail', 'inputable');
    }



}
