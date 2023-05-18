<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory;

    protected $table = 'commands';
    protected $primaryKey='id';
    protected $fillable = [
       'done'
    ];

    ####################### Begin Relations #######################################
    public function commandDetails(){
        return $this->hasMany('App\Models\CommandDetail', 'command_id', 'id');
    } 

    public function fillCommads(){
        return $this->hasMany('App\Models\FillCommand', 'command_id', 'id');
    }
    ####################### End Relations #######################################


}
