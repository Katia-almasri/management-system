<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPurchaseOffer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'purchase_offers_detail';
    protected $primaryKey='id';
    protected $fillable = [
       'purchase_offers_id',
       'type',
       'amount'
    ];

     ############################## Begin Relations #############################
     public function purchaseOrder(){
        return $this->belongsTo('App\Models\PurchaseOffer', 'purchase_offers_id', 'id');
    }

    ############################## End Relations ##############################
}
