<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOfferNotif extends Model
{
    use HasFactory;

    protected $table = 'add_offer_notifs';
    protected $primaryKey='id';
    protected $fillable = [
       'from',
       'is_read',
       'total_amount'
    ];

    ################### Begin Relations #######################
    public function Farm(){
        return $this->belongsTo('App\Models\Farm', 'from', 'id');
    }
    ################### End   Relations #######################
}
