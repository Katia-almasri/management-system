<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class sellingortype extends Model
{
    use HasFactory;

    protected $table = 'sellingortypes';
    protected $primaryKey='id';
    protected $fillable = [
       'name'
    ];

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
