<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ZeroFrige extends Model
{
    use HasFactory;

    protected $table = 'zero_friges';
    protected $primaryKey='id';
    protected $fillable = [
       'warehouse_id',
       'weight',
       'amount'       
    ];

      ############################## Begin Relations #############################
      public function warehouse(){
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
    }

    public function zeroFrigeDetails(){
        return $this->hasMany('App\Models\ZeroFrigeDetail', 'zero_frige_id', 'id');
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
