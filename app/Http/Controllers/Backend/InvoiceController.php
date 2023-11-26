<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\KonfirmasiJo;
use App\Models\Customer;
use App\Models\Joborder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Traits\NoUrutTrait;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Throwable;

class InvoiceController extends Controller
{
    use ResponseStatus,NoUrutTrait;
    function __construct()
    {
      $this->middleware('can:backend-invoice-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-invoice-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-invoice-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-invoice-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Invoice";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Invoice"],
        ];

        $invoice = Invoice::with('customer')->find($request['invoice_id']);
        $belum_bayar = Invoice::selectRaw('sum(sisa_tagihan) as belum_bayar')->where('status_payment', '!=', '2')->first();
        $data = [
          'invoice' => $invoice,
          'belum_bayar' => $belum_bayar,
        ];
        if ($request->ajax()) {
          $data = Invoice::selectRaw('invoice.*')->with('customer');

            if ($request->filled('status_payment')) {
                 $data->where('status_payment', $request['status_payment']);
            }
            if ($request->filled('customer_id')) {
                $data->where('customer_id', $request['customer_id']);
            }

            if ($request->filled('id')) {
                $data->where('id', $request['id']);
            }

            if ($request->filled('ppn')) {
                $data->where('ppn', $request['ppn']);
            }

            if ($request->filled('tgl_invoice')) {
                $data->whereDate('tgl_invoice', $request['tgl_invoice']);
            }

            if ($request->filled('tgl_jatuh_tempo')) {
                $data->whereDate('tgl_jatuh_tempo', $request['tgl_jatuh_tempo']);
            }
            if ($request->filled('tgl_awal')) {
                    $data->whereDate('tgl_invoice', '>=', $request['tgl_awal']);
            }
            if ($request->filled('tgl_akhir')) {
                $data->whereDate('tgl_invoice', '<=', $request['tgl_akhir']);
            }

          return DataTables::of($data)
            ->editColumn('tgl_jatuh_tempo', function ($row) {
                $tgl_invoice = Carbon::parse($row->tgl_invoice);
                $tgl_jatuh_tempo =Carbon::parse($row->tgl_jatuh_tempo);
                $diff = $tgl_invoice->diffInDays($tgl_jatuh_tempo);
                return $diff.' Hari ('.Carbon::parse($row->tgl_jatuh_tempo)->format('Y-m-d').')';
             })
            ->addColumn('action', function ($row) {


                $perm = [
                    'list' => Auth::user()->can('backend-invoice-list'),
                    'create' => Auth::user()->can('backend-invoice-create'),
                    'edit' => Auth::user()->can('backend-invoice-edit'),
                    'delete' => Auth::user()->can('backend-invoice-delete'),
                ];

                // dd( $diff);
                $inovie_jenis = $row->jenis != 'custom' ? 'invoice' : 'invoicecustom';
                $show = '<a href="' . route('backend.'.$inovie_jenis.'.show', $row->id) . '" class="dropdown-item" target="_blank">Detail</a>';
                $list_payment = '<a href="' . route('backend.paymentinvoice.index', ['invoice_id'=> $row->id]) . '" class="dropdown-item">List Payment</a>';
                $edit = '<a class="dropdown-item" href="'.$inovie_jenis.'/' . $row->id . '/edit">Ubah</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';
                $payment = '<a href="' . route('backend.paymentinvoice.create',  ['invoice_id'=> $row->id]) . '" class="dropdown-item">Pembayaran</a>';
                $cicilan = '<a class="dropdown-item" href="paymentinvoice/' . $row->id . '/edit">Pelunasan</a>';

                $cek_payment = $row->status_payment == '0' ? $payment : ($row->status_payment == '1'  ? $cicilan : '');
                $cek_edit =  $row->status_payment == '0' ? $edit : '';
                $cek_delete =  $row->status_payment == '0' ? $delete : '';
                $cek_list_payment = $row->status_payment > '0'  && $row->jenis == 'default' ? $list_payment : '';

                $cek_perm_edit = $perm['edit'] == 'true' ? $cek_edit : '';
                $cek_perm_delete = $perm['delete'] == 'true' ? $cek_delete : '';

                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '.$show.'
                    '.$cek_payment .'
                    '.$cek_perm_edit .'
                    '.$cek_perm_delete .'
                    '.$cek_list_payment.'
                </div>
            </div>';

            })
            ->rawColumns(['tgl_jatuh_tempo', 'action'])
            ->make(true);
        }

        return view('backend.invoice.index', compact('config', 'page_breadcrumbs', 'data'));
    }

