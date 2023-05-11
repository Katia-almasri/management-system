<?php

namespace App\Models;

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

        public function detonatorFrige(){
            return $this->hasOne('App\Models\DetonatorFrige', 'warehouse_id', 'id');
        }

        public function lake(){
            return $this->hasOne('App\Models\Lake', 'warehouse_id', 'id');
        }

        public function outPutSlaughterSupervisorType(){
            return $this->belongsTo('App\Models\outPut_SlaughterSupervisorType_table', 'type_id', 'id');
        }


        
    
}
