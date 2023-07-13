<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output_remnat extends Model
{
    use HasFactory;
    protected $table = 'output_remnat';
    protected $primaryKey='id';
    protected $fillable = [
       'by_section'
    ];

     ############################## Begin Relations #############################

    public function output_remnat_details(){
        return $this->hasMany('App\Models\Output_remnat_details', 'output_remnat_id', 'id');
    }

}
