<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Trip extends Model
{
    use HasFactory;

    protected $table = 'trips';
    protected $primaryKey='id';
    protected $fillable = [
       'truck_id',
       'driver_id',
       'farm_id',
       'selling_port_id',
       'sales_purchasing_requsets_id'
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
    ############################## End Relations ##############################

    ############################# Begin Accessors ##############################endregion
    public function getCreatedAtAttribute($date)
    {
        if($date!=null)
            return Carbon::parse($date)->format('Y-m-d H:i');
        return $date;
    }

    public function getUpdatedAtAttribute($date)
    {
        if($date!=null)
            return Carbon::parse($date)->format('Y-m-d H:i');
        return $date;
    }
}
