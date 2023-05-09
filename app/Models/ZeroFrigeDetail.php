<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ZeroFrigeDetail extends Model
{
    use HasFactory;

    protected $table = 'zero_frige_details';
    protected $primaryKey='id';
    protected $fillable = [
       'zero_frige_id',
       'weight',
       'amount',
       'cur_weight',
       'cur_amount',
       'date_of_destruction',
       'expiration_date'   
    ];

    ############################## Begin Relations #############################
    public function zeroFrige(){
        return $this->belongsTo('App\Models\ZeroFrige', 'zero_frige_id', 'id');
    }

    public function zeroFrigeOutputs(){
        return $this->hasMany('App\Models\ZeroFrigeOutput', 'zero_frige_details_id', 'id');
    }

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
