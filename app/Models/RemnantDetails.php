<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemnantDetails extends Model
{
    use HasFactory;
    protected $table = 'remnant_details';
    protected $primaryKey='id';
    protected $fillable = [
       'inputable',
       'input_from',
       'cur_output_weight',
       'cur_weight',
       'weight',
       'remnant_id'
    ];

    ####################### Begin Relations ######################
    public function remnantWarehouse(){
        return $this->belongsTo('App\Models\RemnantWarehouse', 'remnant_id', 'id');
    }

    public function inputable(){
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