    public function create(Request $request)
    {

      $config['page_title'] = "Tambah Invoice";
      $page_breadcrumbs = [
        ['url' => route('backend.invoice.index'), 'title' => "Data Invoice"],
        ['url' => '#', 'title' => "Tambah Invoice"],
      ];
      return view('backend.invoice.create', compact('page_breadcrumbs', 'config'));
    }

    public function edit($id)
    {
        $config['page_title'] = "Update Invoice";

        $page_breadcrumbs = [
          ['url' => route('backend.invoice.index'), 'title' => "Daftar Invoice"],
          ['url' => '#', 'title' => "Update Kendaraan"],
        ];
        $invoice = Invoice::with('customer')->findOrFail($id);
        $data = [
          'invoice' => $invoice,
        ];

        return view('backend.invoice.edit', compact('page_breadcrumbs', 'config', 'data'));
    }



    public function store(Request $request)
    {
        // dd($request);

          $validator = Validator::make($request->all(), [
            'tgl_invoice'  => "required",
            'customer_id'  => "required",
            'payment_hari'  => "required",
            'tambahan_potongan'  => 'required',
            'nominal_tambahan_potongan'  => "required_if:tambahan_potongan,!=,'None'",
            'ppn'  =>  "required_if:ppn,!=,Tidak",
            'nominal_ppn'  => "required",
            'total_tonase'  => "required",
            'sub_total'  => "required",
            'total_harga'  => "required",

            'konfirmasijoid'  => "required",
          ]);
        //   dd($request);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                  $tgl_jatuh_tempo = Carbon::createFromFormat('Y-m-d',  $request['tgl_invoice'])->addDays($request['payment_hari'])->format('Y-m-d');
                // dd($tgl_jatuh_tempo);
                  $kode =  $this->KodeInvoice(Carbon::parse($request['tgl_invoice'])->format('d M Y'));
                  $data = Invoice::create([
                    'tgl_invoice'  => $request['tgl_invoice'],
                    'tgl_jatuh_tempo' =>  $tgl_jatuh_tempo,
                    'kode_invoice' =>  $kode,
                    'customer_id'  => $request['customer_id'],
                    'total_tonase'  => $request['total_tonase'],
                    'payment_hari'  => $request['payment_hari'],
                    'tambahan_potongan'  => $request['tambahan_potongan'],
                    'nominal_tambahan_potongan'  => $request['nominal_tambahan_potongan'],
                    'ppn'  => $request['ppn'],
                    'nominal_ppn'  => $request['nominal_ppn'],
                    'sub_total'  => $request['sub_total'],
                    'total_harga'  => $request['total_harga'],
                    'sisa_tagihan'  => $request['total_harga'],
                    'keterangan_invoice'  => $request['keterangan_invoice'],
                    'created_by' => Auth::user()->id,
                    'kode_joborder'  => $request['kode_joborder'],
                    // 'konfirmasijoid'  => $request['konfirmasijoid'],
                  ]);

                  if(isset($data['id'])){

                    foreach($request['konfirmasijoid'] as $val){
                        $konfirmasijo = KonfirmasiJo::findOrFail($val);
                        //  dd($konfirmasijo);

                        $joborder = Joborder::findOrFail($konfirmasijo['joborder_id']);
                        $joborder->update([
                            'invoice_id' =>  $data['id'],
                            'kode_invoice' =>  $kode,
                        ]);
                        $konfirmasijo->update([
                            'invoice_id' =>  $data['id'],
                            'kode_invoice' =>  $kode,
                            'status' =>  '1',
                        ]);
                    }

                  }
              DB::commit();
              $response = response()->json($this->responseStore(true, route('backend.invoice.index')));
            } catch (Throwable $throw) {
              dd($throw);
              DB::rollBack();
              $response = response()->json($this->responseStore(false));
            }
          } else {
            $response = response()->json(['error' => $validator->errors()->all()]);
          }
          return $response;
    }

    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
        'tgl_invoice'  => "required",
        'customer_id'  => "required",
        'payment_hari'  => "required",
        'tambahan_potongan'  => 'required',
        'nominal_tambahan_potongan'  => "required_if:tambahan_potongan,!=,'None'",
        'ppn'  =>  "required",
        'nominal_ppn'  => "required_if:ppn,!=,'Tidak'",
        'total_tonase'  => "required",
        'sub_total'  => "required",
        'total_harga'  => "required",

        'konfirmasijoid'  => "required",
      ]);

      if ($validator->passes()) {
        DB::beginTransaction();
        try {
            $data = Invoice::find($id);
            $tgl_jatuh_tempo = Carbon::createFromFormat('Y-m-d',  $request['tgl_invoice'])->addDays($request['payment_hari'])->format('Y-m-d');
            $kode =  $this->KodeInvoice(Carbon::parse($request['tgl_invoice'])->format('d M Y'));
            $kode_update =  $data['tgl_invoice'] != $request['tgl_invoice'] ? $kode : $data['kode_invoice'];
            $data->update([
                'tgl_invoice'  => $request['tgl_invoice'],
                'tgl_jatuh_tempo' =>  $tgl_jatuh_tempo,
                'kode_invoice' =>  $kode_update,
                'customer_id'  => $request['customer_id'],
                'total_tonase'  => $request['total_tonase'],
                'payment_hari'  => $request['payment_hari'],
                'tambahan_potongan'  => $request['tambahan_potongan'],
                'nominal_tambahan_potongan'  => $request['nominal_tambahan_potongan'],
                'ppn'  => $request['ppn'],
                'nominal_ppn'  => $request['nominal_ppn'],
                'sub_total'  => $request['sub_total'],
                'total_harga'  => $request['total_harga'],
                'sisa_tagihan'  => $request['total_harga'],
                'keterangan_invoice'  => $request['keterangan_invoice'],
                'kode_joborder'  => $request['kode_joborder'],
                'updated_by' => Auth::user()->id,
            ]);

            if(isset($data['id'])){

                $joborder_id = [];
                foreach($request['konfirmasijoid'] as $val){
                    $konjoborder = KonfirmasiJo::find($val);
                    $joborder_id[] = $konjoborder['joborder_id'];
                }

                $cek_konfirmasijo = KonfirmasiJo::where([
                    ['invoice_id' , $data['id']],
                ])->whereNotIn('joborder_id', $joborder_id);

                // dd($cek_konfirmasijo->get());
                if($cek_konfirmasijo->get()){
                    $kjo = $cek_konfirmasijo->update([
                        'status' =>  '0',
                        'invoice_id' =>  null,
                        'kode_invoice' =>  null,
                    ]);
                }
                $cek_jo = Joborder::where([
                    ['invoice_id' , $data['id']],
                ])->whereNotIn('id', $joborder_id);


                if($cek_jo->get()){
                    $jo = $cek_jo->update([
                        'invoice_id' =>  null,
                        'kode_invoice' =>  null,
                    ]);
                }







                // dd($kjo);
                // $cek_joborder = KonfirmasiJo::where([
                //     ['invoice_id' , $data['id']],
                // ])->whereIn('joborder_id', $joborder_id)->get();
                // dd( $cek_joborder);
                foreach($request['konfirmasijoid'] as $val){
                    // dd($val);
                    $konfirmasijo = KonfirmasiJo::findOrFail($val);
                    $joborder = Joborder::findOrFail($konfirmasijo['joborder_id']);

                    $joborder->update([
                        'invoice_id' =>  $data['id'],
                        'kode_invoice' =>  $data['kode_invoice'],
                    ]);
                    $konfirmasijo->update([
                        'invoice_id' =>  $data['id'],
                        'kode_invoice' =>  $data['kode_invoice'],
                        'status' =>  '1',
                    ]);
                }



                // dd();





              }
          DB::commit();
          $response = response()->json($this->responseStore(true, route('backend.invoice.index')));

        } catch (Throwable $throw) {
            dd($throw);
          DB::rollBack();
          $response = response()->json($this->responseUpdate(false));
        }
      } else {
        $response = response()->json(['error' => $validator->errors()->all()]);
      }
      return $response;
    }

    public function show($id)
    {
        $config['page_title'] = "Detail Invoice";

        $page_breadcrumbs = [
          ['url' => route('backend.joborder.index'), 'title' => "Detail Invoice"],
          ['url' => '#', 'title' => "Detail Invoice"],
        ];
        $invoice = Invoice::with('customer', 'joborder')->findOrFail($id);


        $konfirmasiJo = KonfirmasiJo::with('joborder')->where('invoice_id',  $invoice['id'])->get();

        // dd( $invoice['joborder'][0]['muatan']);
        $data = [
          'invoice' => $invoice,
          'konfirmasijo' => $konfirmasiJo,
        ];
        return view('backend.invoice.show', compact('page_breadcrumbs', 'config', 'data'));
    }



    public function select2(Request $request)
    {
      $page = $request->page;
      $resultCount = 10;

      $offset = ($page - 1) * $resultCount;
      $konfirmasi_invoice = $request['konfirmasi_invoice'];
      $data = Invoice::where('kode_invoice', 'LIKE', '%' . $request->q . '%')
        ->when($konfirmasi_invoice, function ($query, $konfirmasi_invoice) {
            return $query->where('status_payment', '!=' ,$konfirmasi_invoice);
         })
        ->orderBy('kode_invoice')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, kode_invoice as text')
        ->get();

      $count =  Invoice::where('kode_invoice', 'LIKE', '%' . $request->q . '%')
        ->when($konfirmasi_invoice, function ($query, $konfirmasi_invoice) {
            return $query->where('status_payment', '!=' ,$konfirmasi_invoice);
        })
        ->get()
        ->count();

      $endCount = $offset + $resultCount;
      $morePages = $count > $endCount;

      $results = array(
        "results" => $data,
        "pagination" => array(
          "more" => $morePages
        )
      );

      return response()->json($results);
    }


    public function findinvoice(Request $request)
    {
      $invoice = Invoice::findOrFail($request['id']);
      $customer = Customer::findOrFail($invoice['customer_id']);
      $data = [
        'invoice' => $invoice,
        'customer' => $customer
      ];

      return response()->json($data);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
         $data = Invoice::findOrFail($id);
            if ($data->delete()) {
                $joborder = Joborder::where('invoice_id', $data['id']);
                $joborder->update([
                    'invoice_id' =>  null,
                    'kode_invoice' =>  null,
                ]);

                $konfirmasijo = KonfirmasiJo::where('invoice_id', $data['id']);

                $konfirmasijo->update([
                    'invoice_id' =>  null,
                    'kode_invoice' =>  null,
                    'status' =>  '0',
                ]);

            }
        DB::commit();
        $response = response()->json($this->responseDelete(true));
      } catch (Throwable $throw) {
        dd($throw);
        DB::rollBack();
        $response = response()->json($this->responseStore(false));
      }

      return $response;
    }


    public function pdf(Request $request)
    {

       $status_payment = $request['status_payment'];
       $customer_id =  $request['customer_id'];
       $id = $request['id'];
       $ppn = $request['ppn'];
       $tgl_invoice = $request['tgl_invoice'];
       $tgl_jatuh_tempo = $request['tgl_jatuh_tempo'];
       $tgl_awal = $request['tgl_awal'];
       $tgl_akhir = $request['tgl_akhir'];

        // dd( $tgl_akhir);
        $data = Invoice::with('customer','createdby')
         ->when($status_payment, function ($query, $status_payment) {
            return $query->where('status_payment',  $status_payment);
         })->when($customer_id, function ($query, $customer_id) {
            return $query->where('customer_id',  $customer_id);
         })->when($id, function ($query, $id) {
            return $query->where('id',  $customer_id);
         })->when($ppn, function ($query, $ppn) {
            return $query->where('ppn',  $ppn);
         })->when($tgl_invoice, function ($query, $tgl_invoice) {
            return $query->whereDate('tgl_invoice',  $tgl_invoice);
         })->when($tgl_jatuh_tempo, function ($query, $tgl_jatuh_tempo) {
            return $query->whereDate('tgl_jatuh_tempo',  $tgl_jatuh_tempo);
         })->when($tgl_awal, function ($query, $tgl_awal) {
                return $query->whereDate('tgl_invoice', '>=', $tgl_awal);
         })->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_invoice', '<=', $tgl_akhir);
         })->orderBy('kode_invoice', 'desc')->get();


                $data = [
                    'invoice' => $data,
                    'tgl_awal' => $request['tgl_awal'],
                    'tgl_akhir' => $request['tgl_akhir'],
                ];

        $pdf =  PDF::loadView('backend.invoice.report',  compact('data'));
        $fileName = 'Laporan-Invoice : '. $tgl_awal . '-SD-' .$tgl_akhir;
        $PAPER_F4 = array(0,0,609.4488,935.433);
        $pdf->setPaper( $PAPER_F4, 'landscape');
        $pdf->render();
        $font       = $pdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $pdf->get_canvas()->page_text(33, 590, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
        return $pdf->stream("${fileName}.pdf", array('Attachment' => false));
    }

    public function excel(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $status_payment = $request['status_payment'];
        $customer_id =  $request['customer_id'];
        $id = $request['id'];
        $ppn = $request['ppn'];
        $tgl_invoice = $request['tgl_invoice'];
        $tgl_jatuh_tempo = $request['tgl_jatuh_tempo'];
        $tgl_awal = $request['tgl_awal'];
        $tgl_akhir = $request['tgl_akhir'];

         // dd( $tgl_akhir);
         $data = Invoice::with('customer','createdby')
          ->when($status_payment, function ($query, $status_payment) {
             return $query->where('status_payment',  $status_payment);
          })->when($customer_id, function ($query, $customer_id) {
             return $query->where('customer_id',  $customer_id);
          })->when($id, function ($query, $id) {
             return $query->where('id',  $customer_id);
          })->when($ppn, function ($query, $ppn) {
             return $query->where('ppn',  $ppn);
          })->when($tgl_invoice, function ($query, $tgl_invoice) {
             return $query->whereDate('tgl_invoice',  $tgl_invoice);
          })->when($tgl_jatuh_tempo, function ($query, $tgl_jatuh_tempo) {
             return $query->whereDate('tgl_jatuh_tempo',  $tgl_jatuh_tempo);
          })->when($tgl_awal, function ($query, $tgl_awal) {
                 return $query->whereDate('tgl_invoice', '>=', $tgl_awal);
          })->when($tgl_akhir, function ($query, $tgl_akhir) {
             return $query->whereDate('tgl_invoice', '<=', $tgl_akhir);
          })->get();

         $sheet->setCellValue('A1', 'Laporan Invoice');
         $spreadsheet->getActiveSheet()->mergeCells('A1:N1');
         $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

         if($request['tgl_awal'] != null && $request['tgl_akhir'] != null){
            $spreadsheet->getActiveSheet()->mergeCells('A2:N2');
            $sheet->setCellValue('A2', 'Tanggal : '. date('d-m-Y', strtotime($request['tgl_awal'])) .' S/D '. date('d-m-Y', strtotime($request['tgl_akhir'])));
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
         }

        //  <th>No</th>
        //  <th class="text-center">Kode Invoice</th>
        //  <th>Tanggal Invoice</th>
        //  <th>Customer</th>
        //  <th>Total Tagihan</th>
        //  <th>Sisa Tagihan</th>
        //  <th>Batas Pembayaran</th>
        //  <th>Status Pembayaran</th>
        //  <th>Operator (Waktu)</th>


         $rows3 = 3;
         $sheet->setCellValue('A'.$rows3, 'Kode Invoice');
         $sheet->setCellValue('B'.$rows3, 'Tanggal Invoice');
         $sheet->setCellValue('C'.$rows3, 'Customer');
         $sheet->setCellValue('D'.$rows3, 'Total Tagihan');
         $sheet->setCellValue('E'.$rows3, 'Sisa Tagihan');
         $sheet->setCellValue('F'.$rows3, 'Batas Pembayaran');
         $sheet->setCellValue('G'.$rows3, 'Status Pembayaran');
         $sheet->setCellValue('H'.$rows3, 'Operator Waktu');

         for($col = 'A'; $col !== 'I'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}
         $x = 4;
         foreach($data as $val){
                $status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas');
                 $sheet->setCellValue('A' . $x, $val['kode_invoice']);
                 $sheet->setCellValue('B' . $x, $val['tgl_invoice']);
                 $sheet->setCellValue('C' . $x, $val['customer']['name'] ?? '');
                 $sheet->setCellValue('D' . $x, $val['total_harga'] ?? '');
                 $sheet->setCellValue('E' . $x, $val['sisa_tagihan'] ?? '');
                 $sheet->setCellValue('F' . $x, $val['tgl_jatuh_tempo'] ?? '');
                 $sheet->setCellValue('G' . $x, $status_payment ?? '');
                 $sheet->setCellValue('H' . $x, $val['createdby']->name . ' ( ' .date('d-m-Y', strtotime($val['created_at'])) .' )');
                 $x++;
         }
      $cell   = count($data) + 4;

      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total :');
      $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':C' . $cell . '');
      $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


      $spreadsheet->getActiveSheet()->getStyle('D4:D'.$cell)->getNumberFormat()->setFormatCode('#,##0');
      $spreadsheet->getActiveSheet()->getStyle('E4:E'.$cell)->getNumberFormat()->setFormatCode('#,##0');

      $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$cell, '=SUM(D3:D' . $cell . ')');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$cell, '=SUM(E3:E' . $cell . ')');

      $writer = new Xlsx($spreadsheet);
      $filename = 'Laporan Invoice';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');
    }


    public function sisapayment(Request $request)
    {

        $status_payment = $request['status_payment'];
        $customer_id =  $request['customer_id'];
        $id = $request['id'];
        $ppn = $request['ppn'];
        $tgl_invoice = $request['tgl_invoice'];
        $tgl_jatuh_tempo = $request['tgl_jatuh_tempo'];
        $tgl_awal = $request['tgl_awal'];
        $tgl_akhir = $request['tgl_akhir'];


        $data = Invoice::selectRaw('sum(sisa_tagihan) as belum_bayar')
         ->when($status_payment, function ($query, $status_payment) {
            return $query->where('status_payment',  $status_payment);
         })->when($customer_id, function ($query, $customer_id) {
            return $query->where('customer_id',  $customer_id);
         })->when($id, function ($query, $id) {
            return $query->where('id',  $customer_id);
         })->when($ppn, function ($query, $ppn) {
            return $query->where('ppn',  $ppn);
         })->when($tgl_invoice, function ($query, $tgl_invoice) {
            return $query->whereDate('tgl_invoice',  $tgl_invoice);
         })->when($tgl_jatuh_tempo, function ($query, $tgl_jatuh_tempo) {
            return $query->whereDate('tgl_jatuh_tempo',  $tgl_jatuh_tempo);
         })->when($tgl_awal, function ($query, $tgl_awal) {
                return $query->whereDate('tgl_invoice', '>=', $tgl_awal);
         })->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_invoice', '<=', $tgl_akhir);
         })->where('status_payment', '!=', '2')->first();
        //   dd(response()->json($results['data']->sortByDesc('urut')));
        return response()->json($data);

    }


}
