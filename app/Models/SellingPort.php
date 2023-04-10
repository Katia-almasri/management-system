<?php

namespace App\Models;

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

    public function contract(){
        return $this->hasMany('App\Models\Contract', 'selling_port_id', 'id');
    }

    ############################## End Relations ##############################
}
