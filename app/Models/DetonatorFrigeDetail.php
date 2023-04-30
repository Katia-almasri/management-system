<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrigeDetail extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige_details';
    protected $primaryKey='id';
    protected $fillable = [
       'detonator_frige_id',
       'weight',
       'amount',
       'cur_weight',
       'cur_amount',
       'date_of_destruction',
       'expiration_date'   
    ];

    ############################## Begin Relations #############################
    public function detonatorFrige(){
        return $this->belongsTo('App\Models\DetonatorFrige', 'detonator_frige_id', 'id');
    }

    public function DetonatorFrigeOutputs(){
        return $this->hasMany('App\Models\DetonatorFrigeOutput', 'detonator_frige_details_id', 'id');
    }
}
