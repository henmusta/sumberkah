<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Joborder;
use App\Models\Driver;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Traits\ResponseStatus;
use App\Traits\NoUrutTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Throwable;


class BulananDriverJoController extends Controller
{
    function data(Request $request, $type){
        $bulan = ($type == 'post') ?   $request['bulan'] :  explode(',',  $request['bulan']);
        $cek_driver =  ($type == 'post') ?   $request['driver_id'] : (isset( $request['driver_id'][0]) ? explode(',',  $request['driver_id']) : null);

        if(isset($cek_driver)){
            $driver = $cek_driver;
        }else{
            $driver = Driver::selectRaw('id')->get();
        }

        // dd($driver);
        foreach($bulan as $i => $item){
           $cek_bl[] =  Carbon::parse($item)->isoFormat('MMMM');
           $cek_bl_id[] =  Carbon::parse($item)->format('m');
        }
        $cek_bulan = implode(' - ', $cek_bl);
        $cek_bulan_id = implode(',',$cek_bl_id);
        $data = array();
        $i = 0;
        foreach($driver as $key => $val){
            $id = isset($val['id']) ? $val['id'] : $val;
            $tahun = Carbon::parse($request['tahun'])->isoFormat('Y');
            $cek_driver =  Joborder::whereRaw('MONTH(tgl_joborder) IN ('. $cek_bulan_id.')')
            ->whereYear('tgl_joborder', $tahun)->where('driver_id', $id);
            if(count($cek_driver->get()) > 0){
                $count_key = $i++;
                $get_driver = Driver::findOrFail($id);
                $data[$count_key]['driver'] = $get_driver['name'].','.$cek_bulan;
                $data[$count_key]['alldata'] =  $cek_driver;
            }

        }
        return $data;
    }

    public function index(Request $request)
    {
        $config['page_title'] = "Laporan Joborder Driver";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Laporan Joborder Driver"],
        ];
        return view('backend.bulanandriverjo.index', compact('config', 'page_breadcrumbs'));
    }

    public function pdf(Request $request)
    {

        $type = 'pdf';
        $getdata = $this->data($request, $type);

        $data = [
            'bulan' => $request['bulan'],
            'tahun' => $request['tahun'],
            'data'  => $getdata
        ];

        // dd($data);
        $pdf =  PDF::loadView('backend.bulanandriverjo.pdf',  compact('data'));
        $fileName = 'Laporan-Nopol-Joborder : ';
        $PAPER_F4 = array(0,0,609.4488,935.433);
        $pdf->setPaper( $PAPER_F4, 'landscape');
        $pdf->render();
        $font       = $pdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $pdf->get_canvas()->page_text(33, 590, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
        return $pdf->stream("${fileName}.pdf");
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
          return view('backend.bulanandriverjo.report', compact('data'));

    }

    public function numrows($data, $sheet, $request, $spreadsheet){
        $getdata = $data['data'];
        $sum_hrf = array(2);
        foreach($getdata as $key => $item){
           foreach($sum_hrf as $obj => $val){
             if($key == $obj){
                $count_sum = ($key > 0) ? $val + 3 : $val;
                $getdata[$key]['header_row_first'] =  $count_sum ;
                $getdata[$key]['header_row'] =   $count_sum  + 1;
                $databody = $item['alldata']->get();
                $getdata[$key]['count_row'] =  count($databody);
                $sum_body_end = count($databody) +  $count_sum  + 1;
                $getdata[$key]['body_row_start'] = $count_sum  + 2;
                $getdata[$key]['body_row_end'] = $sum_body_end;
                $getdata[$key]['footer_row'] =  $getdata[$key]['body_row_end'] + 1;
             }
           }
           array_push($sum_hrf,$getdata[$key]['footer_row']);
        }
        $loopdata = $getdata;
        for($key=1; $key < count($loopdata);$key++){
            foreach($sum_hrf as $i => $num){
                if($i == $key){
                    $db_loop = $item['alldata']->get();


                    $loopdata[$key]['header_row_first'] = $num + 1;
                    $loopdata[$key]['header_row'] =  $num + 2;
                    $loopdata[$key]['body_row_start'] =  $num + 3;
                    $loopdata[$key]['count_row'] =  count($db_loop);
                    $sum_body_end =  count($db_loop) + $loopdata[$key]['body_row_start'];
                    $loopdata[$key]['body_row_end'] = $sum_body_end;
                    $loopdata[$key]['footer_row'] =   $sum_body_end + 1;
                }
            }
        }
        $this->numrowsdata($getdata, $sheet, $spreadsheet);
    }


   public function numrowsdata($data, $sheet, $spreadsheet){

    foreach($data as $key => $item){
        $sheet->setCellValue('A'. $item['header_row_first'], $item['driver']);
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
           'data'  => $getdata,
       ];


        $sheet->setCellValue('A1', 'Bulanan Driver Joborder');
        $spreadsheet->getActiveSheet()->mergeCells('A1:N1');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        $this->numrows( $data , $sheet, $request, $spreadsheet);



     $writer = new Xlsx($spreadsheet);
     $filename = 'Bulanan Driver Joborder';
     header('Content-Type: application/vnd.ms-excel');
     header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
     header('Cache-Control: max-age=0');
     $writer->save('php://output');

   }
}
