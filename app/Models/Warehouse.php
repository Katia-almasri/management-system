<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';
    protected $primaryKey='id';
    protected $fillable = [
       'tot_weight', 
       'tot_amount' ,
       'stockpile',
       'minimum',
       'type_id'    
    ];

         ############################## Begin Relations #############################
         public function zeroFrige(){
            return $this->hasOne('App\Models\ZeroFrige', 'warehouse_id', 'id');
        }

        public function detonatorFrige1(){
            return $this->hasOne('App\Models\DetonatorFrige1', 'warehouse_id', 'id');
        }

        public function detonatorFrige2(){
            return $this->hasOne('App\Models\DetonatorFrige2', 'warehouse_id', 'id');
        }

        public function detonatorFrige3(){
            return $this->hasOne('App\Models\DetonatorFrige3', 'warehouse_id', 'id');
        }

        public function lake(){
            return $this->hasOne('App\Models\Lake', 'warehouse_id', 'id');
        }

        public function store(){
            return $this->hasOne('App\Models\Store', 'warehouse_id', 'id');
        }

        public function outPut_Type_Production(){
            return $this->belongsTo('App\Models\outPut_Type_Production', 'type_id', 'id');
        }

        public function commandDetails(){
            return $this->hasMany('App\Models\CommandDetail', 'warehouse_id', 'id');
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
