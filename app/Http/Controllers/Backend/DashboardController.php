<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Mobil;
use App\Models\Joborder;
use App\Models\Invoice;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\CarbonPeriod;
use Yajra\DataTables\Facades\DataTables;
use Throwable;
use PDF;

class DashboardController extends Controller
{



    public function data(Request $request){
        $tgl_now = Carbon::now()->format('Y-m-d');
        $customer_id = $request['customer_id'];
        $customerstatus_id = $request['customerstatus_id'];
        $invoice_data = Invoice::with('customer')
        ->when($customer_id, function ($query, $customer_id) {
            return  $query->where('invoice.customer_id', $customer_id);
         })->whereDate('tgl_jatuh_tempo', '<=', $tgl_now)->where('status_payment', '0');


        //  dd($invoice_data->get());
        // dd($customerstatus_id);
         $joborderstatus_data = Joborder::with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil', 'konfirmasijo', 'invoice', 'gaji')
         ->when($customerstatus_id, function ($query,  $customerstatus_id) {
             return  $query->where('joborder.customer_id',  $customerstatus_id);
         });
        //  DD($joborderstatus_data );
         $joborder_data = Joborder::selectRaw('joborder.*, konfirmasi_joborder.tgl_konfirmasi')
         ->leftJoin('konfirmasi_joborder','konfirmasi_joborder.joborder_id', '=', 'joborder.id')
         ->with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil','konfirmasijo')
         ->where('status_joborder', '1')
         ->when($customer_id, function ($query, $customer_id) {
            return  $query->where('joborder.customer_id', $customer_id);
         })
          ->whereNull('joborder.invoice_id');

          $status_jalan =  $request['status_jalan'];
          $type =  $request['type'];
          $driver_data = Driver::where('validasi', '1')
          ->when( $status_jalan, function ($query,   $status_jalan) {
             return  $query->where('status_jalan', '!=', $status_jalan);
           })->when( $type == 'berlaku_sim', function ($query,   $type) {
             return  $query->whereRaw('DATEDIFF(NOW(),tgl_sim) > -45');
           })->get();

        //    $driver_data = array();
           foreach($driver_data as $key => $val){
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_sim =Carbon::parse($val['tgl_sim']);


            if($type = 'berlaku_sim'){
                $diff = $tgl_sim->diffInDays($tgl_now);
                if( $tgl_now > $tgl_sim){
                    $diff = -$tgl_sim->diffInDays($tgl_now);
                }
                if($diff > 0){
                    $text = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                }elseif($diff < 0){
                    $text = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                }else{
                    $text = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                }
                $driver_data[$key]['exp_sim'] =  $text;
                $driver_data[$key]['dif_sim'] =  $diff . ' Hari';
            }


           }





           $status_jalan_mobil =  $request['status_jalan'];
           $type =  $request['type'];
        //   $type =  $request['berlaku_stnk'];
        //    $berlaku_pajak =  $request['berlaku_pajak'];
        //    $berlaku_kir =  $request['berlaku_kir'];
        //    $berlaku_ijin_bongkar =  $request['berlaku_ijin_bongkar'];
        //    $berlaku_ijin_usaha =  $request['berlaku_ijin_usaha'];
           $mobil_data = Mobil::with('merkmobil', 'tipemobil', 'jenismobil')->where('validasi', '1')
           ->when( $status_jalan_mobil, function ($query,  $status_jalan_mobil) {
            return  $query->where('status_jalan', '!=', $status_jalan_mobil);
          })->when(  $type == 'berlaku_stnk' , function ($query,   $type ) {
            return  $query->whereRaw('DATEDIFF(NOW(),berlaku_stnk) > -45');
          })->when(  $type == 'berlaku_pakak' , function ($query,   $type ) {
            return  $query->whereRaw('DATEDIFF(NOW(),berlaku_pajak) > -45');
          })->when(  $type == 'berlaku_kir' , function ($query,   $type ) {
            return  $query->whereRaw('DATEDIFF(NOW(),berlaku_kir) > -45');
          })->when(  $type == 'berlaku_ijin_bongkar' , function ($query,   $type ) {
            return  $query->whereRaw('DATEDIFF(NOW(),berlaku_ijin_bongkar) > -45');
          })->when( $type == 'berlaku_ijin_usaha', function ($query,   $type ) {
            return  $query->whereRaw('DATEDIFF(NOW(),berlaku_ijin_usaha) > -45');
          })->get();



          foreach($mobil_data as $key => $val){
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_stnk =Carbon::parse($val['berlaku_stnk']);
            $tgl_pajak =Carbon::parse($val['berlaku_pajak']);
            $tgl_kir =Carbon::parse($val['berlaku_kir']);
            $tgl_bm =Carbon::parse($val['berlaku_ijin_bongkar']);
            $tgl_iu =Carbon::parse($val['berlaku_ijin_usaha']);

            $text_pajak = $text_bm = $text_iu = $text_kir = $text_stnk = '';

                    if($type = 'berlaku_stnk'){
                        $diff = $tgl_stnk->diffInDays($tgl_now);
                        if( $tgl_now > $tgl_stnk){
                            $diff = -$tgl_stnk->diffInDays($tgl_now);
                        }
                        if($diff > 0){
                            $text_stnk = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                        }elseif($diff < 0){
                            $text_stnk = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                        }else{
                            $text_stnk = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                        }
                        $mobil_data[$key]['exp_stnk'] = $text_stnk;
                        $mobil_data[$key]['dif_stnk'] =  $diff . ' Hari';
                    }
                 if ($type = 'berlaku_pajak'){
                    $diff = $tgl_pajak->diffInDays($tgl_now);
                    if( $tgl_now > $tgl_pajak){
                        $diff = -$tgl_pajak->diffInDays($tgl_now);
                    }
                    if($diff > 0){
                        $text_pajak = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }elseif($diff < 0){
                        $text_pajak = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }else{
                        $text_pajak = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }
                    $mobil_data[$key]['exp_pajak'] =  $text_pajak;
                    $mobil_data[$key]['dif_pajak'] =  $diff . ' Hari';
                }
                if($type = 'berlaku_kir'){
                    $diff = $tgl_kir->diffInDays($tgl_now);
                    if( $tgl_now > $tgl_kir){
                        $diff = -$tgl_kir->diffInDays($tgl_now);
                    }
                    if($diff > 0){
                        $text_kir = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }elseif($diff < 0){
                        $text_kir = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }else{
                        $text_kir = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }
                    $mobil_data[$key]['exp_kir'] =  $text_kir;
                    $mobil_data[$key]['dif_kir'] =  $diff . ' Hari';
                }
                if($type = 'berlaku_ijin_bongkar'){
                    $diff = $tgl_bm->diffInDays($tgl_now);
                    if( $tgl_now > $tgl_bm){
                        $diff = -$tgl_bm->diffInDays($tgl_now);
                    }
                    if($diff > 0){
                        $text_bm = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }elseif($diff < 0){
                        $text_bm = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }else{
                        $text_bm = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }
                    $mobil_data[$key]['exp_bm'] =  $text_bm;
                    $mobil_data[$key]['dif_bm'] =  $diff . ' Hari';
                }
                if($type = 'berlaku_ijin_usaha'){
                    $diff = $tgl_iu->diffInDays($tgl_now);
                    if( $tgl_now > $tgl_iu){
                        $diff = -$tgl_iu->diffInDays($tgl_now);
                    }
                    if($diff > 0){
                        $text_iu = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }elseif($diff < 0){
                        $text_iu = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }else{
                        $text_iu = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
                    }
                    $mobil_data[$key]['exp_iu'] =  $text_iu;
                    $mobil_data[$key]['dif_iu'] =  $diff . ' Hari';
                }


          }

        //   dd($mobil_data);




         $data = [
            'dtinvoice' =>    $invoice_data,
            'dtjoborder' =>    $joborder_data,
            'dtdriver' =>    $driver_data,
            'dtmobil' =>    $mobil_data,
            'dtstjoborder' =>    $joborderstatus_data,
         ];




        return $data;

    }




