<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'selling_order_details';
    protected $primaryKey='id';
    protected $fillable = [
       'selling_order_id',
       'details'
    ];

     ############################## Begin Relations #############################
    public function sellingOrder(){
        return $this->belongsTo('App\Models\SellingOrder', 'selling_order_id', 'id');
    }

    ############################## End Relations ##############################
}
