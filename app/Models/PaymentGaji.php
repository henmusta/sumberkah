<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGaji extends Model
{
    public $timestamps = false;
    protected $table = 'payment_gaji';
    protected $fillable = [
      'penggajian_id',
      'kode_gaji',
      'tgl_payment',
      'nominal',
      'jenis_payment',
      'keterangan'
    ];

    public function penggajian()
    {
      return $this->belongsTo(Penggajian::class, 'penggajian_id');
    }
}
