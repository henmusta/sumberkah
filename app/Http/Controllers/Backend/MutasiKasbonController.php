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
use PDF;
use Throwable;


class MutasiKasbonController extends Controller
{
    use ResponseStatus,NoUrutTrait;

    public function data(Request $request, $type){

        if($type == 'post_driver' || $type == 'pdf_driver' || $type == 'excel_driver'){
            $status =$request['status'];
            $tgl_awal = date('Y-m-d', strtotime($request['tgl_awal']));
            $tgl_akhir =  date('Y-m-d', strtotime($request['tgl_akhir']));
            // dd($status);
            $data = Driver::query();

            if ($request->filled('status')) {
                $data->where('status_aktif', $request['status']);
            }

            if ($request->filled('tgl_awal')) {
                    $data->whereDate('tgl_aktif', '>=',  $tgl_awal);
            }
            if ($request->filled('tgl_akhir')) {
                $data->whereDate('tgl_aktif', '<=',$tgl_akhir);
            }
        }else{
            $driver_id =$request['driver_id'];
            $tgl_awal = date('Y-m-d', strtotime($request['tgl_awal']));
            $tgl_akhir =  date('Y-m-d', strtotime($request['tgl_akhir']));

            $total_debit_awal = $total_kredit_awal =   $total_debit = $total_kredit = $saldo_awal = $saldo_akhir = 0;
            $data = array();
            $get_saldo_awal =  Kasbonjurnallog::whereDate('tgl_kasbon', '<',  $tgl_awal)->where('driver_id', $driver_id)->get();
            foreach($get_saldo_awal as $key => $i){
                $total_debit_awal += $i['debit'];
                $total_kredit_awal += $i['kredit'];
            }
            $saldo_awal = $total_kredit_awal - $total_debit_awal;

                $get_data = Kasbonjurnallog::selectRaw('kasbon_jurnallog.*')->with('driver','joborder', 'gaji')
                ->when($tgl_awal, function ($query, $tgl_awal) {
                    return $query->whereDate('tgl_kasbon', '>=',  $tgl_awal);
                })
                ->when($tgl_akhir, function ($query, $tgl_akhir) {
                    return $query->whereDate('tgl_kasbon', '<=', $tgl_akhir);
                })
                ->when($driver_id, function ($query, $driver_id) {
                    return $query->where('driver_id', $driver_id);
                })->orderBy('kode_kasbon', 'ASC')->get();

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

           }




            return $data;
    }

    function __construct()
    {
      $this->middleware('can:backend-mutasikasbon-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-mutasikasbon-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-mutasikasbon-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-mutasikasbon-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Driver";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Driver"],
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
            // $data = [
            //     'saldo_awal' => 0,
            //     'total_debit' => 0,
            //     'total_kredit' =>  0,
            //     'saldo_akhir' => 0
            // ];

        //   return response()->json($data);
        }

        return view('backend.mutasikasbon.index', compact('config', 'page_breadcrumbs'));
    }

    public function show($id)
    {
        $config['page_title'] = "Mutasi Kasbon Supir";

        $page_breadcrumbs = [
          ['url' => route('backend.mutasikasbon.index'), 'title' => "Daftar Supir"],
          ['url' => '#', 'title' => "Mutasi Kasbon Supir"],
        ];
        $mutasi = Kasbonjurnallog::with('driver')->where('driver_id', $id);
        $driver = Driver::find($id);
        $data = [
          'mutasi' => $mutasi->first(),
          'driver' => $driver,
          'mutasi_kasbon' => $mutasi->get(),
        ];
        // dd( $id);

        return view('backend.mutasikasbon.show', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function datatablecekdriver(Request $request)
    {
      if ($request->ajax()) {
            $type = 'post_driver';
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

            })->make(true);
            }
    }


    public function ceksaldo(Request $request)
    {
        $type = 'saldo';
        $data = $this->data($request, $type);
        return response()->json($data);
    }

