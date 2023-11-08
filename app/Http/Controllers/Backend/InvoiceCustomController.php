<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
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

class InvoiceCustomController extends Controller
{
    use ResponseStatus,NoUrutTrait;
    public function create(Request $request)
    {

      $config['page_title'] = "Tambah Invoice Custom";
      $page_breadcrumbs = [
        ['url' => route('backend.invoice.index'), 'title' => "Data Invoice v"],
        ['url' => '#', 'title' => "Tambah Invoice Custom"],
      ];
      return view('backend.invoicecustom.create', compact('page_breadcrumbs', 'config'));
    }

    public function store(Request $request)
    {
        // dd($request);

          $validator = Validator::make($request->all(), [
            'tgl_invoice'  => "required",
            'ppn'  =>  "required_if:ppn,!=,Tidak",
            'nominal_ppn'  => "required",
            'sub_total'  => "required",
            'total_harga'  => "required",
            'detail'  => "required",
          ]);
        //   dd($request);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                //   $tgl_jatuh_tempo = Carbon::createFromFormat('Y-m-d',  $request['tgl_invoice'])->addDays($request['payment_hari'])->format('Y-m-d');

                  $kode =  $this->KodeInvoice(Carbon::parse($request['tgl_invoice'])->format('d M Y'));
                  $data = Invoice::create([
                    'tgl_invoice'  => $request['tgl_invoice'],
                    'kode_invoice' =>  $kode,
                    'customer_id' =>  '19',
                    'ppn'  => $request['ppn'],
                    'nominal_ppn'  => $request['nominal_ppn'],
                    'sub_total'  => $request['sub_total'],
                    'total_harga'  => $request['total_harga'],
                    'keterangan_invoice'  => $request['keterangan_invoice'],
                    'created_by' => Auth::user()->id,
                    'jenis' => 'custom',
                    // 'konfirmasijoid'  => $request['konfirmasijoid'],
                  ]);

                  if(isset($data['id'])){
                    foreach($request['detail'] as $val){

                        $detail = InvoiceDetail::create([
                            'invoice_id' => $data['id'],
                            'nominal'  => $val['nominal'],
                            'keterangan'  => $val['keterangan'],
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

    public function edit($id)
    {
        $config['page_title'] = "Update Invoice Custom";

        $page_breadcrumbs = [
          ['url' => route('backend.invoice.index'), 'title' => "Daftar Invoice"],
          ['url' => '#', 'title' => "Update Invoice"],
        ];
        $invoice = Invoice::with('customer')->findOrFail($id);
        $invoicedetail = InvoiceDetail::with('invoice')->where('invoice_id', $id)->get();
        $data = [
          'invoice' => $invoice,
          'invoicedetail' => $invoicedetail,
        ];

        return view('backend.invoicecustom.edit', compact('page_breadcrumbs', 'config', 'data'));
    }

    public function update(Request $request, $id)
    {
          $validator = Validator::make($request->all(), [
            'tgl_invoice'  => "required",
            'ppn'  =>  "required_if:ppn,!=,Tidak",
            'nominal_ppn'  => "required",
            'sub_total'  => "required",
            'total_harga'  => "required",
            'detail'  => "required",
          ]);


          if ($validator->passes()) {
            try {
                DB::beginTransaction();
                  $invoice = Invoice::findOrFail($id);

                  if(isset($request['detail'])){
                    $detail_id = array();
                    foreach($request['detail'] as $val){
                        // dd($val);
                        // $total_payment += $val['nominal'];
                        // $payment_gaji = PaymentGaji::find($val['id']);
                        $detail = InvoiceDetail::updateOrCreate([
                            'id' => $val['id']
                        ],[
                            'invoice_id' =>  $invoice['id'],
                            'nominal'  => $val['nominal'],
                            'keterangan'  => $val['keterangan'],
                        ]);
                        $detail_id[] = $detail['id'];
                    }
                    // dd($payment_id );
                    $cek_detail = InvoiceDetail::where([
                        ['invoice_id' , $invoice['id']],
                    ])->whereNotIn('id',  $detail_id);


                    if(count($cek_detail->get()) > 0){
                        $cek_detail->delete();
                    }
                  }
                  $kode =  $this->KodeInvoice(Carbon::parse($request['tgl_invoice'])->format('d M Y'));
                  $cek_kode = $request['tgl_invoice'] != $invoice['tgl_invoice'] ? $kode : $invoice['tgl_invoice'];

                  $invoice->update([
                    'tgl_invoice'  => $request['tgl_invoice'],
                    'kode_invoice' => $cek_kode,
                    'ppn'  => $request['ppn'],
                    'nominal_ppn'  => $request['nominal_ppn'],
                    'sub_total'  => $request['sub_total'],
                    'total_harga'  => $request['total_harga'],
                    'keterangan_invoice'  => $request['keterangan_invoice'],
                    'updated_by' => Auth::user()->id,
                  ]);

                 DB::commit();
                 $response = response()->json($this->responseStore(true, route('backend.invoice.index')));


            }catch (Throwable $throw) {
                dd($throw);
                DB::rollBack();
                $response = response()->json([
                    'status' => 'error',
                    'message' => 'Ada Kesalahan'
                ]);
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
          ['url' => route('backend.invoice.index'), 'title' => "Detail Invoice"],
          ['url' => '#', 'title' => "Detail Invoice"],
        ];
        $invoice = Invoice::with('customer')->findOrFail($id);
        $invoicedetail = InvoiceDetail::with('invoice')->where('invoice_id', $id)->get();
        $data = [
          'invoice' => $invoice,
          'detail' => $invoicedetail,
        ];
        return view('backend.invoicecustom.show', compact('page_breadcrumbs', 'config', 'data'));
    }


}
