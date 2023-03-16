<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Joborder;
use Illuminate\Support\Facades\Auth;
use App\Models\Rute;
use App\Models\Jenismobil;
use App\Models\Driver;
use App\Models\Mobil;
use App\Models\MenuManager;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Muatan;
use App\Models\Alamatrute;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use App\Traits\NoUrutTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class JoborderController extends Controller
{
    use ResponseStatus,NoUrutTrait;

    function __construct()
    {
      $this->middleware('can:backend-joborder-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-joborder-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-joborder-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-joborder-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $config['page_title'] = "Data Joborder";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Joborder"],
        ];
        // // $this->middleware('can:backend-users-list', ['only' => ['index', 'show']]);

    //     $roleId = auth()->user()->roles()->first()->id;
    //     $menuManager = MenuManager::with(['menupermission', 'permissions'])->findOrFail($id);
    //   //  $role_permisions = Role::with('permissions')->find(auth()->user()->roles()->first()->id);
    //     dd($menuManager);

    //    $data = MenuManager::with(['menupermission', 'permissions'])->where('role_id', auth()->user()->roles()->first()->id)->get();
        // $sortable = NULL;
        // if ($role) {
        //   $sortable = self::getByRole($request['role_id']);
        // } elseif ($request['role_id'] != NULL) {
        //   abort(401, "Halaman tidak diizinkan");
        // }

        // dd( $data);


        if ($request->ajax()) {
          $data = Joborder::with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil');
            if ($request->filled('status_joborder')) {
                $data->where('status_joborder', $request['status_joborder']);
            }
            if ($request->filled('driver_id')) {
                $data->where('driver_id', $request['driver_id']);
            }

            if ($request->filled('jenismobil_id')) {
                $data->where('jenismobil_id', $request['jenismobil_id']);
            }
            if ($request->filled('mobil_id')) {
                $data->where('mobil_id', $request['mobil_id']);
            }

            if ($request->filled('customer_id')) {
                $data->where('customer_id', $request['customer_id']);
            }

            if ($request->filled('id')) {
                $data->where('id', $request['id']);
            }

            if ($request->filled('tgl_awal')) {
                    $data->whereDate('tgl_joborder', '>=', $request['tgl_awal']);
            }
            if ($request->filled('tgl_akhir')) {
                 $data->whereDate('tgl_joborder', '<=', $request['tgl_akhir']);
            }

          return DataTables::of($data)
            ->addColumn('action', function ($row) {


                $perm = [
                    'list' => Auth::user()->can('backend-joborder-list'),
                    'create' => Auth::user()->can('backend-joborder-create'),
                    'edit' => Auth::user()->can('backend-joborder-edit'),
                    'delete' => Auth::user()->can('backend-joborder-delete'),
                ];


                $show = '<a href="' . route('backend.joborder.show', $row->id) . '" class="dropdown-item">Detail</a>';
                $edit = '<a class="dropdown-item" href="joborder/' . $row->id . '/edit">Ubah</a>';
                $payment = '<a href="' . route('backend.paymentjo.create',  ['joborder_id'=> $row->id]) . '" class="dropdown-item">Pembayaran</a>';
                $cicilan = '<a class="dropdown-item" href="paymentjo/' . $row->id . '/edit">Pelunasan</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';
                $konfirmasi_jo =  '<a href="' . route('backend.konfirmasijo.create',  ['joborder_id'=> $row->id]) . '" class="dropdown-item">Konfirmasi Jo</a>';

                $cek_konfirmasi_jo =  $row->status_payment == '2' && $row->status_joborder == '0' ? $konfirmasi_jo : '';
                $cek_payment = $row->status_payment == '0'  ? $payment : ($row->status_payment == '1'  ? $cicilan : '');
                $cek_edit =  $row->status_payment == '0' ? $edit : '';
                $cek_delete =  $row->status_payment == '0' ? $delete : '';

                $cek_perm_konfirmasi_jo = $perm['edit'] == 'true' ? $cek_konfirmasi_jo : '';
                $cek_perm_edit = $perm['edit'] == 'true' ? $cek_edit : '';
                $cek_perm_delete = $perm['delete'] == 'true' ? $cek_delete : '';



                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $cek_perm_edit .'
                    '. $cek_payment.'
                    '. $cek_perm_delete .'
                    '. $cek_perm_konfirmasi_jo .'
                    '. $show .'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.joborder.index', compact('config', 'page_breadcrumbs'));
    }


    public function create(Request $request)
    {
      $config['page_title'] = "Tambah Joborder";
      $page_breadcrumbs = [
        ['url' => route('backend.joborder.index'), 'title' => "Daftar Joborder"],
        ['url' => '#', 'title' => "Tambah Joborder"],
      ];
      $joborder = Joborder::with('kasbon', 'payment')->find($request['joborder_id']);
      $data = [
        'joborder' => $joborder,
      ];
      return view('backend.joborder.create', compact('page_breadcrumbs', 'config', 'data'));
    }




    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
            'tgl_joborder'  => "required",
            'customer_id'  => "required",
            'driver_id' => "required",
            'jenismobil_id' => "required",
            'first_rute_id'  => "required",
            'last_rute_id'  => "required",
            'muatan_id'  => "required",
            'mobil_id'  => "required",
            'jenismobil_id'  => "required",
            'muatan_id'  => "required",
            'uang_jalan'  => "required",
            'tambahan_potongan' => "required",
            'biaya_lain' => "required_if:potongan_tambahan,!=,None",
            'total_uang_jalan'  => "required",
            'kode_rute'  => "required",
            // 'keterangan'  => "required",
          ]);
        //   dd($request);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $kode =  $this->KodeJoborder(Carbon::now()->format('d M Y'));
                  $data = Joborder::create([
                    'kode_joborder' => $kode,
                    'kode_rute'  => $request['kode_rute'],
                    'jenismobil_id'  => $request['jenismobil_id'],
                    'rute_id'  => $request['rute_id'],
                    'tgl_joborder'  => $request['tgl_joborder'],
                    'driver_id'  => $request['driver_id'],
                    'mobil_id'  => $request['mobil_id'],
                    'customer_id'  => $request['customer_id'],
                    'muatan_id'  => $request['muatan_id'],
                    'first_rute_id'  => $request['first_rute_id'],
                    'last_rute_id'  => $request['last_rute_id'],
                    'uang_jalan'  => $request['uang_jalan'],
                    'biaya_lain' =>  $request['biaya_lain'] ?? '0',
                    'tambahan_potongan'  => $request['tambahan_potongan'],
                    'keterangan_joborder'  => $request['keterangan_joborder'],
                    'status_joborder'  => '0',
                    'total_uang_jalan'  => $request['total_uang_jalan'],
                    'sisa_uang_jalan'  => $request['total_uang_jalan'],
                    'created_by' => Auth::user()->id,
                  ]);


                  if(isset($data['id'])){
                    $driver = Driver::findOrFail($request['driver_id']);
                    $driver->update([
                        'status_jalan'  => '1',
                    ]);
                    $mobil = Mobil::findOrFail($request['mobil_id']);
                    $mobil->update([
                        'status_jalan'  => '1',
                    ]);
                  }
              DB::commit();
              $response = response()->json($this->responseStore(true, route('backend.joborder.index')));
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


    public function show($id)
    {
        $config['page_title'] = "Detail Joborder";

        $page_breadcrumbs = [
          ['url' => route('backend.joborder.index'), 'title' => "Detail Joborder"],
          ['url' => '#', 'title' => "Detail Joborder"],
        ];
        $joborder = Joborder::with('kasbon', 'payment', 'konfirmasijo', 'gaji', 'invoice')->findOrFail($id);

        // dd($joborder['konfirmasijo'][0]['createdby']['name']);
        $data = [
          'joborder' => $joborder,
        ];

        return view('backend.joborder.show', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function edit($id)
    {
        $config['page_title'] = "Edit Joborder";

        $page_breadcrumbs = [
          ['url' => route('backend.joborder.index'), 'title' => "Daftar Joborder"],
          ['url' => '#', 'title' => "Update oborder"],
        ];
        $joborder = Joborder::with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil')->findOrFail($id);
        //$joborder = Joborder::with('kasbon', 'payment')->findOrFail($id);
        $data = [
          'joborder' => $joborder,
        ];

        return view('backend.joborder.edit', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
        'tgl_joborder'  => "required",
        'tambahan_potongan' => "required",
        'biaya_lain' => "required_if:potongan_tambahan,!=,None",
        'total_uang_jalan'  => "required"
      ]);

      if ($validator->passes()) {
        DB::beginTransaction();
        try {
            $data = Joborder::find($id);
            if(isset($data['id'])){
                $driver = Driver::findOrFail($data['driver_id']);
                $driver->update([
                    'status_jalan'  => '0',
                ]);
                $mobil = Mobil::findOrFail($data['mobil_id']);
                $mobil->update([
                    'status_jalan'  => '0',
                ]);
            }



            $data->update([
                'driver_id'  => $request['driver_id'],
                'mobil_id'  => $request['mobil_id'],
                'tgl_joborder'  => $request['tgl_joborder'],
                'tambahan_potongan'  => $request['tambahan_potongan'],
                'biaya_lain'  => $request['biaya_lain'],
                'keterangan_joborder'  => $request['keterangan_joborder'],
                'status_joborder'  => '0',
                'total_uang_jalan'  => $request['total_uang_jalan'],
                'sisa_uang_jalan'  => $request['total_uang_jalan'],
                'updated_by' => Auth::user()->id,
            ]);

            $driver = Driver::findOrFail($request['driver_id']);
            $driver->update([
                'status_jalan'  => '1',
            ]);
            $mobil = Mobil::findOrFail($request['mobil_id']);
            $mobil->update([
                'status_jalan'  => '1',
            ]);
          DB::commit();
          $response = response()->json($this->responseStore(true, route('backend.joborder.index')));

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

    public function validasi(Request $request)
    {
        // dd( $request);
          $validator = Validator::make($request->all(), [
            'id' => 'required',
            'validasi' => 'required',
          ]);
        //   dd($request['file']);
          if ($validator->passes()) {
            $data = Rute::find($request['id']);
            DB::beginTransaction();
            try {
                  $data->update([
                    'validasi' => $request['validasi'],
                  ]);

                DB::commit();
                $response = response()->json($this->responseStore(true));
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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
         $data = Joborder::findOrFail($id);
            if ($data->delete()) {
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

    public function select2(Request $request)
    {
      $page = $request->page;
      $resultCount = 10;
      $konfirmasi_jo = $request['konfirmasi_joborder'];
      $offset = ($page - 1) * $resultCount;
      $data = Joborder::where('kode_joborder', 'LIKE', '%' . $request->q . '%')
        ->when($konfirmasi_jo, function ($query, $konfirmasi_jo) {
            return $query->where('status_payment', '=', '0');
         })
        ->orderBy('kode_joborder')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, kode_joborder as text')
        ->get();

      $count =  Joborder::where('kode_joborder', 'LIKE', '%' . $request->q . '%')
        ->when($konfirmasi_jo, function ($query, $konfirmasi_jo) {
            return $query->where('status_payment', '=', '0');
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


    public function findjoborder(Request $request)
    {
      $joborder = Joborder::findOrFail($request['id']);
      $jenismobil = Jenismobil::findOrFail($joborder['jenismobil_id']);
      $driver = Driver::findOrFail($joborder['driver_id']);
      $rute = Rute::findOrFail($joborder['rute_id']);
      $mobil = Mobil::findOrFail($joborder['mobil_id']);
      $customer = Customer::findOrFail($joborder['customer_id']);
      $muatan = Muatan::findOrFail($joborder['muatan_id']);
      $firstrute = Alamatrute::findOrFail($joborder['first_rute_id']);
      $lastrute = Alamatrute::findOrFail($joborder['last_rute_id']);

      $data = [
        'joborder' => $joborder,
        'jenismobil' => $jenismobil,
        'driver' => $driver,
        'rute' => $rute,
        'mobil' => $mobil,
        'customer' => $customer,
        'muatan' => $muatan,
        'firstrute' => $firstrute,
        'lastrute' => $lastrute,
      ];

      return response()->json($data);
    }


    public function datatablecekjoborder(Request $request)
    {
      if ($request->ajax()) {
            $driver_id = $request['driver_id'];
            $mobil_id = $request['mobil_id'];
            $bulan_kerja = $request['bulan_kerja'] ?? Carbon::now()->format('Y-m-d');
            $month = date("m",strtotime($bulan_kerja));
            $year = date("Y",strtotime($bulan_kerja));
            $penggajian_id = $request['penggajian_id'];
            $create = $request['create'];

            // dd( $create);
            // dd($create );
             $data = Joborder::with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil', 'konfirmasijo')
            ->when( $penggajian_id, function ($query,  $penggajian_id) {
                return $query->where('penggajian_id',  $penggajian_id)->orWhere('kode_gaji', null);
             })
            ->where([['status_payment', '2'], ['status_joborder','1'], ['driver_id',  $driver_id], ['mobil_id',  $mobil_id]])
            ->when($create, function ($query, $create) {
                return $query->whereNull('penggajian_id');
             })
            ->whereMonth('tgl_joborder','=' , $month)
            ->whereYear('tgl_joborder', '=' ,$year);

            //  dd( $data);
            // // ->where('customer_id', $request['customer_id']);
        return DataTables::of($data)
          ->make(true);
      }
    }



    public function findkonfirmasijoborder(Request $request)
    {
    //    dd($request['konfirmasijo_id']);
    $gaji = 0;
    $kode_joborder = array();
    foreach($request['konfirmasijo_id'] as $val ){
        $joborder = Joborder::findOrFail($val);
        $rute = Rute::findOrFail($joborder['rute_id']);
        $gaji += $rute['gaji'];
        $kode_joborder[] = $joborder['kode_joborder'];
    }

      $data = [
        'kode_joborder' => $kode_joborder,
        'sum_gaji' => $gaji
      ];

      return response()->json($data);
    }

}
