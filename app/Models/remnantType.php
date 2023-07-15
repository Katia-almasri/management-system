<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class remnantType extends Model
{
    use HasFactory;

    protected $table = 'remnant_types';
    protected $primaryKey='id';
    protected $fillable = [
       'name'
    ];

    ####################### Begin Relations ######################
    public function remnantWarehouses(){
        return $this->hasMany('App\Models\RemnantWarehouse', 'remnant_type', 'id');
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
