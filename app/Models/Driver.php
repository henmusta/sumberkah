<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    public $timestamps = false;
    protected $table = 'driver';
    protected $fillable = [
      'name',
      'validasi',
      'kasbon',
      'alamat',
      'telp',
      'keterangan_driver',
      'ktp',
      'sim',
      'panggilan',
      'tempat_lahir',
      'tgl_lahir',
      'image_foto',
      'image_sim',
      'image_ktp',
      'tgl_sim',
      'status_aktif',
      'tgl_aktif',
      'tgl_nonaktif',
      'status_jalan',
      'status_hapus',
      'darurat_name',
      'darurat_telp',
      'darurat_ref'
    ];


}
