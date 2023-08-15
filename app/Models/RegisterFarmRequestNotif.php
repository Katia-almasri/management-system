<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RegisterFarmRequestNotif extends Model
{
    use HasFactory;

    protected $table = 'register_farm_request_notifs';
    protected $primaryKey='id';
    protected $fillable = [
       'from',
       'owner',
       'name',
       'is_read'
    ];

    ####################### Begin Relations #######################################
    public function Farm(){
        return $this->belongsTo('App\Models\Farm', 'from', 'id');
    }
    ####################### End Relations #######################################

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
