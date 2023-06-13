<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige1Output extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige1_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_date',
       'weight',
       'amount',
       'det1_id',
       'output_to'
    ];

    ################### Begin Relations #####################
     public function DetonatorFrige1InputOutput(){
        return $this->hasMany('App\Models\DetonatorFrige1InputOutput', 'output_id', 'id');
    }

    public function detonator1(){
        return $this->belongsTo('App\Models\DetonatorFrige1', 'det1_id', 'id');
    }

    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
    public function outputable(){
        return $this->morphTo();
    }

    public function storeDetail()
    {
        return $this->morphOne('App\Models\StoreDetail', 'inputable');
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

