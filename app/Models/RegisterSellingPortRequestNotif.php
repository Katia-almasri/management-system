<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterSellingPortRequestNotif extends Model
{
    use HasFactory;
    protected $table = 'register_selling_port_request_notifs';
    protected $primaryKey='id';
    protected $fillable = [
       'from',
       'owner',
       'name',
       'is_read'
    ];

    ####################### Begin Relations #######################################
    public function SellingPort(){
        return $this->belongsTo('App\Models\SellingPort', 'from', 'id');
    }
    ####################### End Relations #######################################
}
