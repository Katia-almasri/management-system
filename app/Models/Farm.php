<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'farms';
    protected $primaryKey='id';
    protected $fillable = [
       'owner',
       'location',
       'mobile_number'
    ];

     ############################## Begin Relations #############################

    public function purchaseOrders(){
        return $this->hasMany('App\Models\PurchaseOffer', 'farm_id', 'id');
    }

    public function salesPurchasingRequests(){
        return $this->hasMany('App\Models\salesPurchasingRequset', 'farm_id', 'id');
    }

    ############################## End Relations ##############################
}
