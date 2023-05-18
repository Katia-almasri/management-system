<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function zeroOutputs(){
        return $this->hasMany('App\Models\ZeroFrigeOutput', 'zero_id', 'id');
    }
}
