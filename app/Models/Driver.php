<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
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

    // protected static function booted(): void
    // {
    //     static::created(function (Driver $driver) {
    //         $driver       = $driver->item()->first();
    //         $st_driver    = Driver::selectRaw('driver.`id` AS id,
    //                                            SUM(IF(status_joborder = "1", 0, 1)) AS count_jo')
    //                                            ->Join('joborder', 'joborder.driver_id', '=', 'driver.id')
    //                                            ->where('driver.id', $driver->id)
    //                                            ->groupBy('driver.id');
    //         $status_jalan    = Driver::where('id', $driver->id)->first();
    //         $balance =  $st_driver['count_jo'] > 0 ? 1 : 0;
    //         $status_jalan->status_jalan  = $balance;
    //         $status_jalan->save();
    //     });
    // }


    public function getkasbon()
    {
      return $this->hasMany(Kasbon::class);
    }

}
