<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentJo extends Model
{
    public $timestamps = false;
    protected $table = 'payment_joborder';
    protected $fillable = [
      'joborder_id',
      'kasbon_id',
      'kode_joborder',
      'tgl_payment',
      'nominal',
      'nominal_kasbon',
      'jenis_payment',
      'keterangan_kasbon',
      'keterangan'
    ];

    public function joborder()
    {
      return $this->belongsTo(Joborder::class, 'joborder_id');
    }

    public function kasbon()
    {
      return $this->belongsTo(Kasbon::class, 'kasbon_id');
    }
}
