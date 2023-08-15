<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command_sales extends Model
{
    use HasFactory;
    protected $table = 'command_sales';
    protected $primaryKey='id';
    protected $fillable = [
       'done',
       'sales_request_id'
    ];

    ####################### Begin Relations #######################################
    public function sales_request(){
        return $this->belongsTO('App\Models\salesPurchasingRequset', 'sales_request_id', 'id');
    }

    public function commandSalesDetails(){
        return $this->hasMany('App\Models\commandSalesDetail', 'command_id', 'id');
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
