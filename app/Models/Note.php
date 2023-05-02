<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $table = 'notes';
    protected $primaryKey='id';
    protected $fillable = [
       'production_manager_id',
       'purchasing_manager_id',
       'detail',
       'sender'
    ];

     ############################## Begin Relations #############################
    public function productionManager(){
        return $this->belongsTo('App\Models\Manager', 'production_manager_id', 'id');
    }
    public function purchasingManager(){
        return $this->belongsTo('App\Models\Manager', 'purchasing_manager_id', 'id');
    }

    ############################## End Relations ##############################
}
