<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZeroFrigeOutput extends Model
{
    use HasFactory;

    protected $table = 'zero_frige_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_date',
       'weight',
       'amount'
    ];

     ############################## Begin Relations #############################
     public function ZeroFrigeInputOutput(){
        return $this->hasMany('App\Models\ZeroFrigeInputOutput', 'output_id', 'id');
    }
 }
