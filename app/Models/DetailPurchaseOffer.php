<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPurchaseOffer extends Model
{
    use HasFactory;


    protected $table = 'detail_purchase_offers';
    protected $primaryKey='id';
    protected $fillable = [
       'purchase_offers_id',
       'weight',
       'type',
       'amount'
    ];

     ############################## Begin Relations #############################
     public function purchaseOrder(){
        return $this->belongsTo('App\Models\PurchaseOffer', 'purchase_offers_id', 'id');
    }

    ############################## End Relations ##############################
}
