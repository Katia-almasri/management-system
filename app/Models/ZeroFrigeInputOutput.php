<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZeroFrigeInputOutput extends Model
{
    use HasFactory;

    protected $table = 'zero_frige_input_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_id',
       'input_id',
       'weight',
       'amount'
    ];

    ####################### Begin Relations ###############################
    public function ZeroFrigeDetail(){
        return $this->belongsTo('App\Models\ZeroFrigeDetail', 'input_id', 'id');
    }

    public function ZeroFrigeOutput(){
        return $this->belongsTo('App\Models\ZeroFrigeOutput', 'output_id', 'id');
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
