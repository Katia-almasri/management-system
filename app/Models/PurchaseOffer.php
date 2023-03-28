<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOffer extends Model
{
    use HasFactory;

    protected $table = 'purchase_offers';
    protected $primaryKey='id';
    protected $fillable = [
       'farm_id',
       'weight'
    ];

     ############################## Begin Relations #############################
     public function detailpurchaseOrders(){
        return $this->hasMany('App\Models\DetailPurchaseOffer', 'purchase_offers_id', 'id');
    }

    public function farm(){
        return $this->belongsTo('App\Models\Farm', 'farm_id', 'id');
    }
    ############################## End Relations ##############################
}