    public function index(Request $request)
    {
      $config['page_title'] = "DASHBOARD";
      $page_breadcrumbs = [
        ['url' => '#', 'title' => "DASHBOARD"],
      ];
      return view('backend.dashboard.index', compact('config', 'page_breadcrumbs'));
    }
    public function dtdriver(Request $request)
    {
      if ($request->ajax()) {
        $cek = $this->data($request);
        $data = $cek['dtdriver'];
        return DataTables::of($data)
         ->rawColumns(['exp_sim'])
         ->make(true);
      }
    }

    public function dtmobil(Request $request)
    {
      if ($request->ajax()) {

        $cek = $this->data($request);
        $data = $cek['dtmobil'];

        return DataTables::of($data)


         ->rawColumns(['exp_stnk', 'exp_pajak', 'exp_kir', 'exp_bm', 'exp_iu'])
         ->make(true);
      }
    }

    public function dtjo(Request $request)
    {
      if ($request->ajax()) {

        $cek = $this->data($request);
        $data = $cek['dtjoborder'];

        return DataTables::of($data)
        ->addColumn('action', function ($row) {
            $show = '<a href="' . route('backend.joborder.show', $row->id) . '" class="dropdown-item">Detail</a>';
            return '<div class="dropdown">
            <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                Aksi <i class="mdi mdi-chevron-down"></i>
            </a>
            <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                '. $show .'
            </div>
        </div>';

        })
          ->make(true);
      }
    }

