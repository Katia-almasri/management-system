<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class outPut_SlaughterSupervisor_detail extends Model
{
    use HasFactory;
    protected $table = 'output_slaughtersupervisors_details';
    protected $primaryKey='id';
    protected $fillable = [
       'weight',
       'expiry_date',
       'type_id',
       'output_id'
    ];

    public function productionManager(){
        return $this->belongsTo('App\Models\outPut_Type_Production', 'type_id', 'id');
    }

    public function detail_output_slaughter(){
        return $this->belongsTo('App\Models\outPut_SlaughterSupervisor_table', 'output_id', 'id');
    }


}
