<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory;

    protected $table = 'commands';
    protected $primaryKey='id';
    protected $fillable = [
       'done'
    ];

    ####################### Begin Relations #######################################
    public function commandDetails(){
        return $this->hasMany('App\Models\CommandDetail', 'command_id', 'id');
    } 

    public function fillCommads(){
        return $this->hasMany('App\Models\FillCommand', 'command_id', 'id');
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
