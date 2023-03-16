<?php

namespace App\Traits;

use Carbon\Carbon;

trait CarbonFormat
{
  public function convertDateDatabase($date)
  {
    if(is_null($date)){
      return '';
    }
    return Carbon::createFromFormat('d M Y', $date)->format('Y-m-d');
  }

  public function convertDateForm($date)
  {
    if(is_null($date)) {
      return '';
    }
    return Carbon::createFromFormat('Y-m-d', $date)->format('d M Y');
  }

  public function convertDateNoRef($date)
  {
    if(is_null($date)) {
      return '';
    }
    return Carbon::createFromFormat('d M Y', $date)->format('ym');
  }

  public function convertDateNoRefExcel($date)
  {
    if(is_null($date)) {
      return '';
    }
    return Carbon::createFromFormat('Y-m-d', $date)->format('ym');
  }

}
