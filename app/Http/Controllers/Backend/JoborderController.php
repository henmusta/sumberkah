<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Joborder;
use App\Models\KonfirmasiJo;
use Illuminate\Support\Facades\Auth;
use App\Models\Rute;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
use PDF;
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
        $joborder = Joborder::find($request['joborder_id']);
        $belum_bayar = Joborder::selectRaw('sum(sisa_uang_jalan) as belum_bayar')->where('status_payment', '0')->first();
        $data = [
          'joborder' => $joborder,
          'belum_bayar' => $joborder
        ];


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


                $validasi = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalValidasi" data-bs-id="' . $row->id . '"   data-bs-kode="' . $row->kode_joborder . '" data-bs-invoice="' . $row->kode_invoice . '" data-bs-gaji="' . $row->kode_gaji . '" class="edit dropdown-item">Pembatalan Konfirmasi</a>';





                $show = '<a href="' . route('backend.joborder.show', $row->id) . '" class="dropdown-item" target="_blank">Detail</a>';
                $list_payment = '<a href="' . route('backend.paymentjo.index', ['joborder_id'=> $row->id]) . '" class="dropdown-item">List Payment</a>';
                $edit = '<a class="dropdown-item" href="joborder/' . $row->id . '/edit">Ubah</a>';
                $payment = '<a href="' . route('backend.paymentjo.create',  ['joborder_id'=> $row->id]) . '" class="dropdown-item">Pembayaran</a>';
                $cicilan = '<a class="dropdown-item" href="paymentjo/' . $row->id . '/edit">Pelunasan</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';
                $konfirmasi_jo =  '<a href="' . route('backend.konfirmasijo.create',  ['joborder_id'=> $row->id]) . '" class="dropdown-item">Konfirmasi Jo</a>';

                $cek_konfirmasi_jo =  $row->status_payment == '2' && $row->status_joborder == '0' ? $konfirmasi_jo : '';
                $cek_payment = $row->status_payment == '0'  ? $payment : ($row->status_payment == '1'  ? $cicilan : '');
                $cek_validasi = $row->status_joborder == '1' ? $validasi : '';
                $cek_list_payment = $row->status_payment > '0' ? $list_payment : '';
                $cek_edit =  $row->status_payment == '0' && $row->status_joborder == '0' ? $edit : '';
                $cek_delete =  $row->status_payment == '0' && $row->status_joborder == '0' ? $delete : '';

                $cek_perm_validasi = $perm['edit'] == 'true' ? $cek_validasi : '';
                $cek_perm_konfirmasi_jo = $perm['edit'] == 'true' ? $cek_konfirmasi_jo : '';
                $cek_perm_edit = $perm['edit'] == 'true' ? $cek_edit : '';
                $cek_perm_delete = $perm['delete'] == 'true' ? $cek_delete : '';

                $cek_level_validasi = Auth::user()->roles()->first()->level == '1' && $row->penggajian_id == null && $row->invoice_id == null ? $cek_validasi : $cek_perm_validasi;
                $cek_level_edit = Auth::user()->roles()->first()->level == '1' && $row->penggajian_id == null && $row->invoice_id == null ? $edit : $cek_perm_edit;
                $cek_level_konfirmasi_jo = $row->penggajian_id == null && $row->invoice_id == null ? $cek_konfirmasi_jo : $cek_perm_konfirmasi_jo;
                $cek_level_delete = Auth::user()->roles()->first()->level == '1' &&  $row->penggajian_id == null && $row->invoice_id == null ? $delete : $cek_perm_delete;

                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $cek_perm_edit .'
                    '. $cek_payment.'
                    '. $cek_perm_delete .'
                    '. $cek_level_validasi.'
                    '. $cek_konfirmasi_jo .'
                    '. $show .'
                    '.$cek_list_payment.'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.joborder.index', compact('config', 'page_breadcrumbs', 'data'));
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
                $kode =  $this->KodeJoborder(Carbon::parse($request['tgl_joborder'])->format('d M Y'));
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
            // $kode =  $this->KodeJoborder(Carbon::now()->format('d M Y'));
            // $kode =  $data['tgl_joborder'] != $request['tgl_joborder'] ?

            // if( $data['tgl_joborder'] != $request['tgl_joborder']){

            // }
            $kode =  $this->KodeJoborder(Carbon::parse($request['tgl_joborder'])->format('d M Y'));
            $kode_update = $data['tgl_joborder'] != $request['tgl_joborder'] ? $kode : $data['kode_joborder'];
            $data->update([
                'driver_id'  => $request['driver_id'],
                'kode_joborder'  =>  $kode_update,
                'mobil_id'  => $request['mobil_id'],
                'customer_id'  => $request['customer_id'],
                'rute_id'  => $request['rute_id'],
                'tgl_joborder'  => $request['tgl_joborder'],
                'tambahan_potongan'  => $request['tambahan_potongan'],
                'muatan_id'  => $request['muatan_id'],
                'first_rute_id'  => $request['first_rute_id'],
                'last_rute_id'  => $request['last_rute_id'],
                'uang_jalan'  => $request['uang_jalan'],
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
          ]);
        //   dd($request['id']);
          if ($validator->passes()) {
            $data = Joborder::find($request['id']);
            $konfirmasi = KonfirmasiJo::where('joborder_id', $data['id']);
            DB::beginTransaction();
            try {

                //dd($data['invoice_id'] , $data['penggajian_id']);
                if(isset($data['invoice_id']) && is_null($data['penggajian_id'])){
                    $response = response()->json([
                        'status' => 'error',
                        'message' => 'Sudah Terkoneksi Dengan Invoice'
                    ]);
                }elseif(isset($data['penggajian_id']) && is_null($data['invoice_id'])){
                    $response = response()->json([
                        'status' => 'error',
                        'message' => 'Sudah Terkoneksi Dengan Penggajian'
                    ]);
                }elseif(isset($data['invoice_id']) && isset($data['penggajian_id'])){
                    $response = response()->json([
                        'status' => 'error',
                        'message' => 'Sudah Terkoneksi Dengan Penggajian Dan Invoice'
                    ]);
                }elseif(is_null($data['penggajian_id']) && is_null($data['invoice_id'])){
                    $data->update([
                        'status_joborder' => '0',
                      ]);
                    $konfirmasi->delete();
                    $response = response()->json($this->responseStore(true));
                }
                DB::commit();

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
                $mobil = Mobil::findOrFail( $data['mobil_id']);
                $driver = Driver::findOrFail( $data['driver_id']);
                $mobil->update([
                    'status_jalan'  => '0',
                ]);
                $driver->update([
                    'status_jalan'  => '0',
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

    public function select2(Request $request)
    {
      $page = $request->page;
      $resultCount = 10;
      $konfirmasi_jo = $request['konfirmasi_joborder'];
      $status_joborder = $request['status_joborder'];
      $offset = ($page - 1) * $resultCount;
      $data = Joborder::where('kode_joborder', 'LIKE', '%' . $request->q . '%')
        ->when($konfirmasi_jo, function ($query, $konfirmasi_jo) {
            return $query->where('status_payment', '=', '0');
         })
         ->when($status_joborder, function ($query, $status_joborder) {
            return $query->where('status_joborder', '!=', $status_joborder);
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
        ->when($status_joborder, function ($query, $status_joborder) {
            return $query->where('status_joborder', '!=', $status_joborder);
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

    public function excel(Request $request)
    {


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $status_joborder = $request['status_joborder'];
        $driver_id = $request['driver_id'];
        $jenismobil_id = $request['jenismobil_id'];
        $mobil_id = $request['mobil_id'];
        $customer_id = $request['customer_id'];
        $id = $request['id'];
        $tgl_awal = $request['tgl_awal'];
        $tgl_akhir = $request['tgl_akhir'];

        // dd( $tgl_akhir);

        $data = Joborder::with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil', 'createdby')
        ->when($status_joborder, function ($query, $status_joborder) {
            return $query->where('status_joborder',  $status_joborder);
         })
         ->when( $driver_id, function ($query,  $driver_id) {
            return $query->where('driver_id',   $driver_id);
         })->when( $jenismobil_id, function ($query,  $jenismobil_id) {
            return $query->where('jenismobil_id',   $jenismobil_id);
         })->when( $mobil_id, function ($query,  $mobil_id) {
            return $query->where('mobil_id',   $mobil_id);
         })->when( $customer_id, function ($query,  $customer_id) {
            return $query->where('customer_id',   $customer_id);
         })->when( $id, function ($query,  $id) {
            return $query->where('id',   $id);
         })->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_joborder', '>=', $tgl_awal);
         })
         ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_joborder', '<=', $tgl_akhir);
         })->get();


         $sheet->setCellValue('A1', 'Laporan Joborder');
         $spreadsheet->getActiveSheet()->mergeCells('A1:N1');
         $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

         if($request['tgl_awal'] != null && $request['tgl_akhir'] != null){
            $spreadsheet->getActiveSheet()->mergeCells('A2:N2');
            $sheet->setCellValue('A2', 'Tanggal : '. date('d-m-Y', strtotime($request['tgl_awal'])) .' S/D '. date('d-m-Y', strtotime($request['tgl_akhir'])));
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
         }


         $rows3 = 3;
         $sheet->setCellValue('A'.$rows3, 'Id Jo');
         $sheet->setCellValue('B'.$rows3, 'Tanggal');
         $sheet->setCellValue('C'.$rows3, 'Status');
         $sheet->setCellValue('D'.$rows3, 'Driver');
         $sheet->setCellValue('E'.$rows3, 'Nomor Plat Polisi');
         $sheet->setCellValue('F'.$rows3, 'Jenis mobil');
         $sheet->setCellValue('G'.$rows3, 'Customer');
         $sheet->setCellValue('H'.$rows3, 'Muatan');
         $sheet->setCellValue('I'.$rows3, 'Alamat Awal');
         $sheet->setCellValue('J'.$rows3, 'Alamat Akhir');
         $sheet->setCellValue('K'.$rows3, 'Total Uj');
         $sheet->setCellValue('L'.$rows3, 'Pembayaran');
         $sheet->setCellValue('M'.$rows3, 'Sisa Uang Jalan');
         $sheet->setCellValue('N'.$rows3, 'Keterangan');
         $sheet->setCellValue('O'.$rows3, 'Operator (Waktu)');
         for($col = 'A'; $col !== 'Q'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}
         $x = 4;
         foreach($data as $val){
                $status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas');
                $status_jo = $val['status_joborder'] == '0' ? 'Ongoing' : 'Done';
                 $sheet->setCellValue('A' . $x, $val['kode_joborder']);
                 $sheet->setCellValue('B' . $x, $val['tgl_joborder']);
                 $sheet->setCellValue('C' . $x,  $status_jo);
                 $sheet->setCellValue('D' . $x, $val['driver']['name'] ?? '');
                 $sheet->setCellValue('E' . $x, $val['mobil']['nomor_plat'] ?? '');
                 $sheet->setCellValue('F' . $x, $val['jenismobil']['name'] ?? '');
                 $sheet->setCellValue('G' . $x, $val['customer']['name'] ?? '');
                 $sheet->setCellValue('H' . $x, $val['muatan']['name'] ?? '');
                 $sheet->setCellValue('I' . $x, $val['ruteawal']['name'] ?? '');
                 $sheet->setCellValue('J' . $x, $val['ruteakhir']['name'] ?? '');
                 $sheet->setCellValue('K' . $x, $val['total_uang_jalan'] ?? '');
                 $sheet->setCellValue('L' . $x, $status_payment);
                 $sheet->setCellValue('M' . $x,  $val['sisa_uang_jalan']);
                 $sheet->setCellValue('N' . $x,  $val['keterangan_joborder'] ?? '');
                 $sheet->setCellValue('O' . $x, $val['createdby']->name . ' ( ' .date('d-m-Y', strtotime($val['created_at'])) .' )');
                 $x++;
         }
      $cell   = count($data) + 4;

      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total :');
      $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':J' . $cell . '');
      $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

      $spreadsheet->getActiveSheet()->getStyle('K3:K'.$cell)->getNumberFormat()->setFormatCode('#,##0');
      $spreadsheet->getActiveSheet()->getStyle('M3:M'.$cell)->getNumberFormat()->setFormatCode('#,##0');

      $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$cell, '=SUM(K3:K' . $cell . ')');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$cell, '=SUM(M3:M' . $cell . ')');

      $writer = new Xlsx($spreadsheet);
      $filename = 'Laporan Joborder';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');

    }

    public function pdf(Request $request)
    {

        $status_joborder = $request['status_joborder'];
        $driver_id = $request['driver_id'];
        $jenismobil_id = $request['jenismobil_id'];
        $mobil_id = $request['mobil_id'];
        $customer_id = $request['customer_id'];
        $id = $request['id'];
        $tgl_awal = $request['tgl_awal'];
        $tgl_akhir = $request['tgl_akhir'];

        // dd( $tgl_akhir);

        $data = Joborder::with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil', 'createdby')
        ->when($status_joborder, function ($query, $status_joborder) {
            return $query->where('status_joborder',  $status_joborder);
         })
         ->when( $driver_id, function ($query,  $driver_id) {
            return $query->where('driver_id',   $driver_id);
         })->when( $jenismobil_id, function ($query,  $jenismobil_id) {
            return $query->where('jenismobil_id',   $jenismobil_id);
         })->when( $mobil_id, function ($query,  $mobil_id) {
            return $query->where('mobil_id',   $mobil_id);
         })->when( $customer_id, function ($query,  $customer_id) {
            return $query->where('customer_id',   $customer_id);
         })->when( $id, function ($query,  $id) {
            return $query->where('id',   $id);
         })->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_joborder', '>=', $tgl_awal);
         })
         ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_joborder', '<=', $tgl_akhir);
         })->get();

                $data = [
                    'jo' => $data,
                    'tgl_awal' => $request['tgl_awal'],
                    'tgl_akhir' => $request['tgl_akhir'],
                ];


        $pdf =  PDF::loadView('backend.joborder.report',  compact('data'));
        $fileName = 'Laporan-JO : '. $tgl_awal . '-SD-' .$tgl_akhir;
        $PAPER_F4 = array(0,0,609.4488,935.433);
        $pdf->setPaper( $PAPER_F4, 'landscape');
        $pdf->render();
        $font       = $pdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $pdf->get_canvas()->page_text(33, 590, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
        return $pdf->stream("${fileName}.pdf");
    }

}
