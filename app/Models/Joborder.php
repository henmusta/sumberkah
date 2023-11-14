<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Joborder extends Model
{
    use HasFactory, LogsActivity;
    public $timestamps = false;
    protected $table = 'joborder';
    protected $fillable = [
      'invoice_id',
      'kode_invoice',
      'penggajian_id',
      'kode_gaji',
      'kode_joborder',
      'kode_rute',
      'rute_id',
      'tgl_joborder',
      'driver_id',
      'jenismobil_id',
      'mobil_id',
      'customer_id',
      'muatan_id',
      'first_rute_id',
      'last_rute_id',
      'uang_jalan',
      'biaya_lain',
      'tambahan_potongan',
      'keterangan_joborder',
      'status_joborder',
      'total_uang_jalan',
      'total_kasbon',
      'total_payment',
      'sisa_uang_jalan',
      'status_payment',
      'kode_joborder',
      'created_by',
      'updated_by'
    ];

    protected static $recordEvents = ['deleted', 'updated'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly([
                    'kode_joborder',
                    'kode_rute',
                    'rute_id',
                    'tgl_joborder',
                    'driver_id',
                    'jenismobil_id',
                    'mobil_id',
                    'customer_id',
                    'muatan_id',
                    'first_rute_id',
                    'last_rute_id',
                    'uang_jalan',
                    'biaya_lain',
                    'tambahan_potongan',
                    'keterangan_joborder',
                    // 'status_joborder',
                    'total_uang_jalan',
                    'total_kasbon',
                    // 'total_payment',
                    // 'sisa_uang_jalan',
                  ])
                ->setDescriptionForEvent(fn(string $eventName) => "Modul Joborder {$eventName}")
                ->dontLogIfAttributesChangedOnly([
                    'invoice_id',
                    'kode_invoice',
                    'status_joborder',
                    'penggajian_id',
                    'kode_gaji',
                    'status_payment',
                    'total_kasbon',
                    'total_payment',
                    'sisa_uang_jalan',
                ])
                ->useLogName('Joborder');
    }


    protected static function booted(): void
    {
        static::created(function (Joborder $joborder) {
            $driver       = $joborder->driver()->first();
            $mobil       = $joborder->mobil()->first();
            $st_driver    = Driver::selectRaw('driver.`id` AS id,
                                               SUM(IF(status_joborder = "1", 0, 1)) AS count_jo')
                                               ->Join('joborder', 'joborder.driver_id', '=', 'driver.id')
                                               ->where('driver.id', $driver->id)
                                               ->groupBy('driver.id')->first();

            $st_mobil    = Mobil::selectRaw('mobil.`id` AS id,
                                               SUM(IF(status_joborder = "1", 0, 1)) AS count_jo')
                                               ->Join('joborder', 'joborder.mobil_id', '=', 'mobil.id')
                                               ->where('mobil.id', $mobil->id)
                                               ->groupBy('mobil.id')->first();

            $sj_driver    = Driver::where('id', $driver->id)->first();
            $balance_driver =  $st_driver['count_jo'] > 0 ? 1 : 0;
            $sj_driver->status_jalan  = $balance_driver;
            $sj_driver->save();

            $sj_mobil    = Mobil::where('id', $mobil->id)->first();
            $balance_mobil =  $st_mobil['count_jo'] > 0 ? 1 : 0;
            $sj_mobil->status_jalan  = $balance_mobil;
            $sj_mobil->save();
        });

        static::updated(function (Joborder $joborder) {
            $driver       = $joborder->driver()->first();
            $mobil       = $joborder->mobil()->first();
            $st_driver    = Driver::selectRaw('driver.`id` AS id,
                                               SUM(IF(status_joborder = "1", 0, 1)) AS count_jo')
                                               ->Join('joborder', 'joborder.driver_id', '=', 'driver.id')
                                               ->where('driver.id', $driver->id)
                                               ->groupBy('driver.id')->first();

            $st_mobil    = Mobil::selectRaw('mobil.`id` AS id,
                                               SUM(IF(status_joborder = "1", 0, 1)) AS count_jo')
                                               ->Join('joborder', 'joborder.mobil_id', '=', 'mobil.id')
                                               ->where('mobil.id', $mobil->id)
                                               ->groupBy('mobil.id')->first();

            $sj_driver    = Driver::where('id', $driver->id)->first();
            $balance_driver =  $st_driver['count_jo'] > 0 ? 1 : 0;
            // dd( $balance_driver);
            $sj_driver->status_jalan  = $balance_driver;
            $sj_driver->save();

            $sj_mobil    = Mobil::where('id', $mobil->id)->first();
            $balance_mobil =  $st_mobil['count_jo'] > 0 ? 1 : 0;
            $sj_mobil->status_jalan  = $balance_mobil;
            $sj_mobil->save();
        });

        static::deleted(function (Joborder $joborder) {
            $driver       = $joborder->driver()->first();
            $mobil       = $joborder->mobil()->first();
            $st_driver    = Driver::selectRaw('driver.`id` AS id,
                                               SUM(IF(status_joborder = "1", 0, 1)) AS count_jo')
                                               ->Join('joborder', 'joborder.driver_id', '=', 'driver.id')
                                               ->where('driver.id', $driver->id)
                                               ->groupBy('driver.id')->first();

            $st_mobil    = Mobil::selectRaw('mobil.`id` AS id,
                                               SUM(IF(status_joborder = "1", 0, 1)) AS count_jo')
                                               ->Join('joborder', 'joborder.mobil_id', '=', 'mobil.id')
                                               ->where('mobil.id', $mobil->id)
                                               ->groupBy('mobil.id')->first();

            $sj_driver    = Driver::where('id', $driver->id)->first();
            $balance_driver =  $st_driver['count_jo'] > 0 ? 1 : 0;
            $sj_driver->status_jalan  = $balance_driver;
            $sj_driver->save();

            $sj_mobil    = Mobil::where('id', $mobil->id)->first();
            $balance_mobil =  $st_mobil['count_jo'] > 0 ? 1 : 0;
            $sj_mobil->status_jalan  = $balance_mobil;
            $sj_mobil->save();
        });

    }


    public function customer()
    {
      return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function driver()
    {
      return $this->belongsTo(Driver::class, 'driver_id');
    }
    public function rute()
    {
      return $this->belongsTo(Rute::class, 'rute_id');
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
    public function mobil()
    {
      return $this->belongsTo(Mobil::class, 'mobil_id');
    }

    public function jenismobil()
    {
      return $this->belongsTo(Jenismobil::class, 'jenismobil_id');
    }

    public function payment()
    {
      return $this->hasMany(PaymentJo::class, 'joborder_id')->with('kasbon');
    }
    public function konfirmasijo()
    {
      return $this->hasMany(KonfirmasiJo::class, 'joborder_id')->with('createdby');
    }

    public function gaji()
    {
        return $this->belongsTo(Penggajian::class, 'penggajian_id')->with('createdby', 'payment');
    }


    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id')->with('createdby');
    }

    public function kasbon()
    {
      return $this->hasMany(Kasbon::class, 'joborder_id');
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
