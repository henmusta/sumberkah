<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\PaymentGaji;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;
use PDF;

class RptGajiController extends Controller
{
    use ResponseStatus;

    function __construct()
    {
        $this->middleware('can:backend-rptgaji-list', ['only' => ['index']]);
        $this->middleware('can:backend-rptgaji-create', ['only' => ['create', 'store']]);
        $this->middleware('can:backend-rptgaji-edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:backend-rptgaji-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $config['page_title'] = "Laporan Payment Gaji";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Laporan Payment Gaji"],
        ];

        return view('backend.rptgaji.index', compact('config', 'page_breadcrumbs'));
    }

    public function getreport(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
          ]);

          if ($validator->passes()) {
                $tgl_awal = $request['tgl_awal'];
                $tgl_akhir = $request['tgl_akhir'];
                // $rup = Rup::where('')
                $payment = PaymentGaji::orderBy('tgl_payment','desc')
                ->when($tgl_awal, function ($query, $tgl_awal) {
                    return $query->whereDate('tgl_payment', '>=', $tgl_awal);
                 })
                 ->when($tgl_akhir, function ($query, $tgl_akhir) {
                    return $query->whereDate('tgl_payment', '<=', $tgl_akhir);
                })->get();

                $data = [
                    'tgl_awal' => $request['tgl_awal'],
                    'tgl_akhir' => $request['tgl_akhir'],
                    'payment' => $payment,
                ];

                // dd( $data);
                return view('backend.rptgaji.report', compact('data'));
          } else {
            $response = response()->json(['error' => $validator->errors()->all()]);
          }

    }


    public function pdf(Request $request)
    {

                $tgl_awal = $request['tgl_awal'];
                $tgl_akhir = $request['tgl_akhir'];
                // $rup = Rup::where('')
                $payment = PaymentGaji::orderBy('tgl_payment','desc')
                ->when($tgl_awal, function ($query, $tgl_awal) {
                    return $query->whereDate('tgl_payment', '>=', $tgl_awal);
                 })
                 ->when($tgl_akhir, function ($query, $tgl_akhir) {
                    return $query->whereDate('tgl_payment', '<=', $tgl_akhir);
                })->get();

                $data = [
                    'tgl_awal' => $request['tgl_awal'],
                    'tgl_akhir' => $request['tgl_akhir'],
                    'payment' => $payment,
                ];

        $pdf =  PDF::loadView('backend.rptgaji.report',  compact('data'));
        $fileName = 'Laporan-Payment_JO : '. $tgl_awal . '-SD-' .$tgl_akhir;
        return $pdf->stream("${fileName}.pdf");
    }

    public function excel(Request $request)
    {

        $tgl_awal = date('Y-m-d', strtotime($request['tgl_awal']));
        $tgl_akhir =  date('Y-m-d', strtotime($request['tgl_akhir']));
        // $total_debit_awal = $total_kredit_awal =   $total_debit = $total_kredit = $saldo_awal = $saldo_akhir = 0;
        $data = PaymentGaji::orderBy('tgl_payment','desc')
        ->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_payment', '>=', $tgl_awal);
         })
         ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_payment', '<=', $tgl_akhir);
        })->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $spreadsheet->getActiveSheet()->mergeCells('A1:F1');
            $sheet->setCellValue('A1', 'Laporan Payment Gaji');
            $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->mergeCells('A2:F2');
            $sheet->setCellValue('A2', 'Tanggal : '. date('d-m-Y', strtotime($request['tgl_awal'])) .' S/D '. date('d-m-Y', strtotime($request['tgl_akhir'])));
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


            $rows5 = 5;
            $sheet->setCellValue('A'.$rows5, 'No');
            $sheet->setCellValue('B'.$rows5, 'Tanggal Payment');
            $sheet->setCellValue('C'.$rows5, 'Kode Joborder');
            $sheet->setCellValue('D'.$rows5, 'Jenis Pembayaran');
            $sheet->setCellValue('E'.$rows5, 'Keterangan Pembayaran');
            $sheet->setCellValue('F'.$rows5, 'Nominal Pembayaran');

            for($col = 'A'; $col !== 'F'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}

            // $list = $pajak->get();
            // // $first = $pajak->first();

            $x = 6;
            $no = 1;
            foreach($data as $val){
                    $sheet->setCellValue('A' . $x, $no++);
                    $sheet->setCellValue('B' . $x, $val['tgl_payment']);
                    $sheet->setCellValue('C' . $x, $val['kode_joborder'] ?? '');
                    $sheet->setCellValue('D' . $x, $val['jenis_payment'] ?? '');
                    $sheet->setCellValue('E' . $x, $val['keterangan']);
                    $sheet->setCellValue('F' . $x, $val['nominal']);
                    $x++;
            }

            $cell   = count($data) + 6;
            // $spreadsheet->getActiveSheet()->getStyle('F6:F'.$cell + 3)->getNumberFormat()->setFormatCode('#,##0');
            $spreadsheet->getActiveSheet()->getStyle('F6:F'.$cell)->getNumberFormat()->setFormatCode('#,##0');

            // $cell_tk = $cell + 1;
            // $cell_sba = $cell + 2;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total :');
            $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':E' . $cell . '');
            $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$cell, '=SUM(F5:F' . $cell . ')');


      $writer = new Xlsx($spreadsheet);
      $filename = 'Laporan Payment Gaji';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');

    }

}
