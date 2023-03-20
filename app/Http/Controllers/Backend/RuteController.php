<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Mobil;
use App\Models\Joborder;
use App\Models\Rute;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use Illuminate\Support\Facades\Auth;
use App\Traits\NoUrutTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class RuteController extends Controller
{
    use ResponseStatus,NoUrutTrait;

    function __construct()
    {
        $this->middleware('can:backend-rute-list', ['only' => ['index']]);
        $this->middleware('can:backend-rute-create', ['only' => ['create', 'store']]);
        $this->middleware('can:backend-rute-edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:backend-rute-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Rute";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Rute"],
        ];
        if ($request->ajax()) {
          $data = Rute::with('customer','ruteawal','ruteakhir','muatan','jenismobil');
          if ($request->filled('val_del')) {
            $data->where('validasi_delete', $request['val_del']);
          }
          return DataTables::of($data)
            ->addColumn('action', function ($row) {

                $perm = [
                    'list' => Auth::user()->can('backend-rute-list'),
                    'create' => Auth::user()->can('backend-rute-create'),
                    'edit' => Auth::user()->can('backend-rute-edit'),
                    'delete' => Auth::user()->can('backend-rute-delete'),
                ];

                $joborder = Joborder::where('rute_id', $row->id)->get();

                $show = '<a href="' . route('backend.rute.show', $row->id) . '" class="dropdown-item">Detail</a>';
                $edit = '<a class="dropdown-item" href="rute/' . $row->id . '/edit">Ubah</a>';
                $validasi = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalValidasi" data-bs-id="' . $row->id . '"  data-bs-validasi="' . $row->validasi. '" class="edit dropdown-item">Validasi</a>';
                $validasi_delete = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalValidasiDelete" data-bs-id="' . $row->id . '"  data-bs-validasi-delete="' . $row->validasi_delete. '" class="edit dropdown-item">Validasi Delete</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';

                $cek_edit =  $row->validasi == '0' &&  count($joborder) <= 0 ? $edit : '';
                $cek_delete =  $row->validasi == '0' && $row->validasi_delete == '0' ? $delete : '';
                $cek_validasi =   $row->validasi_delete == '1' ?$validasi : '';

                $cek_validasidelete =  $row->validasi == '0' || $row->validasi_delete == '0' ? $validasi_delete : '';


                $cek_level_validasi = Auth::user()->roles()->first()->level == '1' ?   $cek_validasi : '';
                $cek_level_validasidelete = Auth::user()->roles()->first()->level == '1' ? $cek_validasidelete : '';

                $cek_perm_edit = $perm['edit'] == 'true' ? $cek_edit : '';
                $cek_perm_delete = $perm['delete'] == 'true' ? $cek_delete : '';

                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $show.'
                    '. $cek_perm_edit.'
                    '. $cek_perm_delete.'
                    '. $cek_level_validasi.'
                    '. $cek_level_validasidelete.'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.rute.index', compact('config', 'page_breadcrumbs'));
    }


    public function create()
    {
      $config['page_title'] = "Tambah Rute";
      $page_breadcrumbs = [
        ['url' => route('backend.rute.index'), 'title' => "Daftar Rute"],
        ['url' => '#', 'title' => "Tambah Kendaraan"],
      ];
      return view('backend.rute.create', compact('page_breadcrumbs', 'config'));
    }


    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
            'customer_id'  => "required",
            'first_rute_id'  => "required",
            'last_rute_id'  => "required",
            'muatan_id'  => "required",
            'jenismobil_id'  => "required",
            'gaji'  => "required",
            'muatan_id'  => "required",
            'ritase_tonase'  => "required",
            'harga'  => "required",
            'uang_jalan'  => "required",
            // 'keterangan'  => "required",
          ]);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                  $kode =  $this->KodeRute(Carbon::now()->format('d M Y'));
                  $data = Rute::create([
                    'kode_rute'  => $kode,
                    'customer_id'  => $request['customer_id'],
                    'first_rute_id'  => $request['first_rute_id'],
                    'last_rute_id'  => $request['last_rute_id'],
                    'muatan_id'  => $request['muatan_id'],
                    'jenismobil_id'  => $request['jenismobil_id'],
                    'gaji'  => $request['gaji'],
                    'uang_jalan'  => $request['uang_jalan'],
                    'ritase_tonase'  => $request['ritase_tonase'],
                    'harga'  => $request['harga'],
                    'keterangan'  => $request['keterangan'],
                  ]);
              DB::commit();
              $response = response()->json($this->responseStore(true, route('backend.rute.index')));
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
        $config['page_title'] = "Detail Rute";

        $page_breadcrumbs = [
          ['url' => route('backend.rute.index'), 'title' => "Detail Rute"],
          ['url' => '#', 'title' => "Update Driver"],
        ];
        $rute = Rute::with('customer','ruteawal','ruteakhir','muatan','jenismobil')->findOrFail($id);
        $data = [
          'rute' => $rute,
        ];

        return view('backend.rute.show', compact('page_breadcrumbs', 'config', 'data'));
    }



    public function edit($id)
    {
        $config['page_title'] = "Update Rute";

        $page_breadcrumbs = [
          ['url' => route('backend.rute.index'), 'title' => "Daftar Rute"],
          ['url' => '#', 'title' => "Update Rute"],
        ];
        $rute = Rute::with('customer','ruteawal','ruteakhir','muatan','jenismobil')->findOrFail($id);
        $data = [
          'rute' => $rute,
        ];

        return view('backend.rute.edit', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
        'customer_id'  => "required",
        'first_rute_id'  => "required",
        'last_rute_id'  => "required",
        'muatan_id'  => "required",
        'jenismobil_id'  => "required",
        'gaji'  => "required",
        'muatan_id'  => "required",
        'ritase_tonase'  => "required",
        'harga'  => "required",
        'uang_jalan'  => "required",
      ]);

      if ($validator->passes()) {
        $dimensions = [array('300', '300', 'mobil')];
        DB::beginTransaction();
        try {
            $data = Rute::find($id);
            $data->update([
                'kode_rute'  => $data['kode_rute'],
                'customer_id'  => $request['customer_id'],
                'first_rute_id'  => $request['first_rute_id'],
                'last_rute_id'  => $request['last_rute_id'],
                'muatan_id'  => $request['muatan_id'],
                'jenismobil_id'  => $request['jenismobil_id'],
                'gaji'  => $request['gaji'],
                'uang_jalan'  => $request['uang_jalan'],
                'ritase_tonase'  => $request['ritase_tonase'],
                'harga'  => $request['harga'],
                'keterangan'  => $request['keterangan'],
            ]);
          DB::commit();
          $response = response()->json($this->responseStore(true, route('backend.rute.index')));

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
        //   dd($request['id']);
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



    public function validasidelete(Request $request)
    {
        // dd( $request);
          $validator = Validator::make($request->all(), [
            'id' => 'required',
            'validasi_delete' => 'required',
          ]);
        //   dd($request['id']);
          if ($validator->passes()) {
            $data = Rute::find($request['id']);
            DB::beginTransaction();
            try {
                  $data->update([
                    'validasi_delete' => $request['validasi_delete'],
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
         $data = Rute::findOrFail($id);
         $joborder = Joborder::where('rute_id', $id)->get();
        //  dd($joborder);
         if(count($joborder) > 0){
            // dd($data);
              $data->update([
                'validasi_delete' => '0',
              ]);

            // dd( $data);
                $response = response()->json([
                    'status' => 'error',
                    'message' => 'Data Rute Sudah Terpakai Di Joborder'
                ]);
                DB::commit();

         }else{
            if ($data->delete()) {
                DB::commit();
                $response = response()->json($this->responseDelete(true));
            }
         }


      } catch (Throwable $throw) {
        dd($throw);
        DB::rollBack();
        $response = response()->json($this->responseStore(false));
      }

      return $response;
    }

    public function select2(Request $request)
    {
      $jenismobil_id = $request->jenismobil_id;
      $customer_id = $request->customer_id;
      $muatan_id = $request->muatan_id;
      $first_rute_id = $request->first_rute_id;
      $last_rute_id = $request->last_rute_id;
      $page = $request->page;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = Rute::where('kode_rute', 'LIKE', '%' . $request->q . '%')
        ->where('validasi', '1')
        ->when($jenismobil_id, function ($query, $jenismobil_id) {
            return $query->where('rute.jenismobil_id', $jenismobil_id);
        })
        ->when($customer_id, function ($query, $customer_id) {
            return $query->where('rute.customer_id', $customer_id);
        })
        ->when($muatan_id, function ($query, $muatan_id) {
            return $query->where('rute.muatan_id', $muatan_id);
        })
        ->when($first_rute_id, function ($query, $first_rute_id) {
            return $query->where('rute.first_rute_id', $first_rute_id);
        })
        ->when($last_rute_id, function ($query, $last_rute_id) {
            return $query->where('rute.last_rute_id', $last_rute_id);
        })
        ->orderBy('kode_rute')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, CONCAT(kode_rute," - uang jalan = ",FORMAT(uang_jalan, 0, "id_ID")," - gaji = ", FORMAT(gaji, 0, "id_ID"))  as text, uang_jalan as uang_jalan, gaji as gaji, kode_rute as kode_rute')
        ->get();

      $count =  Rute::where('kode_rute', 'LIKE', '%' . $request->q . '%')
      ->when($jenismobil_id, function ($query, $jenismobil_id) {
        return $query->where('rute.jenismobil_id', $jenismobil_id);
        })
        ->when($customer_id, function ($query, $customer_id) {
            return $query->where('rute.customer_id', $customer_id);
        })
        ->when($muatan_id, function ($query, $muatan_id) {
            return $query->where('rute.muatan_id', $muatan_id);
        })
        ->when($first_rute_id, function ($query, $first_rute_id) {
            return $query->where('rute.first_rute_id', $first_rute_id);
        })
        ->when($last_rute_id, function ($query, $last_rute_id) {
            return $query->where('rute.last_rute_id', $last_rute_id);
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
}
