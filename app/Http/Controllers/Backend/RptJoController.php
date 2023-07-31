<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\PaymentJo;
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

class RptJoController extends Controller
{
    use ResponseStatus;

    function __construct()
    {
        $this->middleware('can:backend-rptjo-list', ['only' => ['index']]);
        $this->middleware('can:backend-rptjo-create', ['only' => ['create', 'store']]);
        $this->middleware('can:backend-rptjo-edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:backend-rptjo-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $config['page_title'] = "Laporan Payment Jo";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Laporan Payment Jo"],
        ];

        return view('backend.rptjo.index', compact('config', 'page_breadcrumbs'));
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
                $payment = PaymentJo::with('joborder')->orderBy('kode_joborder','desc')
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
                return view('backend.rptjo.report', compact('data'));
          } else {
            $response = response()->json(['error' => $validator->errors()->all()]);
          }

    }


    public function pdf(Request $request)
    {

                $tgl_awal = $request['tgl_awal'];
                $tgl_akhir = $request['tgl_akhir'];
                // $rup = Rup::where('')
                $payment = PaymentJo::orderBy('kode_joborder','desc')
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

        $pdf =  PDF::loadView('backend.rptjo.pdf',  compact('data'));
        $pdf->setPaper('F4', 'landscape');
        $fileName = 'Laporan-Payment_JO : '. $tgl_awal . '-SD-' .$tgl_akhir;
        return $pdf->stream("${fileName}.pdf");
    }

    public function excel(Request $request)
    {

        $tgl_awal = date('Y-m-d', strtotime($request['tgl_awal']));
        $tgl_akhir =  date('Y-m-d', strtotime($request['tgl_akhir']));
        // $total_debit_awal = $total_kredit_awal =   $total_debit = $total_kredit = $saldo_awal = $saldo_akhir = 0;
        $data = PaymentJo::with('joborder')->orderBy('kode_joborder','desc')
        ->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_payment', '>=', $tgl_awal);
         })
         ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_payment', '<=', $tgl_akhir);
        })->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $spreadsheet->getActiveSheet()->mergeCells('A1:H1');
            $sheet->setCellValue('A1', 'Laporan Payment Jo');
            $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->mergeCells('A2:H2');
            $sheet->setCellValue('A2', 'Tanggal : '. date('d-m-Y', strtotime($request['tgl_awal'])) .' S/D '. date('d-m-Y', strtotime($request['tgl_akhir'])));
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


            $rows5 = 5;
            $sheet->setCellValue('A'.$rows5, 'No');
            $sheet->setCellValue('B'.$rows5, 'Tanggal Payment');
            $sheet->setCellValue('C'.$rows5, 'Kode Joborder');
            $sheet->setCellValue('D'.$rows5, 'Jenis Pembayaran');
            $sheet->setCellValue('E'.$rows5, 'Nominal Pembayaran');
            $sheet->setCellValue('F'.$rows5, 'Nominal Kasbon');
            $sheet->setCellValue('G'.$rows5, 'Keterangan Kasbon');
            $sheet->setCellValue('H'.$rows5, 'Operator (Waktu)');
            for($col = 'A'; $col !== 'I'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}

            // $list = $pajak->get();
            // // $first = $pajak->first();

            $x = 6;
            $no = 1;
            $total_uj = $nominal = $nominal_kasbon = 0;
            foreach($data as $val){

                    $total_uj += $val['joborder']->total_uang_jalan;
                    $nominal += $val['nominal'];
                    $nominal_kasbon += $val['nominal_kasbon'];

                    $sheet->setCellValue('A' . $x, $no++);
                    $sheet->setCellValue('B' . $x, $val['tgl_payment']);
                    $sheet->setCellValue('C' . $x, $val['kode_joborder'] ?? '');
                    $sheet->setCellValue('D' . $x, $val['jenis_payment'] ?? '');
                    $sheet->setCellValue('E' . $x, $val['nominal']);
                    $sheet->setCellValue('F' . $x, $val['nominal_kasbon']);
                    $sheet->setCellValue('G' . $x, $val['keterangan_kasbon']);
                    $sheet->setCellValue('H' . $x, $val['joborder']->createdby['name'] . ' ( ' .date('d-m-Y', strtotime($val['joborder']->created_at)) .' )');
                    $x++;
            }

            $cell   = count($data) + 6;
            // $spreadsheet->getActiveSheet()->getStyle('F6:F'.$cell + 3)->getNumberFormat()->setFormatCode('#,##0');


            // $cell_tk = $cell + 1;
            // $cell_sba = $cell + 2;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total :');
            $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':D' . $cell . '');
            $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$cell, '=SUM(F5:F' . $cell . ')');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$cell, '=SUM(E5:E' . $cell . ')');

             $cell_gt =  $cell + 1;
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'. $cell_gt, 'Grand Total :');
             $spreadsheet->getActiveSheet()->mergeCells( 'A' .  $cell_gt . ':D' .  $cell_gt . '');
             $spreadsheet->getActiveSheet()->mergeCells( 'E' .  $cell_gt . ':F' .  $cell_gt . '');
             $spreadsheet->getActiveSheet()->getStyle('A'.$cell_gt)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$cell_gt, $total_uj - $nominal_kasbon);

             $spreadsheet->getActiveSheet()->getStyle('F6:F'.$cell_gt)->getNumberFormat()->setFormatCode('#,##0');
             $spreadsheet->getActiveSheet()->getStyle('E6:E'.$cell_gt)->getNumberFormat()->setFormatCode('#,##0');


      $writer = new Xlsx($spreadsheet);
      $filename = 'Laporan Payment Jo';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');

    }

}
