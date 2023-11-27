<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Mobil extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = false;
    protected $table = 'mobil';
    protected $fillable = [
      'mobilrincian_id',
      'nomor_plat',
      'nomor_rangka',
      'nomor_mesin',
      'nomor_stnk',
      'nomor_ijin_usaha',
      'nomor_ijin_bongkar',
      'nomor_bpkb',
      'jenismobil_id',
      'maxload',
      'keterangan_mobil',
      'merkmobil_id',
      'tipemobil_id',
      'dump',
      'tahun',
      'berlaku_stnk',
      'berlaku_pajak',
      'kir',
      'berlaku_kir',
      'berlaku_ijin_usaha',
      'berlaku_ijin_bongkar',
      'image_mobil',
      'image_stnk',
      'validasi',
      'status_jalan'
    ];

    protected static $recordEvents = ['deleted', 'updated'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly([
                    'mobilrincian_id',
                    'nomor_plat',
                    'nomor_rangka',
                    'nomor_mesin',
                    'nomor_stnk',
                    'nomor_ijin_usaha',
                    'nomor_ijin_bongkar',
                    'nomor_bpkb',
                    'jenismobil_id',
                    'maxload',
                    'keterangan_mobil',
                    'merkmobil_id',
                    'tipemobil_id',
                    'dump',
                    'tahun',
                    'berlaku_stnk',
                    'berlaku_pajak',
                    'kir',
                    'berlaku_kir',
                    'berlaku_ijin_usaha',
                    'berlaku_ijin_bongkar',
                    'image_mobil',
                    'image_stnk'
                  ])
                ->setDescriptionForEvent(fn(string $eventName) => "Modul Mobil {$eventName}")
                ->dontLogIfAttributesChangedOnly([
                    'validasi',
                    'status_jalan'
                ])
                ->useLogName('Mobil');
    }

    public function jenismobil()
    {
      return $this->belongsTo(Jenismobil::class, 'jenismobil_id');
    }
    public function merkmobil()
    {
      return $this->belongsTo(Merkmobil::class, 'merkmobil_id');
    }
    public function tipemobil()
    {
      return $this->belongsTo(Tipemobil::class, 'tipemobil_id');
    }

    public function joborder()
    {
      return $this->hasMany(Joborder::class, 'mobil_id');
    }

}
