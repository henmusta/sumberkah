<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\KonfirmasiJo;
use App\Models\Joborder;
use App\Models\Rute;
use App\Models\Driver;
use App\Models\Mobil;
use App\Traits\NoUrutTrait;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class KonfirmasiJoController extends Controller
{
    use ResponseStatus,NoUrutTrait;

    function __construct()
    {
      $this->middleware('can:backend-konfirmasijo-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-konfirmasijo-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-konfirmasijo-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-konfirmasijo-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $config['page_title'] = "Konfirmasi Joborder";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Konfirmasi Joborder"],
        ];
        if ($request->ajax()) {
          $data = KonfirmasiJo::query();
          if ($request->filled('id')) {
            $data->where('joborder_id', $request['id']);
          }
          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $perm = [
                    'list' => Auth::user()->can('backend-paymentjo-list'),
                    'create' => Auth::user()->can('backend-paymentjo-create'),
                    'edit' => Auth::user()->can('backend-paymentjo-edit'),
                    'delete' => Auth::user()->can('backend-paymentjo-delete'),
                ];


                $show = '<a href="' . route('backend.driver.show', $row->id) . '" class="dropdown-item">Detail</a>';
                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $show.'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.konfirmasijo.index', compact('config', 'page_breadcrumbs'));
    }

    public function create(Request $request)
    {
      $config['page_title'] = "Konfirmasi Joborder";
      $page_breadcrumbs = [
        ['url' => route('backend.konfirmasijo.index'), 'title' => "Konfirmasi Joborder"],
        ['url' => '#', 'title' => "Konfirmasi Joborder"],
      ];
      $joborder = Joborder::find($request['joborder_id']);
      $data = [
        'joborder' => $joborder,
      ];
      return view('backend.konfirmasijo.create', compact('page_breadcrumbs', 'config' , 'data'));
    }

    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
            'joborder_id' => 'required',
            // 'tgl_konfirmasi' => 'required',
            'tgl_muat' => 'required',
            'tgl_bongkar' => 'required',
            'berat_muatan' => 'required',
            'konfirmasi_biaya_lain' => 'required',
            'status_ekspedisi' => 'required'
          ]);



          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                  $joborder =Joborder::findOrFail($request['joborder_id']);
                  $rute = Rute::findOrFail( $joborder['rute_id']);

                  $total_harga = $rute['harga'] * $request['berat_muatan'];

                  if($total_harga < 0){
                    $response = response()->json([
                        'status' => 'error',
                        'message' => 'Hubungin Customer Service'
                    ]);
                  }else{
                    $data = KonfirmasiJo::create([
                        'joborder_id' => $joborder['id'],
                        'customer_id' => $joborder['customer_id'],
                        'kode_joborder' => $joborder['kode_joborder'],
                        'tgl_konfirmasi' => Carbon::now()->format('Y-m-d'),
                        'tgl_muat' => $request['tgl_muat'],
                        'tgl_bongkar' => $request['tgl_bongkar'],
                        'berat_muatan' => $request['berat_muatan'],
                        'konfirmasi_biaya_lain' => $request['konfirmasi_biaya_lain'],
                        'keterangan_konfirmasi' => $request['keterangan_konfirmasi'],
                        'status_ekspedisi' => $request['status_ekspedisi'],
                        'total_harga' =>  $total_harga,
                        'created_by' => Auth::user()->id,
                      ]);

                      if(isset($data['id'])){
                        $joborder->update([
                            'status_joborder' => 1
                        ]);

                            $cek_sj_driver = Joborder::where('driver_id',$joborder['driver_id'])->where('status_joborder', '0')->get();
                            $cek_sj_mobil = Joborder::where('mobil_id',$joborder['mobil_id'])->where('status_joborder', '0')->get();



                            $driver = Driver::findOrFail($joborder['driver_id']);
                            $cek_status_jalan_driver = count($cek_sj_driver) > '1' ? '1' : '0';
                            // dd($cek_status_jalan_driver);

                        //    dd( $cek_status_jalan_driver);
                            $driver->update([
                                'status_jalan'  =>  $cek_status_jalan_driver,
                            ]);
                            $mobil = Mobil::findOrFail($joborder['mobil_id']);
                            $cek_status_jalan_mobil = count($cek_sj_mobil) > '1' ? '1' : '0';
                            $mobil->update([
                                'status_jalan'  =>  $cek_status_jalan_mobil,
                            ]);
                      }
                  DB::commit();
                  }

              if(isset($request['cek_joborder_id'])){
                $response = response()->json($this->responseStore(true, route('backend.joborder.index')));
              }else{
                $response = response()->json($this->responseStore(true, route('backend.konfirmasijo.index')));
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

    public function datatablecekjo(Request $request)
    {
      if ($request->ajax()) {
            $driver_id = $request['driver_id'];
            $mobil_id = $request['mobil_id'];
            $bulan_kerja = $request['bulan_kerja'] ?? Carbon::now()->format('Y-m-d');
            $month = date("m",strtotime($bulan_kerja));
            $year = date("Y",strtotime($bulan_kerja));
            $penggajian_id = $request['penggajian_id'];


            $customer_id = $request['customer_id'];
            $invoice_id = $request['invoice_id'];
            $create = $request['create'];
            // $data = Konfirmasijo::with('joborder','customer')
            $data = Joborder::selectRaw('joborder.*,
                                         konfirmasi_joborder.id as konfirmasi_id,
                                         konfirmasi_joborder.tgl_muat,
                                         konfirmasi_joborder.status as status_konfirmasi,
                                         konfirmasi_joborder.tgl_bongkar,
                                         konfirmasi_joborder.berat_muatan,
                                         konfirmasi_joborder.total_harga
                                         ')->with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil')
                            ->Join('konfirmasi_joborder', 'konfirmasi_joborder.joborder_id', '=', 'joborder.id')

            ->when($create, function ($query, $create) {
                return $query->where('konfirmasi_joborder.status', '0');
            })
            ->when($invoice_id, function ($query, $invoice_id) {
                return $query->where('konfirmasi_joborder.invoice_id', $invoice_id)->orWhere('konfirmasi_joborder.status', '0');
            })
           ->where('konfirmasi_joborder.customer_id', $customer_id);

            // ->where('customer_id', $request['customer_id']);
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

    public function findkonfirmasijo(Request $request)
    {
    //    dd($request['konfirmasijo_id']);
    $total_harga = $harga = $gaji = $berat_muatan = 0;
    $kode_joborder = array();
    foreach($request['konfirmasijo_id'] as $val ){
        $konfirmasijo = KonfirmasiJo::findOrFail($val);
        $joborder = Joborder::findOrFail($konfirmasijo['joborder_id']);
        $rute = Rute::findOrFail($joborder['rute_id']);
        $total_harga += $konfirmasijo['total_harga'];
        $harga += $rute['harga'];
        $gaji += $rute['gaji'];
        $berat_muatan += $konfirmasijo['berat_muatan'];
        $kode_joborder[] = $konfirmasijo['kode_joborder'];
    }

      $data = [
        'sum_beratmuatan' => $berat_muatan,
        'sum_total_harga' => $total_harga,
        'kode_joborder' => $kode_joborder,
        'sum_harga' => $harga,
        'sum_gaji' => $gaji
      ];

      return response()->json($data);
    }
}
