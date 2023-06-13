<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige2InputOutput extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige2_input_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_id',
       'input_id',
       'weight',
       'amount'
    ];

    ####################### Begin Relations ###############################
    public function DetonatorFrige2Detail(){
        return $this->belongsTo('App\Models\DetonatorFrige2Detail', 'input_id', 'id');
    }

    public function DetonatorFrige2Output(){
        return $this->belongsTo('App\Models\DetonatorFrige2Output', 'output_id', 'id');
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
