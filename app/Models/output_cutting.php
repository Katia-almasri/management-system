<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class output_cutting extends Model
{
    use HasFactory;
    protected $table = 'output_cuttings';
    protected $primaryKey='id';
    protected $fillable = [
       'production_date'
    ];

     ############################## Begin Relations #############################


    public function input_cutting(){
        return $this->hasMany('App\Models\InputCutting', 'output_citting_id ', 'id');
    }

    public function detail_output_cutiing(){
        return $this->hasMany('App\Models\output_cutting_detail', 'output_cutting_id', 'id');
    }

    public function detail_output_remnat(){
        return $this->hasMany('App\Models\output_remnat_details', 'output_cutting_id', 'id');
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
