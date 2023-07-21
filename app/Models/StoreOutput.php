<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOutput extends Model
{
    use HasFactory;

    protected $table = 'store_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_date',
       'weight',
       'amount',
       'store_id',
       'output_to'
    ];

     ############################## Begin Relations #############################
     public function storeInputsOutputs(){
        return $this->hasMany('App\Models\StoreInputOutput', 'output_id', 'id');
    }

    public function store(){
        return $this->belongsTo('App\Models\Store', 'store_id', 'id');
    }

    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
    /////////////////////////////////////// صفري ////////////////////////////////////////
    //الخرج من البحرات

    public function outputable(){
        return $this->morphTo();
    }

     ############################# Begin Accessors ##############################endregion
     public function getCreatedAtAttribute($date)
     {
         if($date!=null)
             return Carbon::parse($date)->format('Y-m-d H:i');
         return $date;
     }
 
     public function getUpdatedAtAttribute($date)
     {
         if($date!=null)
             return Carbon::parse($date)->format('Y-m-d H:i');
         return $date;
     }

}
