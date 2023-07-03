<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';
    protected $primaryKey='id';
    protected $fillable = [
       'request_sales_id',
       'note',
       'rate'
    ];

     ############################## Begin Relations #############################
     public function sales_request(){
        return $this->belongsTo('App\Models\salesPurchasingRequset', 'request_sales_id', 'id');
    }
    ############################## End Relations ##############################
}
