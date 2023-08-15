<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddSalesPurchasingNotif extends Model
{
    use HasFactory;
    protected $table = 'add_sales_purchasing_notifs';
    protected $primaryKey='id';
    protected $fillable = [
       'selling_port_id',
       'farm_id',
       'is_read',
       'total_amount',
       'type'
    ];

    ####################### Begin Relations #######################################
    public function SellingPort(){
        return $this->belongsTo('App\Models\SellingPort', 'selling_port_id', 'id');
    } 

    public function Farm(){
        return $this->belongsTo('App\Models\Farm', 'farm_id', 'id');
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
