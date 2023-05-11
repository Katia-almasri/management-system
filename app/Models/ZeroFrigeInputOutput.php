<?php

namespace App\Models;

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


}
