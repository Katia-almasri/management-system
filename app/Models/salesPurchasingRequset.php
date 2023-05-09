<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class salesPurchasingRequset extends Model
{
    use HasFactory;

    protected $table = 'sales_purchasing_requests';
    protected $primaryKey='id';
    protected $fillable = [
       'purchasing_manager_id',
       'ceo_id',
       'total_amount',
       'accept',
       'request_type',
       'farm_id',
       'selling_port_id',
       'command',
       'offer_id',
       'is_seen_by_mechanism_coordinator'
    ];

     ############################## Begin Relations #############################
    public function ceoManager(){
        return $this->belongsTo('App\Models\Manager', 'ceo_id', 'id');
    }
    public function purchasingManager(){
        return $this->belongsTo('App\Models\Manager', 'purchasing_manager_id', 'id');
    }
    public function mechanismCoordinatorManager(){
        return $this->belongsTo('App\Models\Manager', 'mechanism_coordinator_id', 'id');
    }
    public function salesPurchasingRequsetDetail(){
        return $this->hasMany('App\Models\salesPurchasingRequsetDetail', 'requset_id', 'id');
    }
        ///////// NEW /////////
    public function farm(){
        return $this->belongsTo('App\Models\Farm', 'farm_id', 'id');
    }

    public function sellingPort(){
        return $this->belongsTo('App\Models\SellingPort', 'selling_port_id', 'id');
    }

    public function trips(){
        return $this->hasMany('App\Models\Trip', 'sales_purchasing_requsets_id', 'id');
    }

    public function offer(){
        return $this->belongsTo('App\Models\PurchaseOffer', 'offer_id', 'id');
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
