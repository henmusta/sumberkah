<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentGaji extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = false;
    protected $table = 'payment_gaji';
    protected $fillable = [
      'penggajian_id',
      'kode_gaji',
      'tgl_payment',
      'nominal',
      'jenis_payment',
      'keterangan',
      'created_by',
      'updated_by'
    ];


    protected static $recordEvents = ['deleted', 'updated'];
    public function getActivitylogOptions(): LogOptions
    {
        $attributesToLog = [
            'penggajian_id',
            'kode_gaji',
            'tgl_payment',
            'nominal',
            'jenis_payment',
            'keterangan'
        ];
         $data = LogOptions::defaults()
            ->logOnly($attributesToLog)
            ->setDescriptionForEvent(fn(string $eventName) => "Modul Payment Gaji {$eventName}")
            ->useLogName('Payment Gaji');
       return $data;
    }

    public function penggajian()
    {
      return $this->belongsTo(Penggajian::class, 'penggajian_id')->with('createdby', 'driver', 'mobil', 'kasbon');
    }
}
