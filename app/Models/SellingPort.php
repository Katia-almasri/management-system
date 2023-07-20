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

class SellingPort extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LaratrustUserTrait;
    use SoftDeletes;

    protected $table = 'selling_ports';
    protected $primaryKey='id';
    protected $fillable = [
       'owner',
       'location',
       'governorate_id',
       'mobile_number',
       'username',
       'password',
       'admin',
       'approved_at',
       'name',
       'type'
    ];

     ############################## Begin Relations #############################
    public function salesPurchasingRequests(){
        return $this->hasMany('App\Models\salesPurchasingRequset', 'selling_port_id', 'id');
    }

    public function registerFarmRequestNotif(){
        return $this->hasOne('App\Models\RegisterSellingPortRequestNotif', 'from', 'id');
    }

    public function RequestToCompanyNotif(){
        return $this->hasOne('App\Models\RequestToCompanyNotif', 'from', 'id');
    }

    public function AddSalesPurchasingNotif(){
        return $this->hasOne('App\Models\AddSalesPurchasingNotif', 'selling_port_id', 'id');
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
