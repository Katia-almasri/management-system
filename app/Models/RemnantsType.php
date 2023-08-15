<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemnantsType extends Model
{
    use HasFactory;
    protected $table = 'remnants_types';
    protected $primaryKey='id';
    protected $fillable = [
       'by_section',
       'name'
    ];

     ############################## Begin Relations #############################
     public function output_remnat_details(){
        return $this->hasMany('App\Models\Output_remnat_details', 'type_remant_id', 'id');
    }

    public function remnats(){
        return $this->hasMany('App\Models\Remnant', 'type_remant_id', 'id');
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
