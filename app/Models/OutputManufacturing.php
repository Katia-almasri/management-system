<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputManufacturing extends Model
{
    use HasFactory;
    protected $table = 'output_manufacturings';
    protected $primaryKey='id';
    protected $fillable = [
       'production_date'
    ];

     ############################## Begin Relations #############################


    public function input_manufacturing(){
        return $this->hasMany('App\Models\InputManufacturing', 'output_manufacturing_id', 'id');
    }

    public function detail_output_manufacturing(){
        return $this->hasMany('App\Models\OutputManufacturingDetails', 'output_manufacturing_id', 'id');
    }

    public function detail_output_remnat(){
        return $this->hasMany('App\Models\output_remnat_details', 'output_manufacturing_id', 'id');
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
