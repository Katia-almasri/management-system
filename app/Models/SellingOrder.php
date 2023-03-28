<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingOrder extends Model
{
    use HasFactory;

    protected $table = 'selling_orders';
    protected $primaryKey='id';
    protected $fillable = [
       'manager_id',
       'sellingPort_id'
    ];
    ############################## Begin Relations #############################
    public function manager(){
        return $this->belongsTo('App\Models\Manager', 'manager_id', 'id');
    }

    public function sellingPort(){
        return $this->belongsTo('App\Models\SellingPort', 'sellingPort_id', 'id');
    }

    public function sellingOrderDetails(){
        return $this->hasMany('App\Models\SellingOrderDetail', 'selling_order_id', 'id');
    }
    ############################## End Relations ##############################
}
