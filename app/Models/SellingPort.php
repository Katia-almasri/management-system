<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellingPort extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'selling_ports';
    protected $primaryKey='id';
    protected $fillable = [
       'owner',
       'location',
       'mobile_number'
    ];

     ############################## Begin Relations #############################
    public function sellingOrder(){
        return $this->hasMany('App\Models\SellingOrder', 'sellingPort_id', 'id');
    }

    public function salesPurchasingRequests(){
        return $this->hasMany('App\Models\salesPurchasingRequset', 'selling_port_id', 'id');
    }

    ############################## End Relations ##############################
}
