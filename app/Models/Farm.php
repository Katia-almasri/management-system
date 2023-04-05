<?php

namespace App\Models;

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
       'password'
    ];

     ############################## Begin Relations #############################

    public function purchaseOffer(){
        return $this->hasMany('App\Models\PurchaseOffer', 'farm_id', 'id');
    }

    public function salesPurchasingRequests(){
        return $this->hasMany('App\Models\salesPurchasingRequset', 'farm_id', 'id');
    }

    ############################## End Relations ##############################
}
