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

    $tgl = date('Y-m-d', strtotime($date));
    $noUrut = Joborder::selectRaw("MAX(SUBSTRING(`kode_joborder`, 1, 3)) AS max")
        ->first()->max ?? 0;
    $noUrut ++ ;
    $noUrutNext =str_pad($noUrut, 3, "0", STR_PAD_LEFT).'-'.Carbon::createFromFormat('Y-m-d', $tgl)->format('ymd');
    return $noUrutNext;
  }

  public function KodeKasbon($date)
  {
    $tgl = date('Y-m-d', strtotime($date));
    $noUrut = Kasbon::selectRaw("MAX(SUBSTRING(`kode_kasbon`, 1, 3)) AS max")
        ->whereDate('created_at', $tgl)
        ->first()->max ?? 0;
    // dd($noUrut);
    $noUrut ++ ;
    $noUrutNext =str_pad($noUrut, 3, "0", STR_PAD_LEFT).'-BON-'.Carbon::createFromFormat('Y-m-d', $tgl)->format('m').'-'.Carbon::createFromFormat('Y-m-d', $tgl)->format('Y') ;
    return $noUrutNext;
  }


  public function KodeInvoice($date)
  {
    $tgl = date('Y-m-d', strtotime($date));
    $noUrut = Invoice::selectRaw("MAX(SUBSTRING(`kode_invoice`, 1, 3)) AS max")
        ->whereDate('created_at', $tgl)
        ->first()->max ?? 0;
    // dd($noUrut);
    $noUrut ++ ;
    $noUrutNext =str_pad($noUrut, 3, "0", STR_PAD_LEFT).'-SKB-'.Carbon::createFromFormat('Y-m-d', $tgl)->format('m').'-'.Carbon::createFromFormat('Y-m-d', $tgl)->format('Y') ;
    return $noUrutNext;
  }


  public function KodeGaji($date)
  {
    $tgl = date('Y-m-d', strtotime($date));
    $noUrut = Penggajian::selectRaw("MAX(SUBSTRING(`kode_gaji`, 1, 3)) AS max")
        ->whereDate('created_at', $tgl)
        ->first()->max ?? 0;
    // dd($noUrut);
    $noUrut ++ ;
    $noUrutNext =str_pad($noUrut, 3, "0", STR_PAD_LEFT).'-GAJI-'.Carbon::createFromFormat('Y-m-d', $tgl)->format('m').'-'.Carbon::createFromFormat('Y-m-d', $tgl)->format('Y') ;
    return $noUrutNext;
  }
}
