<?php

namespace App\Models;

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
}
