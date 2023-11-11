<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class PaymentInvoice extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = false;
    protected $table = 'payment_invoice';
    protected $fillable = [
      'invoice_id',
      'kode_invoice',
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
            'invoice_id',
            'kode_invoice',
            'tgl_payment',
            'nominal',
            'jenis_payment',
            'keterangan',
        ];
         $data = LogOptions::defaults()
            ->logOnly($attributesToLog)
            ->setDescriptionForEvent(fn(string $eventName) => "Modul Payment Invoice {$eventName}")
            ->useLogName('Payment Invoice');
       return $data;
    }

    public function invoice()
    {
      return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
