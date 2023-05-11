<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\PaymentJo;
use App\Models\Driver;
use App\Models\Driverlogkasbon;
use App\Models\Joborder;
use App\Models\Kasbonjurnallog;
use Illuminate\Support\Facades\Auth;
use App\Models\Kasbon;
use App\Traits\NoUrutTrait;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;

use Throwable;
class PaymentJoController extends Controller
{
    use ResponseStatus,NoUrutTrait;
    function __construct()
    {
      $this->middleware('can:backend-paymentjo-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-paymentjo-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-paymentjo-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-paymentjo-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Payment Joborder";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Payment Joborder"],
        ];

        $joborder = Joborder::find($request['joborder_id']);
        $data = [
          'joborder' => $joborder,
        ];
        if ($request->ajax()) {
          $data = PaymentJo::with('joborder');
          if ($request->filled('id')) {
            $data->where('joborder_id', $request['id']);
        }

          return DataTables::of($data)
          ->addColumn('action', function ($row) {
            $print = '<a href="'. route('backend.paymentjo.pdf', $row->id) . '" class="edit dropdown-item" target="_blank">Cetak</a>';
            $show = '<a href="' . route('backend.joborder.show', $row->joborder_id) . '" class="dropdown-item">Detail</a>';
            $validasi = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalValidasi" data-bs-id="' . $row->id . '"  data-bs-validasi="' . $row->validasi. '" class="edit dropdown-item">Validasi</a>';
            $edit = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                data-bs-id="' . $row->id . '"
                data-bs-nominal="' . $row->nominal . '"
                data-bs-tgl_payment="' . $row->tgl_payment . '"
                data-bs-keterangan="' . $row->keterangan . '"
                data-bs-nominal_kasbon="' . $row->nominal_kasbon . '"
                data-bs-keterangan_kasbon="' . $row->keterangan_kasbon . '"
                data-bs-jenis_payment="' . $row->jenis_payment . '"
                class="edit dropdown-item">Update</a>';

            $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';

            $perm = [
                    'list' => Auth::user()->can('backend-paymentjo-list'),
                    'create' => Auth::user()->can('backend-paymentjo-create'),
                    'edit' => Auth::user()->can('backend-paymentjo-edit'),
                    'delete' => Auth::user()->can('backend-paymentjo-delete'),
            ];



            $cek_edit =  $row->joborder->status_payment != '2'  ? $edit : '';
            $cek_delete =  $row->joborder->status_payment != '2' ? $delete : '';

            $cek_perm_edit = $perm['edit'] == 'true' ? $cek_edit : '';
            $cek_perm_delete = $perm['delete'] == 'true' ? $cek_delete : '';

            $cek_level_edit = Auth::user()->roles()->first()->level == '1'  && $row->joborder->status_joborder == '0' ? $edit : '';
            $cek_level_delete = Auth::user()->roles()->first()->level == '1' && $row->joborder->status_joborder == '0' ? $delete : '';

            return '<div class="dropdown">
            <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                Aksi <i class="mdi mdi-chevron-down"></i>
            </a>
            <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                '. $show.'
                '. $cek_level_edit .'
                '.  $cek_level_delete .'
                '.  $print .'
            </div>
        </div>';

        })
            // ->addColumn('action', function ($row) {
            //     $show = '<a href="' . route('backend.driver.show', $row->id) . '" class="dropdown-item">Detail</a>';
            //     // $edit = '<a class="dropdown-item" href="driver/' . $row->id . '/edit">Ubah</a>';
            //     return '<a href="" class="btn btn-success"><i class="fa fa-print"></i></a>';

            // })
            ->make(true);
        }

        return view('backend.paymentjo.index', compact('config', 'page_breadcrumbs', 'data'));
    }


