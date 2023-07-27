<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\PaymentGaji;
use Carbon\Carbon;
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
                $payment = PaymentGaji::with('penggajian')->orderBy('tgl_payment','desc')
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

        $pdf =  PDF::loadView('backend.rptgaji.pdf',  compact('data'));
        $pdf->setPaper('F4', 'landscape');
        $fileName = 'Laporan-Payment-Gaji : '. $tgl_awal . '-SD-' .$tgl_akhir;
        return $pdf->stream("${fileName}.pdf");
    }

    public function excel(Request $request)
    {

        $tgl_awal = date('Y-m-d', strtotime($request['tgl_awal']));
        $tgl_akhir =  date('Y-m-d', strtotime($request['tgl_akhir']));
        // $total_debit_awal = $total_kredit_awal =   $total_debit = $total_kredit = $saldo_awal = $saldo_akhir = 0;
        $data = PaymentGaji::with('penggajian')->orderBy('tgl_payment','desc')
        ->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_payment', '>=', $tgl_awal);
         })
         ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_payment', '<=', $tgl_akhir);
        })->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $spreadsheet->getActiveSheet()->mergeCells('A1:K1');
            $sheet->setCellValue('A1', 'Laporan Payment Gaji');
            $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->mergeCells('A2:K2');
            $sheet->setCellValue('A2', 'Tanggal : '. date('d-m-Y', strtotime($request['tgl_awal'])) .' S/D '. date('d-m-Y', strtotime($request['tgl_akhir'])));
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


            $rows5 = 5;
            $sheet->setCellValue('A'.$rows5, 'No');
            $sheet->setCellValue('B'.$rows5, 'Tanggal Payment');
            $sheet->setCellValue('C'.$rows5, 'Kode Gaji');
            $sheet->setCellValue('D'.$rows5, 'Nama Driver');
            $sheet->setCellValue('E'.$rows5, 'No Polisi');
            $sheet->setCellValue('F'.$rows5, 'Periode Gaji');
            $sheet->setCellValue('G'.$rows5, 'Jenis Pembayaran');
            $sheet->setCellValue('H'.$rows5, 'Nominal Pembayaran');
            $sheet->setCellValue('I'.$rows5, 'Sisa Pembayaran');
            $sheet->setCellValue('J'.$rows5, 'Total Gaji');
            $sheet->setCellValue('K'.$rows5, 'Operator (Waktu)');

            for($col = 'A'; $col !== 'L'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}

            // $list = $pajak->get();
            // // $first = $pajak->first();

            $x = 6;
            $no = 1;
            foreach($data as $val){
                    $sheet->setCellValue('A' . $x, $no++);
                    $sheet->setCellValue('B' . $x, $val['tgl_payment']);
                    $sheet->setCellValue('C' . $x, $val['kode_gaji'] ?? '');
                    $sheet->setCellValue('D' . $x, $val['penggajian']->driver['name']);
                    $sheet->setCellValue('E' . $x, $val['penggajian']->mobil['nomor_plat']);
                    $sheet->setCellValue('F' . $x, Carbon::parse($val['penggajian']->bulan_kerja)->isoFormat('MMMM Y'));
                    $sheet->setCellValue('G' . $x, $val['jenis_payment'] ?? '');
                    $sheet->setCellValue('H' . $x, $val['nominal']);
                    $sheet->setCellValue('I' . $x, $val['penggajian']->sisa_gaji);
                    $sheet->setCellValue('J' . $x, $val['penggajian']->total_gaji);
                    $sheet->setCellValue('K' . $x, $val['penggajian']->createdby['name'] . ' ( ' .date('d-m-Y', strtotime($val['penggajian']->created_at)) .' )');
                    $x++;
            }

            $cell   = count($data) + 6;
            // $spreadsheet->getActiveSheet()->getStyle('F6:F'.$cell + 3)->getNumberFormat()->setFormatCode('#,##0');
            $spreadsheet->getActiveSheet()->getStyle('H6:H'.$cell)->getNumberFormat()->setFormatCode('#,##0');
            $spreadsheet->getActiveSheet()->getStyle('I6:I'.$cell)->getNumberFormat()->setFormatCode('#,##0');
            $spreadsheet->getActiveSheet()->getStyle('J6:J'.$cell)->getNumberFormat()->setFormatCode('#,##0');


            // $cell_tk = $cell + 1;
            // $cell_sba = $cell + 2;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total :');
            $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':G' . $cell . '');
            $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$cell, '=SUM(H5:H' . $cell . ')');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$cell, '=SUM(I5:I' . $cell . ')');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$cell, '=SUM(J5:J' . $cell . ')');


      $writer = new Xlsx($spreadsheet);
      $filename = 'Laporan Payment Gaji';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');

    }

}
