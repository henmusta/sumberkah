<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseStatus;
use Illuminate\Routing\Controller;
use PDF;


class DatePickerController extends Controller
{
    public function index(Request $request)
    {

        if($request->type == 'bulan'){
            $set = 1;
            $p = '1 month';
        }else if($request->type == 'tahun'){
            $set = 20;
            $p = '1 year';
        }else{
            $set = 2;
            $p = '1 month';
        };


        $startPeriod = Carbon::now()->startOfYear();
        $endPeriod   = Carbon::now()->endOfYear();

        $period = CarbonPeriod::create($startPeriod,  '1 month', $endPeriod);

        foreach ($period as $item) {
            $id = $item->format('Y-m-d');
            $type =  $request->type;
            if( $type == 'bulan'){
                $text =  $item->isoFormat('MMMM');
            }else if($type == 'tahun' ){

                $text =  $item->format('Y');
            }else{
                $text =  $item->isoFormat('MMMM YYYY');
            }


            if (isset($request->q) ) {
                if(stripos($text, $request->q) !== false){
                    $get_date[] = [
                        'id' => $id,
                        'text' => $text,
                    ];
                }
            }else{
                $get_date[] = [
                    'id' => $id,
                    'text' => $text,
                ];
            }

        }



        $datepicker = $get_date;
        $page = $request->page;
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $data = $datepicker;

       $count = count($data);


      $endCount = $offset + $resultCount;
      $morePages = $count > $endCount;

      $results = array(
        "results" =>  array_reverse($data),
        "pagination" => array(
          "more" => $morePages
        )
      );

      return response()->json($results);

    }
}
