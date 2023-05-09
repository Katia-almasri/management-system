<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PurchaseOffer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'purchase_offers';
    protected $primaryKey='id';
    protected $fillable = [
       'farm_id',
       'total_amount'
    ];

     ############################## Begin Relations #############################
     public function detailpurchaseOrders(){
        return $this->hasMany('App\Models\DetailPurchaseOffer', 'purchase_offers_id', 'id');
    }

    public function requestSales(){
        return $this->hasMany('App\Models\salesPurchasingRequset', 'offer_id', 'id');
    }

    public function farm(){
        return $this->belongsTo('App\Models\Farm', 'farm_id', 'id');
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
