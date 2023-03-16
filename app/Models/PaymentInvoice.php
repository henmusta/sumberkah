<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInvoice extends Model
{
    public $timestamps = false;
    protected $table = 'payment_invoice';
    protected $fillable = [
      'invoice_id',
      'kode_invoice',
      'tgl_payment',
      'nominal',
      'jenis_payment',
      'keterangan'
    ];

    public function invoice()
    {
      return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
