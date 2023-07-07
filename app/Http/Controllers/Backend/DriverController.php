<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Mobil;
use App\Models\Joborder;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;


class DriverController extends Controller
{
    use ResponseStatus;

    function __construct()
    {
        $this->middleware('can:backend-driver-list', ['only' => ['index']]);
        $this->middleware('can:backend-driver-create', ['only' => ['create', 'store']]);
        $this->middleware('can:backend-driver-edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:backend-driver-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Driver";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Driver"],
        ];
        if ($request->ajax()) {
          $data = Driver::query();


          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $perm = [
                    'list' => Auth::user()->can('backend-driver-list'),
                    'create' => Auth::user()->can('backend-driver-create'),
                    'edit' => Auth::user()->can('backend-driver-edit'),
                    'delete' => Auth::user()->can('backend-driver-delete'),
                ];

                $validasi = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalValidasi" data-bs-id="' . $row->id . '"  data-bs-validasi="' . $row->validasi. '" class="edit dropdown-item">Validasi</a>';
                $aktifasi = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalAktif" data-bs-id="' . $row->id . '"
                                                                                             data-bs-aktif="' . $row->status_aktif. '"
                                                                                             data-bs-name="' . $row->name. '"
                                                                                             data-bs-tgl_aktif="' . $row->tgl_aktif. '"
                                                                                             class="edit dropdown-item">Aktifasi</a>';
                $show = '<a href="' . route('backend.driver.show', $row->id) . '" class="dropdown-item">Detail</a>';
                $edit = '<a class="dropdown-item" href="driver/' . $row->id . '/edit">Ubah</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';
                $cek_edit =  $row->validasi == '0'  ? $edit : '';
                $cek_delete =  $row->validasi == '0' ? $delete : '';

                $cek_level_validasi = Auth::user()->roles()->first()->level == '1' ? $validasi : '';
                $cek_level_aktifasi = Auth::user()->roles()->first()->level == '1' ? $aktifasi : '';
                $cek_perm_edit = $perm['edit'] == 'true' ? $cek_edit : '';
                $cek_perm_delete = $perm['delete'] == 'true' ? $cek_delete : '';

                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $show.'
                    '.  $cek_perm_edit. '
                    '.  $cek_perm_delete .'
                    '.  $cek_level_validasi.'
                    '.   $cek_level_aktifasi .'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.driver.index', compact('config', 'page_breadcrumbs'));
    }


    public function create()
    {
      $config['page_title'] = "Tambah Driver";
      $page_breadcrumbs = [
        ['url' => route('backend.driver.index'), 'title' => "Daftar Driver"],
        ['url' => '#', 'title' => "Tambah Driver"],
      ];
      return view('backend.driver.create', compact('page_breadcrumbs', 'config'));
    }


    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
            "name" => "required",
            "tgl_sim" =>"required",
            "tgl_aktif" => "required",
          ]);

          if ($validator->passes()) {
            DB::beginTransaction();
            $dimensions = [array('300', '300', 'driver')];
            try {
                  $image_foto = isset($request->image_foto) && !empty($request->image_foto) ? FileUpload::uploadImage('image_foto', $dimensions) : NULL;
                  $image_sim = isset($request->image_sim) && !empty($request->image_sim) ? FileUpload::uploadImage('image_sim', $dimensions) : NULL;
                  $image_ktp = isset($request->image_ktp) && !empty($request->image_ktp) ? FileUpload::uploadImage('image_ktp', $dimensions) : NULL;
                  $data = Driver::create([
                    'name' => $request['name'],
                    // 'kasbon',
                    'alamat' => $request['alamat'],
                    'telp' => $request['telp'],
                    'keterangan_driver' => $request['keterangan'],
                    'ktp' => $request['ktp'],
                    'sim' => $request['sim'],
                    'panggilan' => $request['panggilan'],
                    'tempat_lahir' => $request['tempat_lahir'],
                    'tgl_lahir' => $request['tgl_lahir'],
                    'image_foto' =>  $image_foto,
                    'image_sim' =>  $image_sim,
                    'image_ktp' => $image_ktp,
                    'tgl_sim' => $request['tgl_sim'],
                    'tgl_aktif' => $request['tgl_aktif'],
                    'darurat_name' => $request['darurat_name'],
                    'darurat_telp' => $request['darurat_telp'],
                    'darurat_ref' => $request['darurat_ref']
                  ]);
              DB::commit();
              $response = response()->json($this->responseStore(true, route('backend.driver.index')));
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
        $config['page_title'] = "Detail Driver";

        $page_breadcrumbs = [
          ['url' => route('backend.driver.index'), 'title' => "Detail Driver"],
          ['url' => '#', 'title' => "Update Driver"],
        ];
        $driver = Driver::findOrFail($id);
        $joborder = Joborder::where('driver_id', $id)->where('status_joborder', '0')->get();
        $data = [
          'driver' => $driver,
          'joborder' =>  $joborder
        ];

        return view('backend.driver.show', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function edit($id)
    {
        $config['page_title'] = "Update Driver";

        $page_breadcrumbs = [
          ['url' => route('backend.driver.index'), 'title' => "Daftar Driver"],
          ['url' => '#', 'title' => "Update Driver"],
        ];
        $driver = Driver::findOrFail($id);
        $data = [
          'driver' => $driver,
        ];

        return view('backend.driver.edit', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
        "name" => "required",
        "tgl_sim" =>"required",
        "tgl_aktif" => "required",
      ]);

      if ($validator->passes()) {
        $dimensions = [array('300', '300', 'driver')];
        DB::beginTransaction();
        try {
            $data = Driver::find($id);
            $image_foto = isset($request->image_foto) && !empty($request->image_foto) ? FileUpload::uploadImage('image_foto', $dimensions) : $data['image_foto'];
            $image_sim = isset($request->image_sim) && !empty($request->image_sim) ? FileUpload::uploadImage('image_sim', $dimensions) : $data['image_sim'];
            $image_ktp = isset($request->image_ktp) && !empty($request->image_ktp) ? FileUpload::uploadImage('image_ktp', $dimensions) : $data['image_ktp'];
            $data->update([
                'name' => $request['name'],
                // 'kasbon',
                'alamat' => $request['alamat'],
                'telp' => $request['telp'],
                'keterangan_driver' => $request['keterangan'],
                'ktp' => $request['ktp'],
                'sim' => $request['sim'],
                'panggilan' => $request['panggilan'],
                'tempat_lahir' => $request['tempat_lahir'],
                'tgl_lahir' => $request['tgl_lahir'],
                'image_foto' =>  $image_foto,
                'image_sim' =>  $image_sim,
                'image_ktp' => $image_ktp,
                'tgl_sim' => $request['tgl_sim'],
                'tgl_aktif' => $request['tgl_aktif'],
                'darurat_name' => $request['darurat_name'],
                'darurat_telp' => $request['darurat_telp'],
                'darurat_ref' => $request['darurat_ref']
            ]);
          DB::commit();
          $response = response()->json($this->responseStore(true, route('backend.driver.index')));

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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
         $data = Driver::findOrFail($id);
            if ($data->delete()) {
              Fileupload::deleteFile($data->image_foto, "images/driver","images/original");
              FileUpload::deleteFile($data->image_sim, "images/driver", "images/original");
              FileUpload::deleteFile($data->image_ktp, "images/driver", "images/original");
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
      $status_jalan = $request->status_jalan;
      $validasi = $request->validasi;
      $status_aktif = $request->status_aktif;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = Driver::where('name', 'LIKE', '%' . $request->q . '%')
      ->when($status_jalan, function ($query, $status_jalan) {
        return $query->where('status_jalan', '!=', $status_jalan);
        })
        ->when($validasi, function ($query, $validasi) {
            return $query->where('validasi', $validasi);
        })
        ->when($status_aktif, function ($query, $status_aktif) {
            return $query->where('status_aktif', $status_aktif);
        })
        ->orderBy('name')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id,  CASE
            WHEN panggilan is null THEN name
            ELSE CONCAT(name," (", IFNULL(panggilan,""),")" )
            END as text, kasbon as kasbon')
        ->get();

      $count = Driver::where('name', 'LIKE', '%' . $request->q . '%')
      ->when($status_jalan, function ($query, $status_jalan) {
        return $query->where('status_jalan', '!=', $status_jalan);
     })
     ->when($validasi, function ($query, $validasi) {
        return $query->where('validasi', $validasi);
     })
     ->when($status_aktif, function ($query, $status_aktif) {
        return $query->where('status_aktif', $status_aktif);
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


    public function validasi(Request $request)
    {
        // dd( $request);
          $validator = Validator::make($request->all(), [
            'id' => 'required',
            'validasi' => 'required',
          ]);
        //   dd($request['id']);
          if ($validator->passes()) {
            $data = Driver::find($request['id']);
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

    public function aktivasi(Request $request)
    {
        // dd( $request);
          $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status_aktif' => 'required',
            'tgl_aktif' => 'required',
          ]);
        //   dd($request['id']);
          if ($validator->passes()) {
            $data = Driver::find($request['id']);
            DB::beginTransaction();
            try {
                  $data->update([
                    'status_aktif' => $request['status_aktif'],
                    'tgl_aktif' => $request['tgl_aktif'],
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


}
