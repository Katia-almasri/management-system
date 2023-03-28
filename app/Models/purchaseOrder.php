<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';
    protected $primaryKey='id';
    protected $fillable = [
       'manager_id',
       'farm_id',
       'weight'
    ];

     ############################## Begin Relations #############################
     public function Farms(){
        return $this->hasMany('App\Models\Farm', 'farm_id', 'id');
    }

    public function Managers(){
        return $this->hasMany('App\Models\Manager', 'manager_id', 'id');
    }
    ############################## End Relations ##############################

}
