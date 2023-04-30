<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class outPut_SlaughterSupervisor_table extends Model
{
    use HasFactory;
    protected $table = 'output_slaughtersupervisors';
    protected $primaryKey='id';
    protected $fillable = [
       'waste value',
       'production_date',
    ];

     ############################## Begin Relations #############################


    public function input_slaughter(){
        return $this->hasMany('App\Models\input_slaughter_table', 'output_id', 'id');
    }

    public function detail_output_slaughter(){
        return $this->hasMany('App\Models\outPut_SlaughterSupervisor_detail', 'output_id', 'id');
    }


}
