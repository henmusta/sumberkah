<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Kasbon extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'kasbon';
    protected $keyType = 'int';
    protected $fillable = [
      'driver_id',
      'kode_kasbon',
      'jenis',
      'tgl_kasbon',
      'keterangan',
      'nominal',
      'joborder_id',
      'penggajian_id',
      'validasi',
      'created_by',
      'updated_by'
    ];

    protected static $recordEvents = ['deleted', 'updated'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly([
                    'driver_id',
                    'kode_kasbon',
                    'jenis',
                    'tgl_kasbon',
                    'keterangan',
                    'nominal',
                    'joborder_id',
                    'penggajian_id',
                    'validasi',
                  ])
                ->setDescriptionForEvent(fn(string $eventName) => "Modul Kasbon {$eventName}")
                // ->dontLogIfAttributesChangedOnly([

                // ])
                ->useLogName('Kasbon');
    }

    public function driver()
    {
      return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function penggajian()
    {
      return $this->belongsTo(Penggajian::class, 'penggajian_id');
    }

    public function joborder()
    {
      return $this->belongsTo(Joborder::class, 'joborder_id');
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