    public function create(Request $request)
    {

      $config['page_title'] = "Tambah Payment";
      $page_breadcrumbs = [
        ['url' => route('backend.paymentjo.index'), 'title' => "Daftar Payment"],
        ['url' => '#', 'title' => "Tambah Payment"],
      ];

      $joborder = Joborder::find($request['joborder_id']);
      $data = [
        'joborder' => $joborder,
      ];

      return view('backend.paymentjo.create', compact('page_breadcrumbs', 'config', 'data'));
    }

    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
            'total_kasbon' => "required",
            'total_payment' => "required",
            'joborder_id' => "required",
            'tgl_pembayaran'  => "required",
            'payment'  => "required"
          ]);

          if ($validator->passes()) {
            $joborder = Joborder::findOrFail($request['joborder_id']);
            // dd(joborder['driver_id']);
            DB::beginTransaction();
            try {
             $total_payment = 0;
             $total_kasbon = 0;
            if(isset($request['payment'])){
                foreach($request['payment'] as $val_payment){
                   $total_payment += $val_payment['nominal'];
                   $total_kasbon += $val_payment['nominal_kasbon'];
                   if($val_payment['nominal_kasbon'] > 0){
                    $kode =  $this->KodeKasbon(Carbon::parse($request['tgl_pembayaran'])->format('d M Y'));
                    $kasbon = Kasbon::create([
                      'joborder_id' =>  $joborder['id'],
                      'driver_id' => $joborder['driver_id'],
                      'kode_kasbon'=> $kode,
                      'jenis'=> 'Potong Joborder',
                      'tgl_kasbon'=> $request['tgl_pembayaran'],
                      'keterangan'=> $val_payment['keterangan'],
                      'nominal'=> $val_payment['nominal_kasbon'],
                      'validasi' =>  '1'
                    ]);

                   $kasbonjurnallog = Kasbonjurnallog::create([
                      'kasbon_id' =>   $kasbon['id'],
                      'joborder_id' =>  $kasbon['joborder_id'],
                      'driver_id' =>  $kasbon['driver_id'],
                      'kode_kasbon'=>  $kasbon['kode_kasbon'],
                      'jenis'=>  $kasbon['jenis'],
                      'tgl_kasbon'=>  $kasbon['tgl_kasbon'],
                      'keterangan'=> $kasbon['keterangan'],
                      'debit'=> $kasbon['nominal'],
                      'kredit'=> '0'
                    ]);

                      $driver_total_kasbon = 0;
                      $driver = Driver::findOrFail($joborder['driver_id']);
                      if($driver['kasbon'] >= $kasbon['nominal']){
                          $driver_total_kasbon = $driver['kasbon'] - $kasbon['nominal'];
                        //   dd($driver_total_kasbon);
                          $driver->update([
                              'kasbon'=> $driver_total_kasbon,
                          ]);

                      }else{
                          $response = response()->json([
                              'error' => true,
                              'message' => 'Potongan Kasbon Melebihi Jumlah Kasbon Tersedia'
                          ]);
                      }
                }
                   $payment = PaymentJo::create([
                    'kasbon_id' =>   $kasbon['id'] ?? null,
                    'joborder_id' =>  $joborder['id'],
                    'kode_joborder' => $joborder['kode_joborder'],
                    'tgl_payment' => $request['tgl_pembayaran'],
                    'nominal' => $val_payment['nominal'],
                    'nominal_kasbon' => $val_payment['nominal_kasbon'],
                    'jenis_payment' => $val_payment['jenis_pembayaran'],
                    'keterangan_kasbon' => $val_payment['keterangan_kasbon'],
                    'keterangan' => $val_payment['keterangan']
                  ]);
                  $Driverlogkasbon = Driverlogkasbon::create([
                    'joborder_id' =>  $request['joborder_id'],
                    'driver_id' =>  $joborder['driver_id'],
                    'nominal' =>  $val_payment['nominal_kasbon'],
                    'payment_joborder_id' => $payment['id']
                  ]);

                  //end cek nominal kasbon
                }


            }

            $total_sisa_uang_jalan = $joborder['total_uang_jalan'] - $total_payment - $total_kasbon;

                if( $total_sisa_uang_jalan < 0 ){
                    $response = response()->json([
                        'error' => true,
                        'message' => 'Sisa Uang Jalan Minus'
                    ]);
                }else{
                    $status = $total_sisa_uang_jalan > 0 ? '1' : '2';

                    $joborder->update([
                        'total_kasbon'=> $total_kasbon,
                        'total_payment'=> $total_payment,
                        'sisa_uang_jalan'=> $total_sisa_uang_jalan,
                        'status_payment'=> $status
                    ]);
                        //   $kode =  $this->KodeRute(Carbon::now()->format('d M Y'));
                    DB::commit();
                    $response = response()->json($this->responseStore(true, route('backend.paymentjo.index')));
                }

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
          ['url' => route('backend.paymentjo.index'), 'title' => "Daftar Paymentjo"],
          ['url' => '#', 'title' => "Update Paymentjo"],
        ];

        $joborder = Joborder::with('driver')->findOrFail($id);
        $payment = PaymentJo::where('joborder_id', $joborder['id'])->get();
        $kasbon = Kasbon::where('joborder_id', $joborder['id'])->get();

        $data = [
          'joborder' => $joborder,
          'payment' => $payment,
          'kasbon' => $kasbon
        ];

        return view('backend.paymentjo.edit', compact('page_breadcrumbs', 'config', 'data'));
    }

    public function update(Request $request, $id)
    {
          $validator = Validator::make($request->all(), [
            'total_kasbon' => "required",
            'total_payment' => "required",
            'joborder_id' => "required",
            'tgl_pembayaran'  => "required",
            'payment'  => "required"
          ]);
          if ($validator->passes()) {
            try {
                DB::beginTransaction();
                  $joborder = Joborder::findOrFail($id);
                //kasbon
                  $total_payment = 0;
                  $total_kasbon = 0;
                  $first_kasbon =  $joborder['total_kasbon'];
                  if(isset($request['payment'])){
                    $payment_id = array();
                    $kasbon_id = array();
                    foreach($request['payment'] as $val){
                        $total_payment += $val['nominal'];


                        $total_kasbon += $val['nominal_kasbon'];
                        if($val['nominal_kasbon'] > 0){
                            $kode =  $this->KodeKasbon(Carbon::parse($request['tgl_pembayaran'])->format('d M Y'));
                            $kasbon = Kasbon::find($val['kasbon_id']);
                            $kasbon = Kasbon::updateOrCreate([
                                'id' => $val['kasbon_id']
                            ],[
                                'joborder_id' =>  $joborder['id'],
                                'kode_kasbon'=>  $kasbon['kode_kasbon'] ?? $kode,
                                'driver_id' => $joborder['driver_id'],
                                'tgl_kasbon'=>  $kasbon['tgl_kasbon'] ?? $request['tgl_pembayaran'],
                                'jenis'=> 'Potong Joborder',
                                'keterangan'=> $val['keterangan_kasbon'],
                                'nominal'=> $val['nominal_kasbon'],
                                'validasi' =>  '1'
                            ]);
                            $kasbon_id[] = $kasbon['id'];

                             if(isset($kasbon['id'])){
                                $kasbonjurnallog = Kasbonjurnallog::updateOrCreate([
                                     'kasbon_id' => $kasbon['id']
                                ],[
                                    'kasbon_id' =>   $kasbon['id'],
                                    'joborder_id' =>  $kasbon['joborder_id'],
                                    'driver_id' =>  $kasbon['driver_id'],
                                    'kode_kasbon'=>  $kasbon['kode_kasbon'],
                                    'jenis'=>  $kasbon['jenis'],
                                    'tgl_kasbon'=>  $kasbon['tgl_kasbon'],
                                    'keterangan'=> $kasbon['keterangan'],
                                    'debit'=> $kasbon['nominal'],
                                    'kredit'=> '0'
                                ]);
                             }

                        }


                        $paymentjoborder = PaymentJo::find($val['id']);

                        $payment = PaymentJo::updateOrCreate([
                            'id' => $val['id']
                        ],[
                            'kasbon_id' =>   $kasbon['id'] ?? null,
                            'joborder_id' => $joborder['id'],
                            'kode_joborder' => $joborder['kode_joborder'],
                            'tgl_payment' => $paymentjoborder['tgl_payment'] ?? $request['tgl_pembayaran'],
                            'jenis_payment' => $val['jenis_pembayaran'],
                            'nominal_kasbon' => $val['nominal_kasbon'],
                            'keterangan' => $val['keterangan'],
                            'keterangan_kasbon' => $val['keterangan_kasbon'],
                            'nominal' => $val['nominal'],
                        ]);
                        $payment_id[] = $payment['id'];
                        $cek_kasbon = Kasbon::where([
                            ['joborder_id' , $joborder['id']],
                        ])->whereNotIn('id', $kasbon_id);

                        // $kasbon_driver = 0;
                        if(isset($cek_kasbon)){
                            $cek_kasbon->delete();
                        }

                        $Driverlogkasbon = Driverlogkasbon::updateOrCreate([
                            'payment_joborder_id' => $payment['id']
                        ],[
                            'joborder_id' =>  $joborder['id'],
                            'driver_id' => $joborder['driver_id'],
                            'nominal' =>  $val['nominal_kasbon']
                        ]);
                    }

                    $driver = Driver::findOrFail($joborder['driver_id']);
                  //  $Driverlogkasbon = Driverlogkasbon::selectRaw('sum(nominal) as nominal')->where('joborder_id', $id)->first();
                    // dd( $Driverlogkasbon);
                  //  $NominalDriverlogkasbon =  $Driverlogkasbon['nominal'] ?? 0;

                    $ck_dk = $driver['kasbon'] + $first_kasbon;

                //    dd($driver['kasbon'], $first_kasbon);
                    if($total_kasbon <=  $ck_dk){
                        $total_kasbon_driver = $ck_dk - $total_kasbon;
                        // dd($total_kasbon, $ck_dk, $total_kasbon_driver);
                        // dd($total_kasbon_driver);
                        $driver->update([
                            'kasbon'=>  $total_kasbon_driver,
                        ]);

                    }else{
                        $response = response()->json([
                            'error' => true,
                            'message' => 'Potongan Kasbon Melebihi Jumlah Kasbon Tersedia'
                        ]);
                    }
                    // dd($payment_id );
                    $cek_payment = PaymentJo::where([
                        ['joborder_id' , $joborder['id']],
                    ])->whereNotIn('id', $payment_id);


                    if(isset($cek_payment)){
                        $cek_payment->delete();
                    }




                  }


                  $total_sisa_uang_jalan = $joborder['total_uang_jalan'] - $total_payment - $total_kasbon;

                  if($total_sisa_uang_jalan < 0){
                    $response = response()->json([
                        'error' => true,
                        'message' => 'Sisa Uang Jalan Minus'
                    ]);
                  }else{
                    $status = $total_sisa_uang_jalan > 0 ? '1' : '2';
                    //   dd($total_sisa_uang_jalan);
                      $joborder->update([
                        'total_kasbon'=> $total_kasbon,
                        'total_payment'=> $total_payment,
                        'sisa_uang_jalan'=> $total_sisa_uang_jalan,
                        'status_payment'=> $status
                      ]);

                     DB::commit();
                     $response = response()->json($this->responseStore(true, route('backend.paymentjo.index')));
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
            // 'total_kasbon' => "required",
            // 'total_payment' => "required",
            // 'joborder_id' => "required",
            // 'tgl_pembayaran'  => "required",
            // 'payment'  => "required"
          ]);

          DB::beginTransaction();
          if ($validator->passes()) {
            try {

                 $paymentjo = PaymentJo::findOrFail($request['id']);
                 $joborder = Joborder::findOrFail( $paymentjo['joborder_id']);

                //  dd($joborder['total_payment'], $paymentjo['nominal']);
                 $total_payment =  ($joborder['total_payment'] - $paymentjo['nominal']) + $request['nominal'];
                 $total_kasbon =  ($joborder['total_kasbon'] - $paymentjo['nominal_kasbon']) + $request['nominal_kasbon'];

                //  dd($joborder['total_kasbon'], $paymentjo['nominal_kasbon']);




                // $updatekasbon = null;

                $kasbon = Kasbon::find($paymentjo['kasbon_id']);
                $kasbonjurnallog = Kasbonjurnallog::where('kasbon_id', $paymentjo['kasbon_id']);
                if($request['nominal_kasbon'] > 0){
                    $kode =  $this->KodeKasbon(Carbon::parse($request['tgl_pembayaran'])->format('d M Y'));
                 //   $kasbon = Kasbon::find($paymentjo['kasbon_id']);
                    $kasbon = Kasbon::updateOrCreate([
                        'id' => $paymentjo['kasbon_id']
                    ],[
                        'joborder_id' =>  $joborder['id'],
                        'kode_kasbon'=>  $kasbon['kode_kasbon'] ?? $kode,
                        'driver_id' => $joborder['driver_id'],
                        'tgl_kasbon'=>  $paymentjo['tgl_payment'] ?? $request['tgl_pembayaran'],
                        'jenis'=> 'Potong Joborder',
                        'keterangan'=> $request['keterangan_kasbon'],
                        'nominal'=> $request['nominal_kasbon'],
                        'validasi' =>  '1'
                    ]);
                    // $kasbon_id[] = $kasbon['id'];

                     if(isset($kasbon['id'])){
                        $kasbonjurnallog = Kasbonjurnallog::updateOrCreate([
                             'kasbon_id' => $kasbon['id']
                        ],[
                            'kasbon_id' =>   $kasbon['id'],
                            'joborder_id' =>  $kasbon['joborder_id'],
                            'driver_id' =>  $kasbon['driver_id'],
                            'kode_kasbon'=>  $kasbon['kode_kasbon'],
                            'jenis'=>  $kasbon['jenis'],
                            'tgl_kasbon'=>  $kasbon['tgl_kasbon'],
                            'keterangan'=> $kasbon['keterangan_kasbon'],
                            'debit'=> $kasbon['nominal'],
                            'kredit'=> '0'
                        ]);
                     }



                    $Driverlogkasbon = Driverlogkasbon::updateOrCreate([
                        'payment_joborder_id' => $paymentjo['id']
                    ],[
                        'payment_joborder_id' =>   $paymentjo['id'],
                        'joborder_id' =>   $joborder['id'],
                        'driver_id' => $joborder['driver_id'],
                        'nominal' =>  $request['nominal_kasbon']
                    ]);

                    // $kasbon->update([
                    //     'nominal'=> $request['nominal_kasbon'],
                    // ]);

                    // $kasbonjurnallog->update([
                    //     'debit'=> $request['nominal_kasbon'],
                    // ]);

                }else{
                    if(isset($kasbon)){
                        $kasbon->delete();
                    }
                }

                $driver = Driver::findOrFail($joborder['driver_id']);
                $Driverlogkasbon = Driverlogkasbon::where('payment_joborder_id', $paymentjo['id']);
                $Driverlogkasbon->update([
                    'nominal' =>  $request['nominal_kasbon']
                ]);
                $cek_kasbon = $driver['kasbon'] + $joborder['total_kasbon'] ;


                $total_kasbon_driver =  $cek_kasbon - $total_kasbon;

                // dd( $total_kasbon_driver);

                $kasbon_id = isset($kasbon) ? $kasbon['id'] : null;

                $paymentjo->update([
                    'nominal' => $request['nominal'],
                    'kasbon_id' =>  $kasbon_id,
                    'tgl_payment' =>  $request['tgl_pembayaran'],
                    'nominal_kasbon' => $request['nominal_kasbon'],
                    'jenis_payment' => $request['jenis_payment'],
                    'keterangan_kasbon' => $request['keterangan_kasbon'],
                    'keterangan' => $request['keterangan']
                ]);

                // dd($paymentjo);



                $total_sisa_uang_jalan = $joborder['total_uang_jalan'] - $total_payment - $total_kasbon;

                if( $total_sisa_uang_jalan < 0 ){
                    $response = response()->json([
                        'status' => 'error',
                        'message' => 'Nominal Melebihi Sisa Tagihan'
                    ]);
                }elseif($total_kasbon  < 0){
                    $response = response()->json([
                        'status' => 'error',
                        'message' => 'Nominal kasbon Melebihi Kasbon Tersedia Tersedia'
                    ]);
                }else{
                    // dd($total_kasbon);
               //     dd($total_kasbon_driver);
                    $driver->update([
                        'kasbon'=>  $total_kasbon_driver,
                    ]);





                    $status = $total_sisa_uang_jalan == $joborder['total_uang_jalan'] ? '0' : '1';
                    $status = $total_sisa_uang_jalan <= 0 ? '2' : $status;
                    $joborder->update([
                        'total_kasbon'=> $total_kasbon,
                        'total_payment'=> $total_payment,
                        'sisa_uang_jalan'=> $total_sisa_uang_jalan,
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
    public function pdf($id)
    {
        // dd($id);
        $paymentjo = PaymentJo::findOrFail($id);
        $joborder = Joborder::with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil', 'kasbon')->findOrFail($paymentjo['joborder_id']);
        $paymentjolast = PaymentJo::selectRaw('sum(nominal) as nominal_last, sum(nominal_kasbon) as nominal_kasbon_last, count(id) + 1 as urut')->where('id', '<', $paymentjo['id'])->where('joborder_id', $joborder['id'])->first();
        // dd($paymentjolast);
        $data = [
              'paymentlast' => $paymentjolast,
              'payment' => $paymentjo,
              'joborder' => $joborder,
        ];
        $pdf =  PDF::loadView('backend.pdf.paymentjo', $data);
        $fileName = 'Bukti-Titipan-Uj-'.$paymentjo['id'].'-'. $joborder['kode_joborder'];
        return $pdf->stream("${fileName}.pdf");
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
         $data = PaymentJo::findOrFail($id);
            if ($data->delete()) {
                $joborder = Joborder::where('id', $data['joborder_id'])->first();

                $total_payment = $joborder['total_payment'] - $data['nominal'];
                // $total_kasbon = 0;
                if($data['nominal_kasbon'] > 0){
                        $driver = Driver::findOrFail($joborder['driver_id']);
                    //    $Driverlogkasbon = Driverlogkasbon::where('joborder_id',  $joborder['id'])->first();
                    //    $NominalDriverlogkasbon =  $Driverlogkasbon['nominal'] ?? 0;

                        $total_kasbon_driver = ($driver['kasbon'] + $data['nominal_kasbon']);


                        $driver->update([
                                'kasbon'=>  $total_kasbon_driver,
                        ]);

                     $kasbon = Kasbon::findOrFail($data['kasbon_id']);
                     if(isset($data['kasbon_id'])){
                        $cek_kasbon = Kasbon::where([
                            ['joborder_id' , $joborder['id']],
                        ])->where('id', $data['kasbon_id']);
                        // dd($cek_kasbon);
                        $cek_kasbon->delete();
                     }

                     $total_kasbon =    $joborder['total_kasbon'] - $data['nominal_kasbon'];

                }else{
                    $total_kasbon = $joborder['total_kasbon'];
                }


                $total_sisa_uang_jalan = $joborder['sisa_uang_jalan'] + $data['nominal_kasbon'] + $data['nominal'];
                $status = $total_sisa_uang_jalan == $joborder['total_uang_jalan'] ? '0' : '1';



                $joborder->update([
                    'total_kasbon'=> $total_kasbon,
                    'total_payment'=> $total_payment,
                    'sisa_uang_jalan'=> $total_sisa_uang_jalan,
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
