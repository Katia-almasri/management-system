<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use Carbon\Carbon;

class Manager extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LaratrustUserTrait;

    protected $table = 'managers';
    protected $guard = 'ceo';
    protected $primaryKey='id';
    protected $fillable = [
       'managig_level',
       'first_name',
       'last_name',
       'username',
       'password',
       'date_of_hiring',
       'date_of_leave',
       'secret_code',
    ];

     ############################## Begin Relations #############################
     public function purchaseOrder(){
        return $this->hasMany('App\Models\purchaseOrder', 'manager_id', 'id');
    }

    public function salesPurchasingRequset1(){
        return $this->hasMany('App\Models\salesPurchasingRequset', 'purchasing_manager_id', 'id');
    }
    public function salesPurchasingRequset2(){
        return $this->hasMany('App\Models\salesPurchasingRequset', 'ceo_id', 'id');
    }
    public function salesPurchasingRequset3(){
        return $this->hasMany('App\Models\salesPurchasingRequset', 'mechanism_coordinator_id', 'id');
    }

    public function noteSender(){
        return $this->hasMany('App\Models\Note', 'production_manager_id', 'id');
    }

    public function noteReciver(){
        return $this->hasMany('App\Models\Note', 'purchasing_manager_id', 'id');
    }

    public function trips(){
        return $this->hasMany('App\Models\Trip', 'manager_id', 'id');
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
