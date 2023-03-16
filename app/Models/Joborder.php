<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joborder extends Model
{
    public $timestamps = false;
    protected $table = 'joborder';
    protected $fillable = [
      'invoice_id',
      'kode_invoice',
      'penggajian_id',
      'kode_gaji',
      'kode_joborder',
      'kode_rute',
      'rute_id',
      'tgl_joborder',
      'driver_id',
      'jenismobil_id',
      'mobil_id',
      'customer_id',
      'muatan_id',
      'first_rute_id',
      'last_rute_id',
      'uang_jalan',
      'biaya_lain',
      'tambahan_potongan',
      'keterangan_joborder',
      'status_joborder',
      'total_uang_jalan',
      'total_kasbon',
      'total_payment',
      'sisa_uang_jalan',
      'status_payment',
      'created_by',
      'updated_by'
    ];

    public function customer()
    {
      return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function driver()
    {
      return $this->belongsTo(Driver::class, 'driver_id');
    }
    public function rute()
    {
      return $this->belongsTo(Rute::class, 'rute_id');
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
    public function mobil()
    {
      return $this->belongsTo(Mobil::class, 'mobil_id');
    }

    public function jenismobil()
    {
      return $this->belongsTo(Jenismobil::class, 'jenismobil_id');
    }

    public function payment()
    {
      return $this->hasMany(Paymentjo::class, 'joborder_id');
    }
    public function konfirmasijo()
    {
      return $this->hasMany(KonfirmasiJo::class, 'joborder_id')->with('createdby');
    }

    public function gaji()
    {
        return $this->belongsTo(Penggajian::class, 'penggajian_id')->with('createdby');
    }


    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id')->with('createdby');
    }

    public function kasbon()
    {
      return $this->hasMany(Kasbon::class, 'joborder_id');
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
