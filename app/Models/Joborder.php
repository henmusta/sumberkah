<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\DB;
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

    function updateall(){
        $feild = ['driver', 'mobil'];
        foreach($feild as $val){
        $query = DB::statement("UPDATE
            ".$val."
            INNER JOIN (SELECT
            ".$val.".`id` AS id,
            IF(SUM(IF(status_joborder = '1', 0, 1)) > '0', '1', '0') AS cek_st
            FROM
            ".$val."
            INNER JOIN joborder
            ON joborder.`".$val."_id` =  ".$val.".`id`
            GROUP BY  ".$val.".`id`) child
            ON  ".$val.".id = child.id
            SET  ".$val.".`status_jalan` = child.cek_st");
        }

    }

    protected static function booted(): void
    {
        static::created(function (Joborder $joborder) {
            $joborder->updateall();
        });

        static::updated(function (Joborder $joborder) {
            $joborder->updateall();
        });

        static::deleted(function (Joborder $joborder) {
            $joborder->updateall();
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
