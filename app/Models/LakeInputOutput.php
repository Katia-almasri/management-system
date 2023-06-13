<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LakeInputOutput extends Model
{
    use HasFactory;

    protected $table = 'lake_input_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_id',
       'input_id',
       'weight',
       'amount'
    ];

    ####################### Begin Relations ###############################
    public function lakeDetail(){
        return $this->belongsTo('App\Models\LakeDetail', 'input_id', 'id');
    }

    public function lakeOutput(){
        return $this->belongsTo('App\Models\LakeOutput', 'output_id', 'id');
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
