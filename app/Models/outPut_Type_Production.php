<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class outPut_Type_Production extends Model
{
    use HasFactory;
    protected $table = 'output_production_types';
    protected $primaryKey='id';
    protected $fillable = [
       'type',
    ];

     ############################## Begin Relations #############################
    public function productionManager(){
        return $this->hasMany('App\Models\outPut_SlaughterSupervisor_detail', 'type_id', 'id');
    }
    public function output_Cutting(){
        return $this->hasMany('App\Models\output_cutting_detail', 'type_id', 'id');
    }

    public function output_Manufacturings(){
        return $this->hasMany('App\Models\OutputManufacturingDetails', 'type_id', 'id');
    }



    public function warehouse(){
        return $this->hasMany('App\Models\Warehouse', 'type_id', 'id');
    }

    ############################## End Relations ##############################

}
