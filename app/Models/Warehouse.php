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
       'minimum'      
    ];

         ############################## Begin Relations #############################
         public function zeroFriges(){
            return $this->hasMany('App\Models\ZeroFrige', 'warehouse_id', 'id');
        }

        public function detonatorFriges(){
            return $this->hasMany('App\Models\DetonatorFrige', 'warehouse_id', 'id');
        }
    
}
