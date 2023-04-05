<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;


    protected $table = 'contracts';
    protected $primaryKey='id';
    protected $fillable = [
       'contract_type',
       'accept',
       'datail',
       'selling_port_id'
    ];

     ############################## Begin Relations #############################
     public function contractDetails(){
        return $this->hasMany('App\Models\ContractDetail', 'contract_id', 'id');
    }

    public function sellingPort(){
        return $this->belongsTo('App\Models\SellingPort', 'selling_port_id', 'id');
    }




    ############################## End Relations ##############################
}
