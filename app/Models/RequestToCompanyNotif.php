<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestToCompanyNotif extends Model
{
    use HasFactory;

    protected $table = 'request_to_company_notifs';
    protected $primaryKey='id';
    protected $fillable = [
       'from',
       'total_amount',
       'is_read'
    ];

    ####################### Begin Relations #######################################
    public function SellingPort(){
        return $this->belongsTo('App\Models\SellingPort', 'from', 'id');
    }
    ####################### End Relations #######################################
}
