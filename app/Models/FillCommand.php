<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FillCommand extends Model
{
    use HasFactory;

    protected $table = 'fill_commands';
    protected $primaryKey='id';
    protected $fillable = [
       'command_id',
       'input_weight'
    ];

     ############################## Begin Relations #############################
     public function command(){
        return $this->belongsTo('App\Models\Command', 'command_id', 'id');
    }

    //MORPH
    public function fillCommad(){
        return $this->morphTo();
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
