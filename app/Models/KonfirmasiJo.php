<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfirmasiJo extends Model
{
    public $timestamps = false;
    protected $table = 'konfirmasi_joborder';
    protected $fillable = [
      'invoice_id',
      'kode_invoice',
      'penggajian_id',
      'kode_gaji',
      'joborder_id',
      'customer_id',
      'kode_joborder',
      'tgl_konfirmasi',
      'tgl_muat',
      'tgl_bongkar',
      'berat_muatan',
      'konfirmasi_biaya_lain',
      'keterangan_konfirmasi',
      'status_ekspedisi',
      'status',
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
      return $this->belongsTo(Joborder::class, 'joborder_id')->with('mobil', 'ruteawal', 'ruteakhir', 'muatan', 'rute');
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
