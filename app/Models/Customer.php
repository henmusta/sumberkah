<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = false;
    protected $table = 'customer';
    protected $fillable = [
      'name',
      'alamat',
      'kontak',
      'telp',
      'keterangan_customer',
      'validasi',
    ];

    protected static $recordEvents = ['deleted', 'updated'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly([
                    'name',
                    'alamat',
                    'kontak',
                    'telp',
                    'keterangan_customer'
                  ])
                ->setDescriptionForEvent(fn(string $eventName) => "Modul Customer {$eventName}")
                ->useLogName('Customer');
    }

    public function rute()
    {
      return $this->hasMany(Rute::class,  'customer_id');
    }


}
