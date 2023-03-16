<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    public $timestamps = false;
    protected $table = 'rute';
    protected $fillable = [
      'kode_rute',
      'customer_id',
      'first_rute_id',
      'last_rute_id',
      'muatan_id',
      'jenismobil_id',
      'gaji',
      'uang_jalan',
      'ritase_tonase',
      'harga',
      'validasi',
      'validasi_delete',
      'keterangan',
      1
    ];

    public function customer()
    {
      return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function ruteawal()
    {
      return $this->belongsTo(Alamatrute::class, 'first_rute_id');
    }
    public function ruteakhir()
    {
      return $this->belongsTo(Alamatrute::class, 'last_rute_id');
    }
    public function muatan()
    {
      return $this->belongsTo(Muatan::class, 'muatan_id');
    }
    public function jenismobil()
    {
      return $this->belongsTo(Jenismobil::class, 'jenismobil_id');
    }
}
