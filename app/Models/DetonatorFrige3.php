<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige3 extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige3s';
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

    public function det3Outputs(){
        return $this->hasMany('App\Models\DetonatorFrige3Output', 'det3_id', 'id');
    }


    public function detonatorFrige3Details(){
        return $this->hasMany('App\Models\DetonatorFrige3Detail', 'detonator_frige_3_id', 'id');
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
