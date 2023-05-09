<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class typeChicken extends Model
{
    use HasFactory;

    protected $table = 'type_chickens';
    protected $primaryKey='id';
    protected $fillable = [
       'type',
       'total_amount',
    ];

    public function inputProduction(){
        return $this->hasOne('App\Models\InputProduction', 'type_id', 'id');
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
