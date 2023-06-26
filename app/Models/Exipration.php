<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exipration extends Model
{
    use HasFactory;
    protected $table = 'exiprations';
    protected $primaryKey='id';
    protected $fillable = [
       'weight',
       'output_from',
       'output_type_production',
       'reason_of_expirations'
    ];

    public function inputable(){
        return $this->morphTo();
  }

}
