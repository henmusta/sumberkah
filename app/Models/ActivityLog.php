<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $table = 'activity_log';
    protected $fillable = [
      'log_name',
      'description',
      'subject_type',
      'event',
      'subject_id',
      'causer_type',
      'causer_id',
      'properties',
      'batch_uuid',
    ];

    // protected static function booted(): void
    // {
    //     static::created(function (ActivityLog $activitylog) {
    //         // $driver       = $joborder->driver()->first();
    //         // $mobil       = $joborder->mobil()->first();
    //         $activity    = ActivityLog::query();
    //         dd($activity);
    //         foreach( $activity as $val => $item){
    //             dd($item);
    //         }
    //         // $st_mobil    = Mobil::selectRaw('mobil.`id` AS id,
    //         //                                    SUM(IF(status_joborder = "1", 0, 1)) AS count_jo')
    //         //                                    ->Join('joborder', 'joborder.mobil_id', '=', 'mobil.id')
    //         //                                    ->where('mobil.id', $mobil->id)
    //         //                                    ->groupBy('mobil.id')->first();

    //         // $sj_driver    = Driver::where('id', $driver->id)->first();
    //         // $balance_driver =  $st_driver['count_jo'] > 0 ? 1 : 0;
    //         // $sj_driver->status_jalan  = $balance_driver;
    //         // $sj_driver->save();

    //         // $sj_mobil    = Mobil::where('id', $mobil->id)->first();
    //         // $balance_mobil =  $st_mobil['count_jo'] > 0 ? 1 : 0;
    //         // $sj_mobil->status_jalan  = $balance_mobil;
    //         // $sj_mobil->save();
    //     });


    // }

    public function createdby()
    {
      return $this->belongsTo(User::class, 'causer_id');
    }
}
