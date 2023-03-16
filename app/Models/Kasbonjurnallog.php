<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasbonjurnallog extends Model
{
    public $timestamps = false;
    protected $table = 'kasbon_jurnallog';
    protected $fillable = [
      'driver_id',
      'kasbon_id',
      'kode_kasbon',
      'jenis',
      'tgl_kasbon',
      'keterangan',
      'debit',
      'kredit',
      'joborder_id',
      'penggajian_id',
    ];

    public function driver()
    {
      return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function kasbon()
    {
      return $this->belongsTo(Kasbon::class, 'kasbon_id');
    }

    public function joborder()
    {
      return $this->belongsTo(Joborder::class, 'joborder_id');
    }

    public function gaji()
    {
      return $this->belongsTo(penggajian::class, 'penggajian_id');
    }

}
