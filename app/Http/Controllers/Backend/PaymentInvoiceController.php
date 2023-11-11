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
        $invoice = Invoice::with('customer')->find($request['invoice_id']);
        $data = [
          'invoice' => $invoice,
        ];
        if ($request->ajax()) {
          $data = PaymentInvoice::with('invoice');
          if ($request->filled('id')) {
            $data->where('invoice_id', $request['id']);
          }

          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $show = '<a href="' . route('backend.invoice.show', $row->invoice_id) . '" class="dropdown-item">Detail</a>';
                $edit = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                    data-bs-id="' . $row->id . '"
                    data-bs-nominal="' . $row->nominal . '"
                    data-bs-tgl_payment="' . $row->tgl_payment . '"
                    data-bs-keterangan="' . $row->keterangan . '"
                    data-bs-jenis_payment="' . $row->jenis_payment . '"
                    class="edit dropdown-item">Update</a>';

                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';
                $perm = [
                    'list' => Auth::user()->can('backend-paymentinvoice-list'),
                    'create' => Auth::user()->can('backend-paymentinvoice-create'),
                    'edit' => Auth::user()->can('backend-paymentinvoice-edit'),
                    'delete' => Auth::user()->can('backend-paymentinvoice-delete'),
                ];

                $cek_edit =  $row->invoice->status_payment != '2'  ? $edit : '';
                $cek_delete =  $row->invoice->status_payment != '2' ? $delete : '';

                $cek_perm_edit = $perm['edit'] == 'true' ? $cek_edit : '';
                $cek_perm_delete = $perm['delete'] == 'true' ? $cek_delete : '';

                $cek_level_edit = Auth::user()->roles()->first()->level == '1' ? $edit : '';
                $cek_level_delete = Auth::user()->roles()->first()->level == '1' ? $delete : '';

                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $show.'
                    '. $cek_level_edit .'
                    '. $cek_level_delete .'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.paymentinvoice.index', compact('config', 'page_breadcrumbs', 'data'));
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
                            'keterangan' => $val_payment['keterangan'],
                            'created_by' => Auth::user()->id
                        ]);
                        }
                    }

                    $total_sisa_tagihan = $invoice['total_harga'] - $total_payment ;

                    if( $total_sisa_tagihan < 0){
                        $response = response()->json([
                            'status' => 'error',
                            'message' => 'Nominal Melebihi Sisa Tagihan'
                        ]);
                    }else{
                        $status = $total_sisa_tagihan > 0 ? '1' : '2';

                        $invoice->update([
                            'total_payment'=> $total_payment,
                            'sisa_tagihan'=> $total_sisa_tagihan,
                            'status_payment'=> $status
                        ]);
                        DB::commit();
                        $response = response()->json($this->responseStore(true, route('backend.paymentinvoice.index')));
                    }



                //   $kode =  $this->KodeRute(Carbon::now()->format('d M Y'));


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

                        $paymentinvoice = PaymentInvoice::find($val['id']);

                        $payment = PaymentInvoice::updateOrCreate([
                            'id' => $val['id']
                        ],[
                            'invoice_id' =>  $invoice['id'],
                            'kode_invoice' => $invoice['kode_invoice'],
                            'tgl_payment' => $paymentinvoice['tgl_payment'] ?? $request['tgl_pembayaran'],
                            'nominal' => number_format($val['nominal'], 3, '.', ''),
                            'jenis_payment' => $val['jenis_pembayaran'],
                            'keterangan' => $val['keterangan'],
                            'created_by' => ($val['id'] == '' || $val['id'] == null || $val['id'] == 'undefined') ? Auth::user()->id : $paymentinvoice['created_by']
                        ]);
                        $payment_id[] = $payment['id'];

                        if(!$payment->wasRecentlyCreated && !$payment->wasChanged()){
                            $payment->disableLogging();
                        }
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

                  if( $total_tagihan < 0){
                    $response = response()->json([
                        'status' => 'error',
                        'message' => 'Nominal Melebihi Sisa Tagihan'
                    ]);
                  }else{
                    $status = $total_tagihan > 0 ? '1' : '2';
                    //   dd($total_sisa_uang_jalan);
                      $invoice->update([
                        'total_payment'=> $total_payment,
                        'sisa_tagihan'=> $total_tagihan,
                        'status_payment'=> $status
                      ]);

                     DB::commit();
                     $response = response()->json($this->responseStore(true, route('backend.paymentinvoice.index')));
                  }


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

    public function updatesingle(Request $request)
    {
          $validator = Validator::make($request->all(), [

            'nominal' => "required",
            'jenis_payment'  => "required"
          ]);

          DB::beginTransaction();
          if ($validator->passes()) {
            try {

                 $paymentinvoice = PaymentInvoice::findOrFail($request['id']);
                //  dd($paymentinvoice);
                 $invoice = Invoice::findOrFail( $paymentinvoice['invoice_id']);

                $old_pay = $invoice['total_payment'] - $paymentinvoice['nominal'];
                $total_payment =   $old_pay + $request['nominal'];

                //  dd($old_pay, $total_payment,  $request['nominal']);
                    $paymentinvoice->update([
                        'nominal' => $request['nominal'],
                        'tgl_payment' => $request['tgl_pembayaran'],
                        'jenis_payment' => $request['jenis_payment'],
                        'keterangan' => $request['keterangan'],
                        'updated_by' => Auth::user()->id
                    ]);

                    // dd($total_payment);

                $total_invoice = $invoice['total_harga'] - $total_payment;

         //       dd( $total_invoice, $invoice['total_harga'], $total_payment);
              //  dd($total_sisa_uang_jalan, $total_payment, $total_kasbon);
                if( $total_invoice < 0 ){
                    $response = response()->json([
                        'status' => 'error',
                        'message' => 'Nominal Melebihi Sisa Tagihan'
                    ]);
                }else{

                    $status =  $total_invoice == $invoice['total_harga'] ? '0' : '1';
                    $status =  $total_invoice <= 0 ? '2' : $status;
                    // dd($total_sisa_uang_jalan);
                    $invoice->update([
                        'total_payment'=> $total_payment,
                        'sisa_tagihan'=> $total_invoice,
                        'status_payment'=> $status
                    ]);

                    DB::commit();
                    $response = response()->json($this->responseStore(true));
                }


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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
         $data = PaymentInvoice::findOrFail($id);
            if ($data->delete()) {
                $invoice = Invoice::where('id', $data['invoice_id'])->first();

                $total_payment = $invoice['total_payment'] - $data['nominal'];

                $total_sisa_invoice = $invoice['sisa_tagihan'] + $data['nominal'];
                $status =  $total_sisa_invoice == $invoice['total_harga'] ? '0' : '1';



                $invoice->update([
                    'total_payment'=> $total_payment,
                    'sisa_tagihan'=>  $total_sisa_invoice,
                    'status_payment'=> $status
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

}
