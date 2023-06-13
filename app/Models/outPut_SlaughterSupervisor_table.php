<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class outPut_SlaughterSupervisor_table extends Model
{
    use HasFactory;
    protected $table = 'output_slaughtersupervisors';
    protected $primaryKey='id';
    protected $fillable = [
       'waste_value',
       'production_date',
    ];

     ############################## Begin Relations #############################


    public function input_slaughter(){
        return $this->hasMany('App\Models\input_slaughter_table', 'output_id', 'id');
    }

    public function detail_output_slaughter(){
        return $this->hasMany('App\Models\outPut_SlaughterSupervisor_detail', 'output_id', 'id');
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
