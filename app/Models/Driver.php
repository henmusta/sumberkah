<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Driver extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = false;
    protected $table = 'driver';
    protected $fillable = [
      'name',
      'validasi',
      'kasbon',
      'alamat',
      'telp',
      'keterangan_driver',
      'ktp',
      'sim',
      'panggilan',
      'tempat_lahir',
      'tgl_lahir',
      'image_foto',
      'image_sim',
      'image_ktp',
      'tgl_sim',
      'status_aktif',
      'tgl_aktif',
      'tgl_nonaktif',
      'status_jalan',
      'status_hapus',
      'darurat_name',
      'darurat_telp',
      'darurat_ref'
    ];

    protected static $recordEvents = ['deleted', 'updated'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly([
                    'name',
                    'kasbon',
                    'alamat',
                    'telp',
                    'keterangan_driver',
                    'ktp',
                    'sim',
                    'panggilan',
                    'tempat_lahir',
                    'tgl_lahir',
                    'image_foto',
                    'image_sim',
                    'image_ktp',
                    'tgl_sim',
                    'status_aktif',
                    'tgl_aktif',
                    'tgl_nonaktif',
                    'validasi',
                    'darurat_name',
                    'darurat_telp',
                    'darurat_ref'
                  ])
                ->setDescriptionForEvent(fn(string $eventName) => "Modul Driver {$eventName}")
                ->dontLogIfAttributesChangedOnly(['status_jalan',  'kasbon', 'validasi'])
                ->useLogName('Driver');
    }



    public function getkasbon()
    {
      return $this->hasMany(Kasbon::class);
    }

}
