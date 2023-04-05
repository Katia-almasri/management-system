<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractDetail extends Model
{
    use HasFactory;


    protected $table = 'contract_datails';
    protected $primaryKey='id';
    protected $fillable = [
       'contract_id',
       'type',
       'amount'
    ];

     ############################## Begin Relations #############################
     public function contract(){
        return $this->belongsTo('App\Models\Contract', 'contract_id', 'id');
    }

    ############################## End Relations ##############################
}
