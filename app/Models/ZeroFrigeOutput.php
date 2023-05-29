<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZeroFrigeOutput extends Model
{
    use HasFactory;

    protected $table = 'zero_frige_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_date',
       'weight',
       'amount',
       'zero_id',
       'output_to'
    ];

     ############################## Begin Relations #############################
     public function ZeroFrigeInputOutput(){
        return $this->hasMany('App\Models\ZeroFrigeInputOutput', 'output_id', 'id');
    }

    public function zeroFrige(){
        return $this->belongsTo('App\Models\ZeroFrige', 'zero_id', 'id');
    }

    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
    /////////////////////////////////////// صفري ////////////////////////////////////////
      //الخرج من الصفري

      public function outputable(){
         return $this->morphTo();
   }
   
    public function lakeDetail()
    {
        return $this->morphOne('App\Models\LakeDetail', 'inputable');
    }

    //inputable from output cutting

    public function InputCutting()
    {
        return $this->morphOne('App\Models\InputCutting', 'inputable');
    }

    public function FillCommand()
    {
        return $this->morphOne('App\Models\FillCommand', 'fillCommad');
    }





 }
