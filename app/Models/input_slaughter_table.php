<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class input_slaughter_table extends Model
{
    use HasFactory;


    protected $table = 'input_slaughters';
    protected $primaryKey='id';
    protected $fillable = [
       'type_id',
       'weight',
       'income_date',
       'output_date',
       'productionId',
       'output_id'
    ];



    public function typeChicken(){
        return $this->belongsTo('App\Models\typeChicken', 'type_id', 'id');
    }

    public function inputProduction(){
        return $this->belongsTo('App\Models\InputProduction', 'productionId', 'id');
    }

    public function output_slaughter(){
        return $this->belongsTo('App\Models\outPut_SlaughterSupervisor_table', 'output_id', 'id');
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