    public function dtinvoice(Request $request)
    {
      if ($request->ajax()) {

        $cek = $this->data($request);
        $data = $cek['dtinvoice'];

        return DataTables::of($data)
        ->addColumn('exp_due', function ($row) {
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_due =Carbon::parse($row->tgl_jatuh_tempo);
            $diff = $tgl_due->diffInDays($tgl_now);
            if( $tgl_now > $tgl_due){
                $diff = -$tgl_due->diffInDays($tgl_now);
            }
            if($diff > 0){
                $text = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }elseif($diff < 0){
                $text = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }else{
                $text = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }
            return $text;
         })
        ->addColumn('action', function ($row) {
            $show = '<a href="' . route('backend.joborder.show', $row->id) . '" class="dropdown-item">Detail</a>';
            return '<div class="dropdown">
            <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                Aksi <i class="mdi mdi-chevron-down"></i>
            </a>
            <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                '. $show .'
            </div>
        </div>';

        })
        ->rawColumns(['exp_due', 'action'])
        ->make(true);
      }
    }


    public function dtstatusjo(Request $request)
    {
      if ($request->ajax()) {


        $cek = $this->data($request);
        $data = $cek['dtstjoborder'];


        return DataTables::of($data)
        ->addColumn('action', function ($row) {
            $show = '<a href="' . route('backend.joborder.show', $row->id) . '" class="dropdown-item">Detail</a>';
            return '<div class="dropdown">
            <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                Aksi <i class="mdi mdi-chevron-down"></i>
            </a>
            <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                '. $show .'
            </div>
        </div>';

        })
          ->make(true);
      }
    }



