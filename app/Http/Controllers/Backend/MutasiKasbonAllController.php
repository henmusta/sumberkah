<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Kasbonjurnallog;
use App\Models\Driver;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use App\Traits\NoUrutTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yajra\DataTables\Facades\DataTables;
use Throwable;
use PDF;


class MutasiKasbonAllController extends Controller
{
    function __construct()
    {
      $this->middleware('can:backend-mutasikasbonall-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-mutasikasbonall-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-mutasikasbonall-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-mutasikasbonall-delete', ['only' => ['destroy']]);
    }

    public function data(Request $request, $type){

        // dd($request['jenis']);
       // $jenis = $request['jenis'];
            $jenis = ($type == 'post' || $type == 'saldo') ? $request['jenis'] :  explode(',',  $request['jenis']);

            $tgl_awal = date('Y-m-d', strtotime($request['tgl_awal']));
            $tgl_akhir =  date('Y-m-d', strtotime($request['tgl_akhir']));
            $total_debit_awal = $total_kredit_awal =   $total_debit = $total_kredit = $saldo_awal = $saldo_akhir = 0;
            $data = array();
            $get_saldo_awal =  Kasbonjurnallog::
            when($jenis, function ($query, $jenis) {
                return $query->whereIn('jenis',  $jenis);
            })
            ->whereDate('tgl_kasbon', '<',  $tgl_awal)->get();

            foreach($get_saldo_awal as $key => $i){
                $total_debit_awal += $i['debit'];
                $total_kredit_awal += $i['kredit'];
            }
            $saldo_awal = $total_kredit_awal - $total_debit_awal;

            // dd($type);

            $get_data = Kasbonjurnallog::when($jenis, function ($query, $jenis) {
                return $query->whereIn('jenis',  $jenis);
            })->selectRaw('kasbon_jurnallog.*')->with('driver','joborder', 'gaji')
            ->when($tgl_awal, function ($query, $tgl_awal) {
                return $query->whereDate('tgl_kasbon', '>=',  $tgl_awal);
            })
            ->when($tgl_akhir, function ($query, $tgl_akhir) {
                return $query->whereDate('tgl_kasbon', '<=', $tgl_akhir);
            })
            ->groupBy('kasbon_jurnallog.kode_kasbon')->get();
            // dd(count($get_data));
            if(count($get_data) > 0){
                foreach($get_data as $key => $val){
                    $total_debit += $val['debit'];
                    $total_kredit += $val['kredit'];
                    $data[] = $val;
                    $get_data[$key]['saldo'] =  $val['kredit'] - $val['debit'];
                    $new_saldo = $saldo_awal;
                    if ($key == 0) {
                        $new_saldo = $new_saldo + (float)$get_data[0]->saldo;
                        $get_data[0]->new_saldo = $new_saldo;
                    }
                    else{
                        $new_saldo = (float)$get_data[$key-1]->new_saldo + (float)$get_data[$key]->saldo;
                        $get_data[$key]->new_saldo = $new_saldo;
                    }

             }
                if($type != 'post'){
                    $data[0]['saldo_akhir'] =  end($data)->new_saldo;
                }
            }else{
                if($type != 'post'){
                    $data[0]['saldo_akhir'] = $saldo_awal;
                }
            }

            if($type != 'post'){
                $alldata = $data;
                $data = [
                    'data' => $alldata,
                    'saldo_awal' => $saldo_awal,
                    'total_debit' => $total_debit,
                    'total_kredit' =>  $total_kredit,
                    'saldo_akhir' => $data[0]['saldo_akhir']
                ];
            }

            return  $data;
    }


    public function index(Request $request)
    {
        $config['page_title'] = "Mutasi Kasbon Keseluruhan";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Mutasi Kasbon Keseluruhan"],
        ];

        if ($request->ajax()) {
          $type = 'post';
          $data = $this->data($request, $type);
          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $show = '<a href="' . route('backend.mutasikasbon.show', $row->id) . '" class="dropdown-item">Detail</a>';
                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '.  $show.'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.mutasikasbonall.index', compact('config', 'page_breadcrumbs'));
    }






    public function ceksaldo(Request $request)
    {
        $type = 'saldo';
        $data = $this->data($request, $type);
        return response()->json($data);
    }


