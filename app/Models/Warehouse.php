<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';
    protected $primaryKey='id';
    protected $fillable = [
       'tot_weight',
       'tot_amount' ,
       'stockpile',
       'minimum'      
    ];

         ############################## Begin Relations #############################
         public function zeroFriges(){
            return $this->hasMany('App\Models\ZeroFrige', 'warehouse_id', 'id');
        }

        public function detonatorFriges(){
            return $this->hasMany('App\Models\DetonatorFrige', 'warehouse_id', 'id');
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
