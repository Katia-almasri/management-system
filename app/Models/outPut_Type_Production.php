<?php

namespace App\Models;

use Carbon\Carbon;
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
    public function inputCutting(){
        return $this->hasMany('App\Models\InputCutting', 'type_id', 'id');
    }
    public function inputManufacturing(){
        return $this->hasMany('App\Models\InputManufacturing', 'type_id', 'id');
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
