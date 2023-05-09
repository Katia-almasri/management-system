<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Driver extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'drivers';
    protected $primaryKey='id';
    protected $fillable = [
       'mashenism_coordinator_id',
       'name',
       'state',
       'address',
       'mobile_number'
    ];

     ############################## Begin Relations #############################
     public function manager(){
        return $this->belongsTo('App\Models\Manager', 'mashenism_coordinator_id', 'id');
    }

    public function trips(){
        return $this->hasMany('App\Models\Trip', 'driver_id', 'id');
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
