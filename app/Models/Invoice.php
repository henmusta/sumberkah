<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $timestamps = false;
    protected $table = 'invoice';
    protected $fillable = [
      'customer_id',
      'kode_invoice',
      'total_tonase',
      'tgl_invoice',
      'tgl_jatuh_tempo',
      'payment_hari',
      'tambahan_potongan',
      'sub_total',
      'nominal_tambahan_potongan',
      'ppn',
      'nominal_ppn',
      'keterangan_invoice',
      'sisa_tagihan',
      'total_payment',
      'status_payment',
      'total_harga',
      'created_by',
      'updated_by',
    ];

    public function customer()
    {
      return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function joborder()
    {
        return $this->hasMany(Joborder::class, 'invoice_id');
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
