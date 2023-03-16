<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\KonfirmasiJo;
use App\Models\Customer;
use App\Models\Joborder;
use App\Traits\NoUrutTrait;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
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
                $show = '<a href="' . route('backend.invoice.show', $row->id) . '" class="dropdown-item">Detail</a>';
                $edit = '<a class="dropdown-item" href="invoice/' . $row->id . '/edit">Ubah</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';
                $payment = '<a href="' . route('backend.paymentinvoice.create',  ['invoice_id'=> $row->id]) . '" class="dropdown-item">Pembayaran</a>';
                $cicilan = '<a class="dropdown-item" href="paymentinvoice/' . $row->id . '/edit">Pelunasan</a>';

                $cek_payment = $row->status_payment == '0'  ? $payment : ($row->status_payment == '1'  ? $cicilan : '');
                $cek_edit =  $row->status_payment == '0' ? $edit : '';
                $cek_delete =  $row->status_payment == '0' ? $delete : '';

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
                </div>
            </div>';

            })
            ->rawColumns(['tgl_jatuh_tempo', 'action'])
            ->make(true);
        }

        return view('backend.invoice.index', compact('config', 'page_breadcrumbs'));
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
                  $kode =  $this->KodeInvoice(Carbon::now()->format('d M Y'));
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
            $data->update([
                'tgl_invoice'  => $request['tgl_invoice'],
                'tgl_jatuh_tempo' =>  $tgl_jatuh_tempo,
                'kode_invoice' =>  $data['kode_invoice'],
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
                'updated_by' => Auth::user()->id,
            ]);

            if(isset($data['id'])){

                $joborder_id = [];
                foreach($request['konfirmasijoid'] as $val){
                    $joborder_id[] = $val;
                }

                $cek_konfirmasijo = KonfirmasiJo::where([
                    ['invoice_id' , $data['id']],
                ])->whereNotIn('joborder_id', $joborder_id);


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

}
