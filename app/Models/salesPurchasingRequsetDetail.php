<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salesPurchasingRequsetDetail extends Model
{
    use HasFactory;

    protected $table = 'sales-purchasing-requset-details';
    protected $primaryKey='id';
    protected $fillable = [
       'requset_id',
       'amount',
       'type'
    ];

     ############################## Begin Relations #############################
    public function salesPurchasingRequset(){
        return $this->belongsTo('App\Models\salesPurchasingRequset', 'request-id', 'id');
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
