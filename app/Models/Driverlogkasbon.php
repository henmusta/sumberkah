<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driverlogkasbon extends Model
{
    public $timestamps = false;
    protected $table = 'driver_logkasbon';
    protected $fillable = [
      'driver_id',
      'penggajian_id',
      'payment_joborder_id',
      'joborder_id',
      'nominal',
    ];
}
