<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exipration extends Model
{
  use HasFactory;
  protected $table = 'exiprations';
  protected $primaryKey = 'id';
  protected $fillable = [
    'weight',
    'output_from',
    'output_type_production',
    'reason_of_expirations'
  ];

  public function inputable()
  {
    return $this->morphTo();
  }

  ############################# Begin Accessors ##############################endregion
  public function getCreatedAtAttribute($date)
  {
    if ($date != null)
      return Carbon::parse($date)->format('Y-m-d H:i');
    return $date;
  }

  public function getUpdatedAtAttribute($date)
  {
    if ($date != null)
      return Carbon::parse($date)->format('Y-m-d H:i');
    return $date;
  }

}