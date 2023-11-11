<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Penggajian extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = false;
    protected $table = 'penggajian';
    protected $fillable = [
      'joborder_id',
      'kasbon_id',
      'kode_gaji',
      'tgl_gaji',
      'driver_id',
      'mobil_id',
      'bulan_kerja',
      'sub_total',
      'bonus',
      'kasbon_id',
      'nominal_kasbon',
      'total_gaji',
      'sisa_gaji',
      'keterangan_gaji',
      'keterangan_kasbon',
      'total_payment',
      'status_payment',
      'kode_joborder',
      'created_by',
      'updated_by',
    ];

    protected static $recordEvents = ['deleted', 'updated'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly([
                    'joborder_id',
                    'kode_gaji',
                    'tgl_gaji',
                    'driver_id',
                    'mobil_id',
                    'kasbon_id',
                    'bulan_kerja',
                    'sub_total',
                    'bonus',
                    'kasbon_id',
                    'nominal_kasbon',
                    'total_gaji',
                    'kode_joborder',
                    'keterangan_gaji',
                    'keterangan_kasbon',
                    // 'total_payment',
                    // 'status_payment',
                  ])
                ->setDescriptionForEvent(fn(string $eventName) => "Modul Penggajian {$eventName}")
                ->dontLogIfAttributesChangedOnly([
                    'status_payment',
                    'total_payment',
                    'sisa_gaji',
                ])
                ->useLogName('Penggajian');
    }

    public function driver()
    {
      return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function kasbon()
    {
      return $this->belongsTo(Kasbon::class, 'kasbon_id');
    }

    public function mobil()
    {
      return $this->belongsTo(Mobil::class, 'mobil_id')->with('jenismobil');
    }

    public function joborder()
    {
        return $this->hasMany(Joborder::class, 'penggajian_id');
    }

    public function payment()
    {
      return $this->hasMany(PaymentGaji::class, 'penggajian_id');
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
