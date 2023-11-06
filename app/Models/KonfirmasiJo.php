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

    // protected static function booted(): void
    // {
    //     static::created(function (Joborder $joborder) {
    //         $driver       = $joborder->driver()->first();
    //         $st_driver    = Driver::selectRaw('driver.`id` AS id,
    //                                            SUM(IF(status_joborder = "1", 0, 1)) AS count_jo')
    //                                            ->Join('joborder', 'joborder.driver_id', '=', 'driver.id')
    //                                            ->where('driver.id', $driver->id)
    //                                            ->groupBy('driver.id')->first();

    //         $status_jalan    = Driver::where('id', $driver->id)->first();
    //         $balance =  $st_driver['count_jo'] > 0 ? 1 : 0;
    //         $status_jalan->status_jalan  = $balance;
    //         $status_jalan->save();
    //     });
    // }



    public function customer()
    {
      return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function joborder()
    {
      return $this->belongsTo(Joborder::class, 'joborder_id')->with('mobil', 'ruteawal', 'ruteakhir', 'muatan', 'rute', 'driver');
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