    public function pdf(Request $request)
    {
        $cek = $this->data($request);
        $type = $request['type'];
        switch($type){
            case $type == 'berlaku_sim':
                $view = 'backend.dashboard.pdf.berlaku_sim';
                $data = $cek['dtdriver'];
                $fileName = 'Dashboard-Berlaku-Sim';
                $orient = 'potrait';
                $x = 33;
                $y = 900;
                break;
            case $type == 'berlaku_pajak':
                $view = 'backend.dashboard.pdf.pajak_lima_tahun';
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Pajak-Lima-Tahun';
                $orient = 'potrait';
                $x = 33;
                $y = 900;
                break;
            case $type == 'berlaku_stnk':
                $view = 'backend.dashboard.pdf.pajak_satu_tahun';
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Pajak-Satu-Tahun';
                $orient = 'potrait';
                $x = 33;
                $y = 900;
                break;
            case $type == 'berlaku_ijin_bongkar':
                $view = 'backend.dashboard.pdf.ijin_bongkar';
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Ijin-Bongkar';
                $orient = 'potrait';
                $x = 33;
                $y = 900;
                break;
            case $type == 'berlaku_kir':
                $view = 'backend.dashboard.pdf.kir';
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Berlaku-Kir';
                $orient = 'potrait';
                $x = 33;
                $y = 900;
                break;
            case $type == 'berlaku_ijin_usaha':
                $view = 'backend.dashboard.pdf.ijin_usaha';
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Berlaku-Ijin-Usaha';
                $orient = 'potrait';
                $x = 33;
                $y = 900;
                break;
            case $type == 'kendaraan_tidak_jalan':
                $view = 'backend.dashboard.pdf.kendaraantj';
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Kendaraan-Tidak-Jalan';
                $orient = 'potrait';
                $x = 33;
                $y = 900;
                break;
            case $type == 'driver_tidak_jalan':
                $view = 'backend.dashboard.pdf.drivertj';
                $data = $cek['dtdriver'];
                $fileName = 'Dashboard-Driver-Tidak-Jalan';
                $orient = 'potrait';
                $x = 33;
                $y = 900;
                break;
            case $type == 'invoice':
                $view = 'backend.dashboard.pdf.invoice';
                $data = $cek['dtinvoice'];
                $fileName = 'Dashboard-Invoice-Jatuh-Tempo';
                $orient = 'potrait';
                $x = 33;
                $y = 900;
                break;
            case $type == 'joborder':
                $view = 'backend.dashboard.pdf.joborder';
                $data = $cek['dtjoborder'];
                $fileName = 'Dashboard-Belum-Ada-Invoice ';
                $orient = 'landscape';
                $x = 33;
                $y = 590;
                break;
            case $type == 'status_joborder':
                $data = $cek['dtstjoborder'];
                $view = 'backend.dashboard.pdf.stjoborder';
                $fileName = 'Dashboard-Status-Joborder ';
                $orient = 'landscape';
                $x = 33;
                $y = 590;
                break;

        }

        // foreach($data->get() as $val){
        //     dd($val);
        // }



        $data = [
            'data' => $data,
        ];

        // dd($data);
        $pdf =  PDF::loadView($view,  compact('data'));

        $PAPER = array(0,0,609.4488,935.433);
        $pdf->setPaper( $PAPER, $orient);
        $pdf->render();
        $font       = $pdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $pdf->get_canvas()->page_text( $x, $y, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
        return $pdf->stream("${fileName}.pdf");
    }


    public function excel(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();




        $cek = $this->data($request);
        $type = $request['type'];
        // dd($type);
        $lastcolumn = 'D';
        switch($type){
            case $type == 'berlaku_sim':
                $data = $cek['dtdriver'];
                $fileName = 'Dashboard-Berlaku-Sim';
                $lastcolumn = 'E';
                break;
            case $type == 'berlaku_pajak':
                $view = 'backend.dashboard.pdf.pajak_lima_tahun';
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Pajak-Lima-Tahun';
                $tgl = 'berlaku_pajak';
                $exp = 'dif_pajak';
                break;
            case $type == 'berlaku_stnk':
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Pajak-Satu-Tahun';
                $tgl = 'berlaku_stnk';
                $exp = 'dif_stnk';
                break;
            case $type == 'berlaku_ijin_bongkar':
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Ijin-Bongkar';
                $tgl = 'berlaku_ijin_bongkar';
                $exp = 'dif_iu';
                break;
            case $type == 'berlaku_kir':
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Berlaku-Kir';
                $tgl = 'berlaku_kir';
                $exp = 'dif_kir';
                break;
            case $type == 'berlaku_ijin_usaha':
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Berlaku-Ijin-Usaha';
                $tgl = 'berlaku_ijin_usaha';
                $exp = 'dif_iu';
                break;
            case $type == 'kendaraan_tidak_jalan':
                $data = $cek['dtmobil'];
                $fileName = 'Dashboard-Kendaraan-Tidak-Jalan';
                $lastcolumn = 'E';
                break;
            case $type == 'driver_tidak_jalan':
                $data = $cek['dtdriver'];
                $fileName = 'Dashboard-Driver-Tidak-Jalan';
                $lastcolumn = 'C';
                break;
            case $type == 'invoice':
                $data = $cek['dtinvoice'];
                $fileName = 'Dashboard-Invoice-Jatuh-Tempo';
                $lastcolumn = 'F';
                break;
            case $type == 'joborder':
                $data = $cek['dtjoborder'];
                $fileName = 'Dashboard-Belum-Ada-Invoice ';
                $lastcolumn = 'J';
                break;
            case $type == 'status_joborder':
                $data = $cek['dtstjoborder'];
                $fileName = 'Dashboard-Status-Joborder ';
                break;

        }


         $sheet->setCellValue('A1', $fileName);
         $spreadsheet->getActiveSheet()->mergeCells('A1:'. $lastcolumn . '1');
         $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


         $rows3 = 3;


         for($col = 'A'; $col !== $lastcolumn ; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}
         $x = 4;
         $no = 1;

        if($type == 'berlaku_sim'){
            $sheet->setCellValue('A'.$rows3, 'No');
            $sheet->setCellValue('B'.$rows3, 'Nama Lengkap');
            $sheet->setCellValue('C'.$rows3, 'No Hp');
            $sheet->setCellValue('D'.$rows3, 'Tanggal Expired Sim');
            $sheet->setCellValue('E'.$rows3, 'Masa Berlaku');

            foreach($data as $val){
                    $sheet->setCellValue('A' . $x, $no++);
                    $sheet->setCellValue('B' . $x, $val['name']);
                    $sheet->setCellValue('C' . $x, $val['telp'] ?? '');
                    $sheet->setCellValue('D' . $x, $val['tgl_sim'] ?? '');
                    $sheet->setCellValue('E' . $x, $val['dif_sim'] ?? '');
                    $x++;
            }

        }else if($type == 'kendaraan_tidak_jalan'){

            $sheet->setCellValue('A'.$rows3, 'No');
            $sheet->setCellValue('B'.$rows3, 'Nomor Polisi');
            $sheet->setCellValue('C'.$rows3, 'Merek');
            $sheet->setCellValue('D'.$rows3, 'Jenis');
            $sheet->setCellValue('E'.$rows3, 'Dump');


            foreach($data as $val){
                $sheet->setCellValue('A' . $x, $no++);
                $sheet->setCellValue('B' . $x, $val['namor_plat']);
                $sheet->setCellValue('C' . $x, $val['merkmobil']['name'] ?? '');
                $sheet->setCellValue('D' . $x, $val['jenismobil']['name'] ?? '');
                $sheet->setCellValue('E' . $x, $val['dump'] ?? '');
                $x++;
           }
        }else if($type == 'driver_tidak_jalan'){

            $sheet->setCellValue('A'.$rows3, 'No');
            $sheet->setCellValue('B'.$rows3, 'Nama Lengkap');
            $sheet->setCellValue('C'.$rows3, 'No Hp');



            foreach($data as $val){
                $sheet->setCellValue('A' . $x, $no++);
                $sheet->setCellValue('B' . $x, $val['name']);
                $sheet->setCellValue('C' . $x, $val['telp']?? '');
                $x++;
           }
        }else if($type == 'invoice'){

            $sheet->setCellValue('A'.$rows3, 'No');
            $sheet->setCellValue('B'.$rows3, 'Kode Invoice');
            $sheet->setCellValue('C'.$rows3, 'Tanggal Invoice');
            $sheet->setCellValue('D'.$rows3, 'Customer');
            $sheet->setCellValue('E'.$rows3, 'Nominal Invoice');
            $sheet->setCellValue('F'.$rows3, 'Due Date');


            foreach($data->get() as $val){
                $sheet->setCellValue('A' . $x, $no++);
                $sheet->setCellValue('B' . $x, $val['kode_invoice']);
                $sheet->setCellValue('C' . $x, $val['tgl_invoice']?? '');
                $sheet->setCellValue('D' . $x, $val['customer']['name'] ?? '');
                $sheet->setCellValue('E' . $x, $val['total_harga'] ?? '');
                $sheet->setCellValue('F' . $x, $val['tgl_jatuh_tempo'] ?? '');
                $x++;
           }

           $cell   = count($data->get()) + 4;

           $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total :');
           $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':D' . $cell . '');
           $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


           $spreadsheet->getActiveSheet()->getStyle('E4:E'.$cell)->getNumberFormat()->setFormatCode('#,##0');
           $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$cell, '=SUM(E3:E' . $cell . ')');
        }
        else if($type == 'joborder'){
            $sheet->setCellValue('A'.$rows3, 'No');
            $sheet->setCellValue('B'.$rows3, 'Kode Joborder');
            $sheet->setCellValue('C'.$rows3, 'Driver');
            $sheet->setCellValue('D'.$rows3, 'Nomor Polisi');
            $sheet->setCellValue('E'.$rows3, 'Jenis Mobil');
            $sheet->setCellValue('F'.$rows3, 'Customer');
            $sheet->setCellValue('G'.$rows3, 'Muatan');
            $sheet->setCellValue('H'.$rows3, 'Alamat Awal (Dari)');
            $sheet->setCellValue('I'.$rows3, 'Alamat Akhir (Ke)');
            $sheet->setCellValue('J'.$rows3, 'Tanggal Closing');

            foreach($data->get() as $val){
                $sheet->setCellValue('A' . $x, $no++);
                $sheet->setCellValue('B' . $x, $val['kode_joborder']);
                $sheet->setCellValue('C' . $x, $val['driver']['name']?? '');
                $sheet->setCellValue('D' . $x, $val['mobil']['nomor_plat'] ?? '');
                $sheet->setCellValue('E' . $x, $val['jenismobil']['name'] ?? '');
                $sheet->setCellValue('F' . $x, $val['customer']['name'] ?? '');
                $sheet->setCellValue('G' . $x, $val['muatan']['name'] ?? '');
                $sheet->setCellValue('H' . $x, $val['ruteawal']['name'] ?? '');
                $sheet->setCellValue('I' . $x, $val['ruteakhir']['name'] ?? '');
                $sheet->setCellValue('J' . $x, $val->konfirmasijo[0]['tgl_konfirmasi'] ?? '');
                $x++;
           }
        }
        else if($type == 'status_joborder'){

            $sheet->setCellValue('A'.$rows3, 'No');
            $sheet->setCellValue('B'.$rows3, 'Tanggal Joborder');
            $sheet->setCellValue('C'.$rows3, 'Kode Joborder');
            $sheet->setCellValue('D'.$rows3, 'Driver');
            $sheet->setCellValue('E'.$rows3, 'Nomor Polisi');
            $sheet->setCellValue('F'.$rows3, 'Jenis Mobil');
            $sheet->setCellValue('G'.$rows3, 'Customer');
            $sheet->setCellValue('H'.$rows3, 'Rute Awal (Dari)');
            $sheet->setCellValue('I'.$rows3, 'Rute Akhir (Ke)');
            $sheet->setCellValue('J'.$rows3, 'Muatan');
            $sheet->setCellValue('K'.$rows3, 'Tonase');
            $sheet->setCellValue('L'.$rows3, 'Total UJ');
            $sheet->setCellValue('M'.$rows3, 'Kode Gaji');
            $sheet->setCellValue('N'.$rows3, 'Tanggal Pay Gaji');
            $sheet->setCellValue('O'.$rows3, 'Kode Invoice');
            $sheet->setCellValue('P'.$rows3, 'Total Tagihan Invoice');

            foreach($data->get() as $val){

                $sheet->setCellValue('A' . $x, $no++);
                $sheet->setCellValue('B' . $x, $val['kode_joborder']);
                $sheet->setCellValue('C' . $x, $val['tgl_joborder']);
                $sheet->setCellValue('D' . $x, $val['driver']['name']?? '');
                $sheet->setCellValue('E' . $x, $val['mobil']['nomor_plat'] ?? '');
                $sheet->setCellValue('F' . $x, $val['jenismobil']['name'] ?? '');
                $sheet->setCellValue('G' . $x, $val['customer']['name'] ?? '');
                $sheet->setCellValue('H' . $x, $val['ruteawal']['name'] ?? '');
                $sheet->setCellValue('I' . $x, $val['ruteakhir']['name'] ?? '');
                $sheet->setCellValue('J' . $x, $val['muatan']['name'] ?? '');
                $sheet->setCellValue('K' . $x, $val->konfirmasijo[0]['berat_muatan'] ?? '');
                $sheet->setCellValue('L' . $x, $val['total_uang_jalan'] ?? '0');
                $sheet->setCellValue('M' . $x, $val['gaji']['kode_gaji'] ?? '');
                $sheet->setCellValue('N' . $x, $val['gaji']->payment[0]['tgl_payment'] ?? '');
                $sheet->setCellValue('O' . $x, $val['invoice']['kode_invoice'] ?? '');
                $sheet->setCellValue('P' . $x, $val['invoice']['total_harga']  ?? '0');
                $x++;
           }

           $cell   = count($data->get()) + 4;

           $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total :');
           $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':K' . $cell . '');
           $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


           $spreadsheet->getActiveSheet()->getStyle('L4:L'.$cell)->getNumberFormat()->setFormatCode('#,##0');
           $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$cell, '=SUM(L3:L' . $cell . ')');

           $spreadsheet->getActiveSheet()->getStyle('P4:P'.$cell)->getNumberFormat()->setFormatCode('#,##0');
           $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$cell, '=SUM(P3:P' . $cell . ')');
        }


        else{
            $sheet->setCellValue('A'.$rows3, 'No');
            $sheet->setCellValue('B'.$rows3, 'No Polisi');
            $sheet->setCellValue('C'.$rows3, 'Tanggal Expired');
            $sheet->setCellValue('D'.$rows3, 'Masa Berlaku');


            foreach($data as $val){
                    $sheet->setCellValue('A' . $x, $no++);
                    $sheet->setCellValue('B' . $x, $val['nomor_plat']);
                    $sheet->setCellValue('C' . $x, $val[$tgl] ?? '');
                    $sheet->setCellValue('D' . $x, $val[$exp] ?? '');
                    $x++;
            }
        }

        // $cell   = count($data) + 4;



      $writer = new Xlsx($spreadsheet);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' .  $fileName . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');
    }

}
