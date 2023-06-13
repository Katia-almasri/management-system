<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandDetail extends Model
{
    use HasFactory;
    protected $table = 'command_details';
    protected $primaryKey='id';
    protected $fillable = [
       'warehouse_id',
       'command_id',
       'input_weight',
       'command_weight',
       'cur_weight',
       'from',
       'to',
       'is_filled'
    ];

    ####################### Begin Relations #######################################
    public function command(){
        return $this->belongsTo('App\Models\Command', 'command_id', 'id');
    } 

    public function warehouse(){
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
    } 

    ####################### End Relations #######################################

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
