<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Note extends Model
{
    use HasFactory;
    protected $table = 'notes';
    protected $primaryKey='id';
    protected $fillable = [
       'production_manager_id',
       'purchasing_manager_id',
       'detail',
       'sender'
    ];

     ############################## Begin Relations #############################
    public function productionManager(){
        return $this->belongsTo('App\Models\Manager', 'production_manager_id', 'id');
    }
    public function purchasingManager(){
        return $this->belongsTo('App\Models\Manager', 'purchasing_manager_id', 'id');
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
