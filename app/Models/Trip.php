<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'trips';
    protected $primaryKey='id';
    protected $fillable = [
       'truck_id',
       'driver_id',
       'farm_id',
       'selling_port_id',
       'sales_purchasing_requsets_id',
       'status'
    ];

     ############################## Begin Relations #############################
     public function driver(){
        return $this->belongsTo('App\Models\Driver', 'driver_id', 'id');
    }

    public function truck(){
        return $this->belongsTo('App\Models\Truck', 'truck_id', 'id');
    }


    public function requset1(){
        return $this->belongsTo('App\Models\salesPurchasingRequset', 'sales_purchasing_requsets_id', 'id');
    }

    public function manager(){
        return $this->belongsTo('App\Models\Manager', 'manager_id', 'id');
    }

    public function poultryRecipet(){
        return $this->hasOne('App\Models\PoultryReceiptDetection', 'trip_id', 'id');
    }

    ############################## End Relations ##############################
}
