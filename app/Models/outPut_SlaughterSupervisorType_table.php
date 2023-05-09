<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class outPut_SlaughterSupervisorType_table extends Model
{
    use HasFactory;
    protected $table = 'output_production_types';
    protected $primaryKey='id';
    protected $fillable = [
       'type',
       'number_day_validity',
    ];

     ############################## Begin Relations #############################
    public function productionManager(){
        return $this->hasMany('App\Models\outPut_SlaughterSupervisor_detail', 'type_id', 'id');
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
