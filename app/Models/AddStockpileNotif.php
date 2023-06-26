<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddStockpileNotif extends Model
{
    use HasFactory;

    protected $table = 'add_stockpile_notifs';
    protected $primaryKey='id';
    protected $fillable = [
       'warehouse_username',
       'is_read',
       'type'
    ];

}
