<?php

namespace App\Traits;
use App\Models\Rute;
use App\Models\Joborder;
use App\Models\Penggajian;
use App\Models\Kasbon;
use App\Models\Invoice;
use Carbon\Carbon;

trait NoUrutTrait
{

  public function KodeRute($date)
  {

    $tgl = date('Y-m-d', strtotime($date));
    $noUrut = Rute::selectRaw("MAX(SUBSTRING(`kode_rute`, 4, 3)) AS max")
        ->whereDate('created_at', $tgl)
        ->first()->max ?? 0;
    // dd($noUrut);
    $noUrut ++ ;
    $noUrutNext ='RT-'.str_pad($noUrut, 3, "0", STR_PAD_LEFT).'-'.Carbon::createFromFormat('Y-m-d', $tgl)->format('ymd') ;
    return $noUrutNext;
  }

  public function KodeJoborder($date)
  {
    // dd($date);
    $tgl = date('Y-m-d', strtotime($date));
    $noUrut = Joborder::selectRaw("MAX(SUBSTRING(`kode_joborder`, 7, 9)) AS max")
         ->whereDate('tgl_joborder',  $tgl)
        ->first()->max ?? 0;
    $noUrut ++ ;
    $noUrutNext =Carbon::createFromFormat('Y-m-d', $tgl)->format('ymd').str_pad($noUrut, 3, "0", STR_PAD_LEFT);
    return $noUrutNext;
  }

  public function KodeKasbon($date)
  {
    $tgl = date('Y-m-d', strtotime($date));
    $month = date("m",strtotime($date));
    $noUrut = Kasbon::selectRaw("MAX(SUBSTRING(`kode_kasbon`, 6, 8)) AS max")
        ->whereMonth('tgl_kasbon', $month)
        ->first()->max ?? 0;
    // dd($noUrut);
    $noUrut ++ ;
    $noUrutNext = '3'.Carbon::createFromFormat('Y-m-d', $tgl)->format('ym').str_pad($noUrut, 3, "0", STR_PAD_LEFT);
    return $noUrutNext;
  }


  public function KodeInvoice($date)
  {
    $tgl = date('Y-m-d', strtotime($date));
    $month = date("m",strtotime($date));
    $noUrut = Invoice::selectRaw("MAX(SUBSTRING(`kode_invoice`, 6, 8)) AS max")
         ->whereMonth('tgl_invoice', $month)
        ->first()->max ?? 0;
    // dd($noUrut);
    $noUrut ++ ;
    $noUrutNext = '1'.Carbon::createFromFormat('Y-m-d', $tgl)->format('ym').str_pad($noUrut, 3, "0", STR_PAD_LEFT);
    return $noUrutNext;
  }


  public function KodeGaji($date)
  {
    $tgl = date('Y-m-d', strtotime($date));
    $month = date("m",strtotime($date));
    $noUrut = Penggajian::selectRaw("MAX(SUBSTRING(`kode_gaji`, 6, 8)) AS max")
        ->whereMonth('tgl_gaji', $month)
        ->first()->max ?? 0;
    // dd($noUrut);
    $noUrut ++ ;
    $noUrutNext = '2'.Carbon::createFromFormat('Y-m-d', $tgl)->format('ym').str_pad($noUrut, 3, "0", STR_PAD_LEFT);
    return $noUrutNext;
  }
}
