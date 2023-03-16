<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
    public $timestamps = false;
    protected $table = 'kasbon';
    protected $fillable = [
      'driver_id',
      'kode_kasbon',
      'jenis',
      'tgl_kasbon',
      'keterangan',
      'nominal',
      'joborder_id',
      'penggajian_id',
      'validasi'
    ];


    public function driver()
    {
      return $this->belongsTo(Driver::class, 'driver_id');
    }


    public function joborder()
    {
      return $this->belongsTo(Joborder::class, 'joborder_id');
    }

}
