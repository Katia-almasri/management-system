<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LaratrustUserTrait;
    use SoftDeletes;

    protected $table = 'farms';
    protected $primaryKey='id';
    protected $fillable = [
       'name',
       'owner',
       'location',
       'mobile_number',
       'username',
       'password',
       'added_by',
       'governorate_id'

    ];

    ############################## Begin Relations #############################

    public function purchaseOffer(){
        return $this->hasMany('App\Models\PurchaseOffer', 'farm_id', 'id');
    }

    public function salesPurchasingRequests(){
        return $this->hasMany('App\Models\salesPurchasingRequset', 'farm_id', 'id');
    }


    public function governate(){
        return $this->belongsTo('App\Models\Governate', 'governorate_id', 'id');
    }

    public function governorate(){
        return $this->belongsTo('App\Models\Governorate', 'governorate_id', 'id');
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
