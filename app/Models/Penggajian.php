<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    public $timestamps = false;
    protected $table = 'penggajian';
    protected $fillable = [
      'joborder_id',
      'kode_gaji',
      'tgl_gaji',
      'driver_id',
      'mobil_id',
      'bulan_kerja',
      'sub_total',
      'bonus',
      'nominal_kasbon',
      'total_gaji',
      'sisa_gaji',
      'keterangan_gaji',
      'total_payment',
      'status_payment',
      'created_by',
      'updated_by',
    ];



    public function driver()
    {
      return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function mobil()
    {
      return $this->belongsTo(Mobil::class, 'mobil_id');
    }

    public function joborder()
    {
        return $this->hasMany(Joborder::class, 'penggajian_id');
    }

    public function createdby()
    {
      return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedby()
    {
      return $this->belongsTo(User::class, 'updated_by');
    }


}
