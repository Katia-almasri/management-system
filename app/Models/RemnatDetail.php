<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemnatDetail extends Model
{
    use HasFactory;
    protected $table = 'remnat_details';
    protected $primaryKey='id';
    protected $fillable = [
       'weight',
       'output_remnat_det_id',
       'remant_id'
    ];

     ############################## Begin Relations #############################
     public function output_remnat_detail(){
        return $this->belongsTo('App\Models\Output_remnat_details', 'output_remnat_det_id', 'id');
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
