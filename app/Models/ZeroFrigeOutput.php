<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ZeroFrigeOutput extends Model
{
    use HasFactory;

    protected $table = 'zero_frige_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'zero_frige_details_id',
       'output_date',
       'note'
    ];

     ############################## Begin Relations #############################
     public function zeroFrigeDetail(){
        return $this->belongsTo('App\Models\ZeroFrigeDetail', 'zero_frige_details_id', 'id');
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
