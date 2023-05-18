<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInputOutput extends Model
{
    use HasFactory;

    protected $table = 'store_input_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_id',
       'input_id',
       'weight',
       'amount'
    ];

    ####################### Begin Relations ###############################
    public function storeDetail(){
        return $this->belongsTo('App\Models\StoreDetail', 'input_id', 'id');
    }

    public function StoreOutput(){
        return $this->belongsTo('App\Models\StoreOutput', 'output_id', 'id');
    }

}
