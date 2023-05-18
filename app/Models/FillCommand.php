<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FillCommand extends Model
{
    use HasFactory;

    protected $table = 'fill_commands';
    protected $primaryKey='id';
    protected $fillable = [
       'command_id',
       'input_weight'
    ];

     ############################## Begin Relations #############################
     public function command(){
        return $this->belongsTo('App\Models\Command', 'command_id', 'id');
    }

    //MORPH
    public function fillCommad(){
        return $this->morphTo();
    }

}
