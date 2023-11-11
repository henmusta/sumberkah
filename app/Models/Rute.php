<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rute extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = false;
    protected $table = 'rute';
    protected $fillable = [
      'kode_rute',
      'customer_id',
      'first_rute_id',
      'last_rute_id',
      'muatan_id',
      'jenismobil_id',
      'gaji',
      'uang_jalan',
      'ritase_tonase',
      'harga',
      'validasi',
      'validasi_delete',
      'keterangan',
    ];

    protected static $recordEvents = ['deleted', 'updated'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly([
                    'kode_rute',
                    'customer_id',
                    'first_rute_id',
                    'last_rute_id',
                    'muatan_id',
                    'jenismobil_id',
                    'gaji',
                    'uang_jalan',
                    'ritase_tonase',
                    'harga',
                    'keterangan',
                    'validasi',
                    'validasi_delete',
                  ])
                ->setDescriptionForEvent(fn(string $eventName) => "Modul Penggajian {$eventName}")
                // ->dontLogIfAttributesChangedOnly([

                // ])
                ->useLogName('Penggajian');
    }


    public function customer()
    {
      return $this->belongsTo(Customer::class, 'customer_id');
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
    public function jenismobil()
    {
      return $this->belongsTo(Jenismobil::class, 'jenismobil_id');
    }
}
