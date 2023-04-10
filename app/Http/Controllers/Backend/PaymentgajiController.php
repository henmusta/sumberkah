<?php

namespace App\Http\Controllers\Backend;


use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\PaymentGaji;
use App\Models\Penggajian;
use App\Traits\NoUrutTrait;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;


class PaymentGajiController extends Controller
{
    use ResponseStatus,NoUrutTrait;
    function __construct()
    {
      $this->middleware('can:backend-paymentgaji-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-paymentgaji-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-paymentgaji-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-paymentgaji-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Payment Gaji";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Payment Gaji"],
        ];

        $penggajian = Penggajian::find($request['penggajian_id']);
        $data = [
          'gaji' => $penggajian
        ];
        if ($request->ajax()) {
          $data = PaymentGaji::selectRaw('payment_gaji.*')->with('penggajian');

          if ($request->filled('id')) {
            $data->where('penggajian_id', $request['id']);
          }
          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $show = '<a href="' . route('backend.penggajian.show', $row->penggajian_id) . '" class="dropdown-item">Detail</a>';

                $edit = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                    data-bs-id="' . $row->id . '"
                    data-bs-nominal="' . $row->nominal . '"
                    data-bs-tgl_payment="' . $row->tgl_payment . '"
                    data-bs-keterangan="' . $row->keterangan . '"
                    data-bs-jenis_payment="' . $row->jenis_payment . '"
                    class="edit dropdown-item">Update</a>';

                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';
                $perm = [
                    'list' => Auth::user()->can('backend-paymentgaji-list'),
                    'create' => Auth::user()->can('backend-paymentgaji-create'),
                    'edit' => Auth::user()->can('backend-paymentgaji-edit'),
                    'delete' => Auth::user()->can('backend-paymentgaji-delete'),
                ];

                $cek_edit =  $row->penggajian->status_payment != '2'  ? $edit : '';
                $cek_delete =  $row->penggajian->status_payment != '2' ? $delete : '';

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
                    '.  $cek_level_edit .'
                    '.  $cek_level_delete .'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.paymentgaji.index', compact('config', 'page_breadcrumbs', 'data'));
    }


    public function create(Request $request)
    {

      $config['page_title'] = "Tambah Payment Gaji";
      $page_breadcrumbs = [
        ['url' => route('backend.paymentgaji.index'), 'title' => "Daftar Gaji"],
        ['url' => '#', 'title' => "Tambah Gaji"],
      ];

      $gaji = Penggajian::find($request['penggajian_id']);
      $data = [
        'gaji' => $gaji,
      ];

      return view('backend.paymentgaji.create', compact('page_breadcrumbs', 'config', 'data'));
    }

    public function store(Request $request)
    {
        // dd($request);

          $validator = Validator::make($request->all(), [
            'total_payment' => "required",
            'penggajian_id' => "required",
            'tgl_pembayaran'  => "required",
            'payment'  => "required"
          ]);



          if ($validator->passes()) {
            $penggajian = Penggajian::findOrFail($request['penggajian_id']);
            // dd($penggajian);
            DB::beginTransaction();
            try {
             $total_payment = 0;
                    if(isset($request['payment'])){
                        foreach($request['payment'] as $val_payment){
                        $total_payment += $val_payment['nominal'];
                        $payment = PaymentGaji::create([
                            'penggajian_id' =>  $penggajian['id'],
                            'kode_gaji' => $penggajian['kode_gaji'],
                            'tgl_payment' => $request['tgl_pembayaran'],
                            'nominal' => $val_payment['nominal'],
                            'jenis_payment' => $val_payment['jenis_pembayaran'],
                            'keterangan' => $val_payment['keterangan']
                        ]);
                        }
                    }

                    $total_sisa_tagihan = $penggajian['total_gaji'] - $total_payment ;

                    $status = $total_sisa_tagihan > 0 ? '1' : '2';

                    $penggajian->update([
                        'total_payment'=> $total_payment,
                        'sisa_gaji'=> $total_sisa_tagihan,
                        'status_payment'=> $status
                    ]);

                //   $kode =  $this->KodeRute(Carbon::now()->format('d M Y'));

              DB::commit();
              $response = response()->json($this->responseStore(true, route('backend.paymentgaji.index')));
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
          ['url' => route('backend.paymentgaji.index'), 'title' => "Daftar Paymentgaji"],
          ['url' => '#', 'title' => "Update Paymentgaji"],
        ];

        $penggajian = Penggajian::findOrFail($id);
        $payment = PaymentGaji::where('penggajian_id', $penggajian['id'])->get();

        $data = [
          'gaji' => $penggajian,
          'payment' => $payment
        ];

        return view('backend.paymentgaji.edit', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function update(Request $request, $id)
    {
          $validator = Validator::make($request->all(), [
            'total_payment' => "required",
            'penggajian_id' => "required",
            'tgl_pembayaran'  => "required",
            'payment'  => "required"
          ]);


          if ($validator->passes()) {
            try {
                DB::beginTransaction();
                  $penggajian = Penggajian::findOrFail($id);
                //kasbon
                $total_payment = 0;
                  if(isset($request['payment'])){
                    $payment_id = array();
                    foreach($request['payment'] as $val){
                        $total_payment += $val['nominal'];

                        $payment_gaji = PaymentGaji::find($val['id']);
                        $payment = PaymentGaji::updateOrCreate([
                            'id' => $val['id']
                        ],[
                            'penggajian_id' =>  $penggajian['id'],
                            'kode_gaji' => $penggajian['kode_gaji'],
                            'tgl_payment' => $payment_gaji['tgl_payment'] ?? $request['tgl_pembayaran'],
                            'nominal' => $val['nominal'],
                            'jenis_payment' => $val['jenis_pembayaran'],
                            'keterangan' => $val['keterangan']
                        ]);
                        $payment_id[] = $payment['id'];
                    }
                    // dd($payment_id );
                    $cek_payment = PaymentGaji::where([
                        ['penggajian_id' , $penggajian['id']],
                    ])->whereNotIn('id', $payment_id);


                    if(isset($cek_payment)){
                        $cek_payment->delete();
                    }
                  }


                  $total_tagihan = $penggajian['total_gaji'] - $total_payment;

                  $status = $total_tagihan > 0 ? '1' : '2';
                //   dd($total_sisa_uang_jalan);
                  $penggajian->update([
                    'total_payment'=> $total_payment,
                    'sisa_gaji'=> $total_tagihan,
                    'status_payment'=> $status
                  ]);

                 DB::commit();
                 $response = response()->json($this->responseStore(true, route('backend.paymentgaji.index')));
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
            // 'total_kasbon' => "required",
            // 'total_payment' => "required",
            // 'joborder_id' => "required",
            // 'tgl_pembayaran'  => "required",
            // 'payment'  => "required"
          ]);

          DB::beginTransaction();
          if ($validator->passes()) {
            try {

                 $paymentgaji = PaymentGaji::findOrFail($request['id']);
                 $penggajian = Penggajian::findOrFail( $paymentgaji['penggajian_id']);


                    $total_payment =  ($penggajian['total_payment'] - $paymentgaji['nominal']) + $request['nominal'];


                    $paymentgaji->update([
                        'nominal' => $request['nominal'],
                        'tgl_payment' => $request['tgl_pembayaran'],
                        'jenis_payment' => $request['jenis_payment'],
                        'keterangan' => $request['keterangan']
                    ]);


                $total_gaji = $penggajian['total_gaji'] - $total_payment;
              //  dd($total_sisa_uang_jalan, $total_payment, $total_kasbon);
                if( $total_gaji < 0 ){
                    $response = response()->json([
                        'status' => 'error',
                        'message' => 'Nominal Melebihi Sisa Tagihan'
                    ]);
                }else{

                    $status =  $total_gaji == $penggajian['total_gaji'] ? '0' : '1';
                    $status =  $total_gaji <= 0 ? '2' : $status;
                    // dd($total_sisa_uang_jalan);
                    $penggajian->update([
                        'total_payment'=> $total_payment,
                        'sisa_gaji'=> $total_gaji,
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
         $data = PaymentGaji::findOrFail($id);
            if ($data->delete()) {
                $penggajian = Penggajian::where('id', $data['penggajian_id'])->first();

                $total_payment = $penggajian['total_payment'] - $data['nominal'];

                $total_sisa_gaji = $penggajian['sisa_gaji'] + $data['nominal'];
                $status =  $total_sisa_gaji == $penggajian['total_gaji'] ? '0' : '1';



                $penggajian->update([
                    'total_payment'=> $total_payment,
                    'sisa_gaji'=>  $total_sisa_gaji,
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
