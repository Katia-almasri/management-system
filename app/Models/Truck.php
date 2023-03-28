<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'trucks';
    protected $primaryKey='id';
    protected $fillable = [
       'mashenism_coordinator_id',
       'name',
       'model',
       'storage_capacity',
       'state'
    ];

     ############################## Begin Relations #############################
     public function manager(){
        return $this->belongsTo('App\Models\Manager', 'mashenism_coordinator_id', 'id');
    }

    public function trips(){
        return $this->hasMany('App\Models\Trip', 'truck_id', 'id');
    }

    ############################## End Relations ##############################
}
