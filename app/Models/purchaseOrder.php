<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class purchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';
    protected $primaryKey='id';
    protected $fillable = [
       'manager_id',
       'farm_id',
       'weight'
    ];

     ############################## Begin Relations #############################
     public function Farms(){
        return $this->hasMany('App\Models\Farm', 'farm_id', 'id');
    }

    public function Managers(){
        return $this->hasMany('App\Models\Manager', 'manager_id', 'id');
    }
    ############################## End Relations ##############################

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
