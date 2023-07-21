<?php

namespace App\Models;

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

}
