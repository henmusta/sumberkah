<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\PaymentInvoice;
use App\Models\Invoice;
use App\Models\Joborder;
use App\Models\Driver;
use App\Models\Kasbon;
use App\Traits\NoUrutTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class PaymentInvoiceController extends Controller
{
    use ResponseStatus,NoUrutTrait;
    function __construct()
    {
      $this->middleware('can:backend-paymentinvoice-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-paymentinvoice-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-paymentinvoice-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-paymentinvoice-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Payment Invoice";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Payment Invoice"],
        ];
        if ($request->ajax()) {
          $data = PaymentInvoice::with('invoice');
          if ($request->filled('id')) {
            $data->where('invoice_id', $request['id']);
          }

          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $show = '<a href="' . route('backend.driver.show', $row->id) . '" class="dropdown-item">Detail</a>';
                $edit = '<a class="dropdown-item" href="driver/' . $row->id . '/edit">Ubah</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';
                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $show.'
                    '. $edit.'
                    '. $delete.'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.paymentinvoice.index', compact('config', 'page_breadcrumbs'));
    }


    public function create(Request $request)
    {

      $config['page_title'] = "Tambah Payment Invoice";
      $page_breadcrumbs = [
        ['url' => route('backend.paymentinvoice.index'), 'title' => "Daftar Invoice"],
        ['url' => '#', 'title' => "Tambah Invoice"],
      ];

      $invoice = Invoice::find($request['invoice_id']);
      $data = [
        'invoice' => $invoice,
      ];

      return view('backend.paymentinvoice.create', compact('page_breadcrumbs', 'config', 'data'));
    }

    public function store(Request $request)
    {
        // dd($request);

          $validator = Validator::make($request->all(), [
            'total_payment' => "required",
            'invoice_id' => "required",
            'tgl_pembayaran'  => "required",
            'payment'  => "required"
          ]);



          if ($validator->passes()) {
            $invoice = Invoice::findOrFail($request['invoice_id']);
            DB::beginTransaction();
            try {
             $total_payment = 0;
                    if(isset($request['payment'])){
                        foreach($request['payment'] as $val_payment){
                        $total_payment += $val_payment['nominal'];
                        $payment = PaymentInvoice::create([
                            'invoice_id' =>  $invoice['id'],
                            'kode_invoice' => $invoice['kode_invoice'],
                            'tgl_payment' => $request['tgl_pembayaran'],
                            'nominal' => $val_payment['nominal'],
                            'jenis_payment' => $val_payment['jenis_pembayaran'],
                            'keterangan' => $val_payment['keterangan']
                        ]);
                        }
                    }

                    $total_sisa_tagihan = $invoice['total_harga'] - $total_payment ;

                    $status = $total_sisa_tagihan > 0 ? '1' : '2';

                    $invoice->update([
                        'total_payment'=> $total_payment,
                        'sisa_tagihan'=> $total_sisa_tagihan,
                        'status_payment'=> $status
                    ]);

                //   $kode =  $this->KodeRute(Carbon::now()->format('d M Y'));

              DB::commit();
              $response = response()->json($this->responseStore(true, route('backend.paymentinvoice.index')));
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
        $config['page_title'] = "Update Payment";

        $page_breadcrumbs = [
          ['url' => route('backend.paymentinvoice.index'), 'title' => "Daftar Paymentjo"],
          ['url' => '#', 'title' => "Update Paymentjo"],
        ];

        $invoice = Invoice::findOrFail($id);
        $payment = PaymentInvoice::where('invoice_id', $invoice['id'])->get();

        $data = [
          'invoice' => $invoice,
          'payment' => $payment
        ];

        return view('backend.paymentinvoice.edit', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function update(Request $request, $id)
    {
          $validator = Validator::make($request->all(), [
            'total_payment' => "required",
            'invoice_id' => "required",
            'tgl_pembayaran'  => "required",
            'payment'  => "required"
          ]);


          if ($validator->passes()) {
            try {
                DB::beginTransaction();
                  $invoice = Invoice::findOrFail($id);
                //kasbon
                $total_payment = 0;
                  if(isset($request['payment'])){
                    $payment_id = array();
                    foreach($request['payment'] as $val){
                        $total_payment += $val['nominal'];
                        $payment = PaymentInvoice::updateOrCreate([
                            'id' => $val['id']
                        ],[
                            // 'joborder_id' => $joborder['id'],
                            // 'kode_joborder' => $joborder['kode_joborder'],
                            // 'tgl_payment' => $request['tgl_pembayaran'],
                            // 'jenis_payment' => $val['jenis_pembayaran'],
                            // 'keterangan' => $val['keterangan'],
                            // 'nominal' => $val['nominal'],
                            'invoice_id' =>  $invoice['id'],
                            'kode_invoice' => $invoice['kode_invoice'],
                            'tgl_payment' => $request['tgl_pembayaran'],
                            'nominal' => $val['nominal'],
                            'jenis_payment' => $val['jenis_pembayaran'],
                            'keterangan' => $val['keterangan']
                        ]);
                        $payment_id[] = $payment['id'];
                    }
                    // dd($payment_id );
                    $cek_payment = PaymentInvoice::where([
                        ['invoice_id' , $invoice['id']],
                    ])->whereNotIn('id', $payment_id);


                    if(isset($cek_payment)){
                        $cek_payment->delete();
                    }
                  }


                  $total_tagihan = $invoice['total_harga'] - $total_payment;

                  $status = $total_tagihan > 0 ? '1' : '2';
                //   dd($total_sisa_uang_jalan);
                  $invoice->update([
                    'total_payment'=> $total_payment,
                    'sisa_tagihan'=> $total_tagihan,
                    'status_payment'=> $status
                  ]);

                 DB::commit();
                 $response = response()->json($this->responseStore(true, route('backend.paymentinvoice.index')));
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


}
