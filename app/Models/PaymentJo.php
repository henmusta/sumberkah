<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentJo extends Model
{
    use HasFactory, LogsActivity;
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
      'keterangan',
      'created_by',
      'updated_by'
    ];

    protected static $logAttributes = [
        'joborder_id',
        'kasbon_id',
        'kode_joborder',
        'tgl_payment',
        'nominal',
        'nominal_kasbon',
        'jenis_payment',
        'keterangan_kasbon',
        'keterangan',
    ];


    protected static $recordEvents = ['deleted', 'updated'];
    public function getActivitylogOptions(): LogOptions
    {
        $attributesToLog = [
            'joborder_id',
            'kasbon_id',
            'kode_joborder',
            'tgl_payment',
            'nominal',
            'nominal_kasbon',
            'jenis_payment',
            'keterangan_kasbon',
            'keterangan',
        ];
         $data = LogOptions::defaults()
            ->logOnly($attributesToLog)
            ->setDescriptionForEvent(fn(string $eventName) => "Modul Payment Joborder {$eventName}")
            ->useLogName('Payment Joborder');
       return $data;
    }

    public function joborder()
    {
      return $this->belongsTo(Joborder::class, 'joborder_id')->with('customer','ruteawal','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil', 'createdby');
    }

    public function kasbon()
    {
      return $this->belongsTo(Kasbon::class, 'kasbon_id');
    }
}
