<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Joborder;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Traits\ResponseStatus;
use App\Traits\NoUrutTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Throwable;

class BulananJoborderController extends Controller
{


    function data(Request $request, $type){
        $array =  ($type == 'post') ?   $request['bulan'] :  explode(',',  $request['bulan']);
        // $array = explode(",", $get_bulan);
        $data = array();
        foreach($array as $key => $val){
            $bulan = Carbon::parse($val)->isoFormat('M');
            $tahun = Carbon::parse($request['tahun'])->isoFormat('Y');
            $data[$key]['bulan'] = Carbon::parse($val)->isoFormat('MMMM YYYY');
            $data[$key]['alldata'] = Joborder::whereMonth('tgl_joborder', $bulan)
                                    ->whereYear('tgl_joborder', $tahun);
        }
        return $data;

    }


    public function index(Request $request)
    {
        $config['page_title'] = "Laporan Bulanan Joborder";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Laporan Bulanan Joborder"],
        ];

        return view('backend.bulananjoborder.index', compact('config', 'page_breadcrumbs'));
    }

    public function getreport(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'bulan' => 'required',
            'tahun' => 'required',
          ]);
          $type = 'post';
          $getdata = $this->data($request, $type);

          $data = [
              'bulan' => $request['bulan'],
              'tahun' => $request['tahun'],
              'data' =>$getdata ,
          ];

        return view('backend.bulananjoborder.report', compact('data'));
        //   if ($validator->passes()) {

        //   } else {
        //     $response = response()->json(['error' => $validator->errors()->all()]);
        //   }

    }

    public function pdf(Request $request)
    {

        $type = 'pdf';
        $getdata = $this->data($request, $type);

        $html = '';


        $no = 1;
        for($i = 0; $i < count($getdata); $i++){
           $html .= '<table id="pakettable">';
              $html .= '<thead style="background-color: #fff !important; color:black;">
                      <tr>
                          <th width="10%" colspan="16" style="text-align: left !important">'.$getdata[$i]['bulan'].'</th>
                      </tr>
                  </thead>
                  <thead style="background-color: #fff !important; color:black;">
                      <tr>
                          <th>No</th>
                          <th class="text-center">Id JO</th>
                          <th>Tanggal</th>
                          <th>Status</th>
                          <th>Driver</th>
                          <th>No Plat Polisi</th>
                          <th>Jenis Mobil</th>
                          <th>Customer</th>
                          <th>Muatan</th>
                          <th>Alamat Awal (Dari)</th>
                          <th>Alamat Akhir (Ke)</th>
                          <th width="8%">Total Uj</th>
                          <th>Pembayaran</th>
                          <th width="8%">Sisa Uj</th>
                          <th>Keterangan</th>
                          <th>Operator (Waktu)</th>
                      </tr>
                  </thead>';
            $html .= '<tbody>';

            $alldata = $getdata[$i]['alldata']->get();
            $total_uj=$sisa_uj = 0;
            for($i = 0; $i < 100; $i++){
                $status_payment = $alldata[$i]['status_payment'] == '0' ? 'Belum Bayar' : ($alldata[$i]['status_payment'] == '1' ? 'Progress Payment' : 'Lunas');
                $status_jo = $alldata[$i]['status_joborder'] == '0' ? 'Ongoing' : 'Done';
                $html .= '
                <tr>
                    <td class="text-center">{{$no++}}</td>
                    <td><a href="'.route('backend.joborder.index').'?joborder_id='.$alldata[$i]->id.'" target="_blank">'.$alldata[$i]->kode_joborder.'</a></td>
                    <td>'. $alldata[$i]->tgl_joborder.'</td>
                    <td>'.$status_jo.'</td>
                    <td>'.$alldata[$i]->driver['name'].'</td>
                    <td>'.$alldata[$i]->mobil['nomor_plat'].'</td>
                    <td>'.$alldata[$i]->jenismobil['name'].'</td>
                    <td>'.$alldata[$i]->customer['name'].'</td>
                    <td>'.$alldata[$i]->muatan['name'].'</td>
                    <td>'.$alldata[$i]->ruteawal['name'].'</td>
                    <td>'.$alldata[$i]->ruteakhir['name'].'</td>
                    <td class="text-end">Rp. '. number_format($alldata[$i]->total_uang_jalan,0,',','.').'</td>
                    <td>'.$status_payment.'</td>
                    <td class="text-end">Rp. '. number_format($alldata[$i]->sisa_uang_jalan,0,',','.').'</td>
                    <td width="5%">-</td>
                    <td>-</td>
                </tr>';
            }
             $html .= '</tbody>';

           $html .= '</table>';
        }

        // @php($no=1)
        // @php($getdata = $data['data'])
        // @for ($item = 0; $item < count($getdata); $item++)
        // {{-- {{dd($getdata[$item]['bulan'])}} --}}
        // <table id="pakettable">
        //     <thead style="background-color: #fff !important; color:black;">
        //         <tr>
        //             <th width="10%" colspan="16" style="text-align: left !important">{{$getdata[$item]['bulan']}}</th>
        //         </tr>
        //     </thead>
        //     <thead style="background-color: #fff !important; color:black;">
        //         <tr>
        //             <th>No</th>
        //             <th class="text-center">Id JO</th>
        //             <th>Tanggal</th>
        //             <th>Status</th>
        //             <th>Driver</th>
        //             <th>No Plat Polisi</th>
        //             <th>Jenis Mobil</th>
        //             <th>Customer</th>
        //             <th>Muatan</th>
        //             <th>Alamat Awal (Dari)</th>
        //             <th>Alamat Akhir (Ke)</th>
        //             <th width="8%">Total Uj</th>
        //             <th>Pembayaran</th>
        //             <th width="8%">Sisa Uj</th>
        //             <th>Keterangan</th>
        //             <th>Operator (Waktu)</th>
        //         </tr>
        //     </thead>
        //     <tbody>
        //         @php($total_uj=$sisa_uj = 0)
        //         @php($alldata = $getdata[$item]['alldata']->get())
        //         @for ($i = 0; $i < count($alldata); $i++)
        //         @php($status_payment = $alldata[$i]['status_payment'] == '0' ? 'Belum Bayar' : ($alldata[$i]['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
        //         @php($status_jo = $alldata[$i]['status_joborder'] == '0' ? 'Ongoing' : 'Done')
        //         @php($total_uj += $alldata[$i]->total_uang_jalan)
        //         @php($sisa_uj += $alldata[$i]->sisa_uang_jalan)
        //             <tr>
        //                 <td width="2%" class="text-center">{{$no++}}</td>
        //                 <td>{{ $alldata[$i]['kode_joborder']}}</td>
        //                 <td>{{ $alldata[$i]['tgl_joborder']}}</td>
        //                 <td width="20px">{{$status_jo}}</td>
        //                 <td>{{ $alldata[$i]['driver']->name }}</td>
        //                 <td width="50px">{{ $alldata[$i]['mobil']['nomor_plat']}}</td>
        //                 <td>{{ $alldata[$i]['jenismobil']->name }}</td>
        //                 <td>{{ $alldata[$i]['customer']->name }}</td>
        //                 <td>{{ $alldata[$i]['muatan']->name }}</td>
        //                 <td>{{ $alldata[$i]['ruteawal']->name }}</td>
        //                 <td>{{ $alldata[$i]['ruteakhir']->name }}</td>
        //                 <td  width="60px" class="text-end">Rp. {{ number_format( $alldata[$i]['total_uang_jalan'],0,',','.')}}</td>
        //                 <td>{{$status_payment}}</td>
        //                 <td  class="text-end">Rp. {{ number_format( $alldata[$i]['sisa_uang_jalan'],0,',','.')}}</td>
        //                 <td>{{ $alldata[$i]['keterangan_joborder']}}</td>
        //                 <td>{{ $alldata[$i]['createdby']->name ?? '' }} ( {{  \Carbon\Carbon::parse( $alldata[$i]['created_at'])->format('d-m-Y H:i:s')  }} )</td>
        //             </tr>
        //         @endfor
        //     </tbody>
        //     <tfoot>
        //         <tr>
        //             <th colspan="11"style="text-align:right">Total: </th>
        //             <th class="text-end" id="">Rp. {{ number_format($total_uj,0,',','.')}}</th>
        //             <th></th>
        //             <th class="text-end" id="">Rp. {{ number_format($sisa_uj,0,',','.')}}</th>
        //             <th width="5%"></th>
        //             <th width="5%"></th>
        //          </tr>
        //     </tfoot>
        // </table><br>
        // @endfor

        $data = [
            'bulan' => $request['bulan'],
            'tahun' => $request['tahun'],
            'data' =>$getdata ,
            'datatable' =>$html ,
        ];


        $pdf =  PDF::loadView('backend.bulananjoborder.pdf',  compact('data'));
        $fileName = 'Laporan-Bulanan-Jo : ';
        $PAPER_F4 = array(0,0,609.4488,935.433);
        $pdf->setPaper( $PAPER_F4, 'landscape');
        $pdf->render();
        $font       = $pdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $pdf->get_canvas()->page_text(33, 590, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
        return $pdf->stream("${fileName}.pdf");
    }

    public function numrows($data, $sheet, $request, $spreadsheet){


         $getdata = $data['data'];
         $sum_hrf = 2;
         $sum_hr = 3;
         $sum_bs = 4;

         foreach($getdata as $key => $item){
            // $getdata[$key]['key'] =  $key;
            $getdata[$key]['header_row_first'] =   $sum_hrf;
            $getdata[$key]['header_row'] =   $sum_hr;

            $databody = $item['alldata']->get();
            $sum_body_end = count($databody)  + $sum_bs;
            $getdata[$key]['body_row_start'] = $sum_bs;
            $getdata[$key]['body_row_end'] = $sum_body_end + 2;
            $getdata[$key]['footer_row'] = $sum_hr +  count($databody) + 1;
         }

        //  dd($getdata);

         $loopdata = $getdata;

        //  dd(count($loopdata ) );

         for($key=0; $key < count($loopdata);$key++){

            foreach($getdata as $keyfirst => $itemfirst){
                if( $key == $keyfirst && $keyfirst != '0'){
                    $count =  $keyfirst > 1 ? $itemfirst['body_row_end'] - 1 : $itemfirst['body_row_end'];
                    $sum_hrf +=  $count;
                    $sum_hr  +=  $count;
                    $sum_bs  +=  $count;
                }
            }

            if($key != '0'){
                $loopdata[$key]['header_row_first'] =    $sum_hrf;
                $loopdata[$key]['header_row'] =   $sum_hr;
                $loopdata[$key]['body_row_start'] =   $sum_bs;
                $db_loop = $item['alldata']->get();
                $sum_body_end =  count($db_loop) + $loopdata[$key]['body_row_start'];
                $loopdata[$key]['body_row_end'] = $sum_body_end;
                $loopdata[$key]['footer_row'] =   $sum_hr +  count($db_loop) + 1;
            }

        }
        // dd($loopdata);
         $this->numrowsdata($loopdata, $sheet, $spreadsheet);


    }

    public function numrowsdata($data, $sheet, $spreadsheet){

        foreach($data as $key => $item){
            $sheet->setCellValue('A'. $item['header_row_first'], $item['bulan']);
            $sheet->setCellValue('A'. $item['header_row'], 'Id Jo');
            $sheet->setCellValue('B'. $item['header_row'], 'Tanggal');
            $sheet->setCellValue('C'. $item['header_row'], 'Status');
            $sheet->setCellValue('D'. $item['header_row'], 'Driver');
            $sheet->setCellValue('E'. $item['header_row'], 'Nomor Plat Polisi');
            $sheet->setCellValue('F'. $item['header_row'], 'Jenis mobil');
            $sheet->setCellValue('G'. $item['header_row'], 'Customer');
            $sheet->setCellValue('H'. $item['header_row'], 'Muatan');
            $sheet->setCellValue('I'. $item['header_row'], 'Alamat Awal');
            $sheet->setCellValue('J'. $item['header_row'], 'Alamat Akhir');
            $sheet->setCellValue('K'. $item['header_row'], 'Total Uj');
            $sheet->setCellValue('L'. $item['header_row'], 'Pembayaran');
            $sheet->setCellValue('M'. $item['header_row'], 'Sisa Uang Jalan');
            $sheet->setCellValue('N'. $item['header_row'], 'Keterangan');

        for($col = 'A'; $col !== 'N'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}
            $x = $item['body_row_start'];
            foreach($item['alldata']->get() as $val){
                    $status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas');
                    $status_jo = $val['status_joborder'] == '0' ? 'Ongoing' : 'Done';
                    $sheet->setCellValue('A' . $x, $val['kode_joborder']);
                    $sheet->setCellValue('B' . $x, $val['tgl_joborder']);
                    $sheet->setCellValue('C' . $x,  $status_jo);
                    $sheet->setCellValue('D' . $x, $val['driver']['name'] ?? '');
                    $sheet->setCellValue('E' . $x, $val['mobil']['nomor_plat'] ?? '');
                    $sheet->setCellValue('F' . $x, $val['jenismobil']['name'] ?? '');
                    $sheet->setCellValue('G' . $x, $val['customer']['name'] ?? '');
                    $sheet->setCellValue('H' . $x, $val['muatan']['name'] ?? '');
                    $sheet->setCellValue('I' . $x, $val['ruteawal']['name'] ?? '');
                    $sheet->setCellValue('J' . $x, $val['ruteakhir']['name'] ?? '');
                    $sheet->setCellValue('K' . $x, $val['total_uang_jalan'] ?? '');
                    $sheet->setCellValue('L' . $x, $status_payment);
                    $sheet->setCellValue('M' . $x,  $val['sisa_uang_jalan']);
                    $sheet->setCellValue('N' . $x,  $val['keterangan_joborder'] ?? '');
                    $x ++;
            }
            $cell   = $item['footer_row'];
            $bs = $item['body_row_start'];
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total :');
            $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':J' . $cell . '');
            $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $spreadsheet->getActiveSheet()->getStyle('K'. $bs.':K'.$cell)->getNumberFormat()->setFormatCode('#,##0');
            $spreadsheet->getActiveSheet()->getStyle('M'. $bs.':M'.$cell)->getNumberFormat()->setFormatCode('#,##0');

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$cell, '=SUM(K'.  $bs .':K' . $cell . ')');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$cell, '=SUM(M'.  $bs .':M' . $cell . ')');



        }


    }







    public function excel(Request $request)
    {


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $type = 'excel';
        $getdata = $this->data($request, $type);




        $data = [
            'bulan' => $request['bulan'],
            'tahun' => $request['tahun'],
            'data' =>$getdata ,
        ];


         $sheet->setCellValue('A1', 'Bulanan Joborder');
         $spreadsheet->getActiveSheet()->mergeCells('A1:N1');
         $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


         $this->numrows( $data , $sheet, $request, $spreadsheet);



      $writer = new Xlsx($spreadsheet);
      $filename = 'Bulanan Joborder';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');

    }

}
