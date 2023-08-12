<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commandSalesDetail extends Model
{
    use HasFactory;

    protected $table = 'command_sales_details';
    protected $primaryKey='id';
    protected $fillable = [
       'command_id',
       'req_detail_id',
       'cur_weight',
       'from',
       'to',
       'is_filled',
    ];

     ############################## Begin Relations #############################
     public function commandSales(){
        return $this->belongsTo('App\Models\Command_sales', 'command_id', 'id');
    }

    public function salesPurchaseRequestDetail(){
        return $this->belongsTo('App\Models\salesPurchasingRequsetDetail', 'req_detail_id', 'id');
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