    public function excel_driver($data, $spreadsheet, $sheet, $request){

        $sheet->setCellValue('A1', 'Kasbon Driver');
        $spreadsheet->getActiveSheet()->mergeCells('A1:E1');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        if($request['tgl_awal'] != null && $request['tgl_akhir'] != null){
            $spreadsheet->getActiveSheet()->mergeCells('A2:E2');
            $sheet->setCellValue('A2', 'Tanggal : '. date('d-m-Y', strtotime($request['tgl_awal'])) .' S/D '. date('d-m-Y', strtotime($request['tgl_akhir'])));
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        $rows5 = 3;
        $sheet->setCellValue('A'.$rows5, 'No');
        $sheet->setCellValue('B'.$rows5, 'Tanggal');
        $sheet->setCellValue('C'.$rows5, 'Nama Supir');
        $sheet->setCellValue('D'.$rows5, 'Kasbon');
        $sheet->setCellValue('E'.$rows5, 'Status Driver');


        for($col = 'A'; $col !== 'F'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}

        $x = 4;
        $no = 0;
        foreach($data->get() as $val){
                $status = $val['status_aktif'] == '1' ? 'Aktif' : 'Tidak Aktif';
                $sheet->setCellValue('A' . $x, $no++);
                $sheet->setCellValue('B' . $x, $val['tgl_aktif']);
                $sheet->setCellValue('C' . $x, $val['name'] ?? '');
                $sheet->setCellValue('D' . $x, $val['kasbon'] ?? '');
                $sheet->setCellValue('E' . $x,  $status);
                $x++;
        }

        $cell   = count($data->get()) + 4;
        $spreadsheet->getActiveSheet()->getStyle('D4:D'.$cell)->getNumberFormat()->setFormatCode('#,##0');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total : ');
        $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':C' . $cell . '');
        $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$cell, '=SUM(D3:D' . $cell . ')');

    }

    public function excel_mutasi($data, $spreadsheet, $sheet, $request){
        $driver_id =$request['driver_id'];
        $driver = Driver::find($driver_id);
        $sheet->setCellValue('A1', 'Mutasi Kasbon');
        $spreadsheet->getActiveSheet()->mergeCells('A1:I1');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'Nama Driver : '. $driver['name']);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);



        if($request['tgl_awal'] != null && $request['tgl_akhir'] != null){
            $spreadsheet->getActiveSheet()->mergeCells('A3:I3');
            $sheet->setCellValue('A3', 'Tanggal : '. date('d-m-Y', strtotime($request['tgl_awal'])) .' S/D '. date('d-m-Y', strtotime($request['tgl_akhir'])));
            $spreadsheet->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }



        $rows4 = 4;
        $spreadsheet->getActiveSheet()->mergeCells('A4:E4');
        $sheet->setCellValue('A'.$rows4, 'Saldo Awal');
        $spreadsheet->getActiveSheet()->getStyle('A'.$rows4)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $spreadsheet->getActiveSheet()->mergeCells('F4:H4');
        $sheet->setCellValue('F'.$rows4, $data['saldo_awal']);
        $spreadsheet->getActiveSheet()->getStyle('F'.$rows4)->getNumberFormat()->setFormatCode('#,##0');

        $rows5 = 5;
        $sheet->setCellValue('A'.$rows5, 'Tanggal Transaksi');
        $sheet->setCellValue('B'.$rows5, 'Kode Kasbon');
        $sheet->setCellValue('C'.$rows5, 'Kode Gaji');
        $sheet->setCellValue('D'.$rows5, 'Kode Joborder');
        $sheet->setCellValue('E'.$rows5, 'Keterangan');
        $sheet->setCellValue('F'.$rows5, 'Debit');
        $sheet->setCellValue('G'.$rows5, 'Kredit');
        $sheet->setCellValue('H'.$rows5, 'Saldo Kasbon');
        $sheet->setCellValue('I'.$rows5, 'Operator (Waktu)');

        for($col = 'A'; $col !== 'J'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}

        // $list = $pajak->get();
        // // $first = $pajak->first();

        $x = 6;
        foreach($data['data'] as $val){
                $user = isset($val['kasbon']['createdby']->name) ? $val['kasbon']['createdby']->name : '-' ;

                $sheet->setCellValue('A' . $x, $val['tgl_kasbon']);
                $sheet->setCellValue('B' . $x, $val['kode_kasbon']);
                $sheet->setCellValue('C' . $x, $val['gaji']['kode_gaji'] ?? '');
                $sheet->setCellValue('D' . $x, $val['joborder']['kode_joborder'] ?? '');
                $sheet->setCellValue('E' . $x, $val['keterangan']);
                $sheet->setCellValue('F' . $x, $val['debit']);
                $sheet->setCellValue('G' . $x, $val['kredit']);
                $sheet->setCellValue('H' . $x, $val['new_saldo']);
                $sheet->setCellValue('I' . $x, $user. ' ( ' .date('d-m-Y H:i:s', strtotime( $val['kasbon']['created_at'])) .' )');
                $x++;
        }

        $cell   = count($data['data']) + 6;
        $spreadsheet->getActiveSheet()->getStyle('F6:F'.$cell + 3)->getNumberFormat()->setFormatCode('#,##0');
        $spreadsheet->getActiveSheet()->getStyle('G6:G'.$cell)->getNumberFormat()->setFormatCode('#,##0');
        $spreadsheet->getActiveSheet()->getStyle('H6:H'.$cell)->getNumberFormat()->setFormatCode('#,##0');

        $cell_tk = $cell + 1;
        $cell_sba = $cell + 2;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total Debit');
        $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':E' . $cell . '');
        $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$cell, $data['total_debit']);
        $spreadsheet->getActiveSheet()->mergeCells( 'F' . $cell . ':H' . $cell . '');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell_tk, $data['total_kredit']);
        $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell_tk . ':E' . $cell_tk . '');
        $spreadsheet->getActiveSheet()->getStyle('A'.$cell_tk)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$cell_tk, $data['total_kredit']);
        $spreadsheet->getActiveSheet()->mergeCells( 'F' . $cell_tk . ':H' . $cell_tk . '');


        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell_sba, 'Saldo Bon Akhir');
        $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell_sba . ':E' . $cell_sba . '');
        $spreadsheet->getActiveSheet()->getStyle('A'.$cell_sba)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$cell_sba, $data['saldo_akhir']);
        $spreadsheet->getActiveSheet()->mergeCells( 'F' . $cell_sba . ':H' . $cell_sba . '');
    }

    public function excel(Request $request)
    {



            $type = isset($request['jenis']) ? $request['jenis'] : 'excel';
            $data = $this->data($request, $type);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            if($type != 'excel'){
                $this->excel_driver($data, $spreadsheet, $sheet,$request);
                $name = 'Kasbon Driver';
            }else{
                $this->excel_mutasi($data, $spreadsheet, $sheet,$request);
                $name = 'Mutasi Kasbon';
            }





      $writer = new Xlsx($spreadsheet);
      $filename = $name;
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');
    }

    public function pdf(Request $request)
    {

        $driver_id =$request['driver_id'];
        $driver = Driver::find($driver_id);
        $type = isset($request['jenis']) ? $request['jenis'] : 'pdf';
        $data = $this->data($request, $type);

        // dd($type);

        if($type != 'pdf'){
            $data = [
                'data' =>  $data,
                'tgl_awal' => $request['tgl_awal'],
                'tgl_akhir' => $request['tgl_akhir'],
            ];
            $route = 'backend.mutasikasbon.reportdriver';
            $name = 'Laporan-Kasbon-Driver : ';
            $y = 900;
            $oriented = 'potrait';
        }else{
            $data = [
                'mutasikasbon' => $data['data'],
                'driver' =>  $driver['name'],
                'saldo_awal' => $data['saldo_awal'],
                'saldo_akhir' => $data['saldo_akhir'],
                'total_debit' => $data['total_debit'],
                'total_kredit' => $data['total_kredit'],
                'tgl_awal' => $request['tgl_awal'],
                'tgl_akhir' => $request['tgl_akhir'],
            ];

            $route = 'backend.mutasikasbon.reportdriver';
            $name = 'Laporan-MutasiKasbon : ';
            $y = 590;
            $oriented = 'landscape';
        }

        $PAPER_F4 = array(0,0,609.4488,935.433);
        $pdf =  PDF::loadView($route,  compact('data'));
        $fileName = $name. $request['tgl_awal'] . '-SD-' .$request['tgl_akhir'];
        $pdf->setPaper( $PAPER_F4, $oriented);
        $pdf->render();
        $font       = $pdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $pdf->get_canvas()->page_text(33, $y, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
        return $pdf->stream("${fileName}.pdf");
    }

}
