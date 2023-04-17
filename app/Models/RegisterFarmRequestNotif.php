<?php

namespace App\Models;

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
}