    public function excel(Request $request)
    {


            $type = 'excel';
            $data = $this->data($request, $type);


            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // $sheet->setCellValue('A1', 'Mutasi Kasbon');
            $spreadsheet->getActiveSheet()->mergeCells('A2:J2');
            $sheet->setCellValue('A2', 'Mutasi Kasbon Keseluruhan');
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $rows4 = 4;
            $spreadsheet->getActiveSheet()->mergeCells('A4:F4');
            $sheet->setCellValue('A'.$rows4, 'Saldo Awal');
            $spreadsheet->getActiveSheet()->getStyle('A'.$rows4)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->mergeCells('G4:I4');
            $sheet->setCellValue('G'.$rows4, $data['saldo_awal']);
            $spreadsheet->getActiveSheet()->getStyle('G'.$rows4)->getNumberFormat()->setFormatCode('#,##0');

            $rows5 = 5;
            $sheet->setCellValue('A'.$rows5, 'Nama Driver');
            $sheet->setCellValue('B'.$rows5, 'Tanggal Transaksi');
            $sheet->setCellValue('C'.$rows5, 'Kode Kasbon');
            $sheet->setCellValue('D'.$rows5, 'Kode Gaji');
            $sheet->setCellValue('E'.$rows5, 'Kode Joborder');
            $sheet->setCellValue('F'.$rows5, 'Keterangan');
            $sheet->setCellValue('G'.$rows5, 'Debit');
            $sheet->setCellValue('H'.$rows5, 'Kredit');
            $sheet->setCellValue('I'.$rows5, 'Saldo Kasbon');
            $sheet->setCellValue('J'.$rows5, 'Operator (Waktu)');

            for($col = 'A'; $col !== 'K'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}

            // $list = $pajak->get();
            // // $first = $pajak->first();

            $x = 6;
            foreach($data['data'] as $val){
                    $user = isset($val['kasbon']['createdby']->name) ? $val['kasbon']['createdby']->name : '-' ;
                    $sheet->setCellValue('A' . $x, $val['driver']['name']);
                    $sheet->setCellValue('B' . $x, $val['tgl_kasbon']);
                    $sheet->setCellValue('C' . $x, $val['kode_kasbon']);
                    $sheet->setCellValue('D' . $x, $val['gaji']['kode_gaji'] ?? '');
                    $sheet->setCellValue('E' . $x, $val['joborder']['kode_joborder'] ?? '');
                    $sheet->setCellValue('F' . $x, $val['keterangan']);
                    $sheet->setCellValue('G' . $x, $val['debit']);
                    $sheet->setCellValue('H' . $x, $val['kredit']);
                    $sheet->setCellValue('I' . $x, $val['new_saldo']);
                    $sheet->setCellValue('J' . $x,  $user  . ' ( ' .date('d-m-Y H:i:s', strtotime( $val['kasbon']['created_at'])) .' )');
                    $x++;
            }

            $cell   = count($data['data']) + 6;
            $spreadsheet->getActiveSheet()->getStyle('G6:G'.$cell + 3)->getNumberFormat()->setFormatCode('#,##0');
            $spreadsheet->getActiveSheet()->getStyle('H6:H'.$cell)->getNumberFormat()->setFormatCode('#,##0');
            $spreadsheet->getActiveSheet()->getStyle('I6:I'.$cell)->getNumberFormat()->setFormatCode('#,##0');

            $cell_tk = $cell + 1;
            $cell_sba = $cell + 2;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total Debit');
            $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':F' . $cell . '');
            $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$cell, $data['total_debit']);
            $spreadsheet->getActiveSheet()->mergeCells( 'G' . $cell . ':I' . $cell . '');



            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell_tk, 'Total Kredit');
            $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell_tk . ':F' . $cell_tk . '');
            $spreadsheet->getActiveSheet()->getStyle('A'.$cell_tk)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$cell_tk, $data['total_kredit']);
            $spreadsheet->getActiveSheet()->mergeCells( 'G' . $cell_tk . ':I' . $cell_tk . '');


            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell_sba, 'Saldo Bon Akhir');
            $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell_sba . ':F' . $cell_sba . '');
            $spreadsheet->getActiveSheet()->getStyle('A'.$cell_sba)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$cell_sba, $data['saldo_akhir']);
            $spreadsheet->getActiveSheet()->mergeCells( 'G' . $cell_sba . ':I' . $cell_sba . '');

      $writer = new Xlsx($spreadsheet);
      $filename = 'Mutasi Kasbon';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');

    }

    public function pdf(Request $request)
    {

        $type = 'pdf';
        $data = $this->data($request, $type);



                $data = [
                    'mutasikasbonall' => $data['data'],
                    'saldo_awal' => $data['saldo_awal'],
                    'saldo_akhir' =>  $data['saldo_akhir'],
                    'total_debit' =>  $data['total_debit'],
                    'total_kredit' =>$data['total_kredit'],
                    'tgl_awal' => $request['tgl_awal'],
                    'tgl_akhir' => $request['tgl_akhir'],
                ];

        $pdf =  PDF::loadView('backend.mutasikasbonall.report',  compact('data'));
        $PAPER_F4 = array(0,0,609.4488,935.433);
        $pdf->setPaper( $PAPER_F4, 'landscape');
        $pdf->render();
        $font  = $pdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $pdf->get_canvas()->page_text(33, 590, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
        $fileName = 'Laporan-MutasiKasbon : '. $request['tgl_awal'] . '-SD-' .$request['tgl_akhir'];
        return $pdf->stream("${fileName}.pdf");
    }
}
