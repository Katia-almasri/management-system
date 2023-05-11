<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lake extends Model
{
    use HasFactory;

    protected $table = 'lakes';
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

    public function lakeDetails(){
        return $this->hasMany('App\Models\LakeDetail', 'lake_id', 'id');
    }

}
