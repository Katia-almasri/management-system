<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemnantWarehouse extends Model
{
    use HasFactory;

    protected $table = 'remnant_warehouses';
    protected $primaryKey='id';
    protected $fillable = [
       'tot_weight',
       'remnant_type'
    ];

    ####################### Begin Relations ######################
    public function remnantType(){
        return $this->belongsTo('App\Models\remnantType', 'remnant_type', 'id');
    }

    public function remnantDetails(){
        return $this->hasMany('App\Models\RemnantDetails', 'remnant_id', 'id');
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
