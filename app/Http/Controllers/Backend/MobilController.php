<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Mobil;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class MobilController extends Controller
{
    use ResponseStatus;

    function __construct()
    {
        $this->middleware('can:backend-mobil-list', ['only' => ['index']]);
        $this->middleware('can:backend-mobil-create', ['only' => ['create', 'store']]);
        $this->middleware('can:backend-mobil-edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:backend-mobil-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Mobil";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Mobil"],
        ];
        if ($request->ajax()) {
          $data = Mobil::with('merkmobil', 'tipemobil', 'jenismobil');


          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $perm = [
                    'list' => Auth::user()->can('backend-mobil-list'),
                    'create' => Auth::user()->can('backend-mobil-create'),
                    'edit' => Auth::user()->can('backend-mobil-edit'),
                    'delete' => Auth::user()->can('backend-mobil-delete'),
                ];



                $validasi = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalValidasi" data-bs-id="' . $row->id . '"  data-bs-validasi="' . $row->validasi. '" class="edit dropdown-item">Validasi</a>';
                $show = '<a href="' . route('backend.mobil.show', $row->id) . '" class="dropdown-item" target="_blank">Detail</a>';
                $edit = '<a class="dropdown-item" href="mobil/' . $row->id . '/edit">Ubah</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';

                $cek_edit =  $row->validasi == '0'  ? $edit : '';
                $cek_delete =  $row->validasi == '0' ? $delete : '';
                $cek_level_validasi = Auth::user()->roles()->first()->level == '1' ? $validasi : '';
          //      $cek_perm_validasi = $perm['edit'] == 'true' ? $cek_edit : '';
                $cek_perm_edit = $perm['edit'] == 'true' ? $cek_edit : '';
                $cek_perm_delete = $perm['delete'] == 'true' ? $cek_delete : '';

                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $show.'
                    '.  $cek_perm_edit .'
                    '.  $cek_perm_delete .'
                    '. $cek_level_validasi .'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.mobil.index', compact('config', 'page_breadcrumbs'));
    }


    public function create()
    {
      $config['page_title'] = "Tambah Kendaraan";
      $page_breadcrumbs = [
        ['url' => route('backend.mobil.index'), 'title' => "Daftar Kendaraan"],
        ['url' => '#', 'title' => "Tambah Kendaraan"],
      ];
      return view('backend.mobil.create', compact('page_breadcrumbs', 'config'));
    }


    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
            "merkmobil_id" => "required",
            "mobilrincian_id" => "required",
            "tipemobil_id" => "required",
            "jenismobil_id" => "required",
            'nomor_plat' => 'required|unique:mobil,nomor_plat',
            "tahun" => "required",
            "berlaku_stnk" => "required",
            "berlaku_pajak" => "required",
            "berlaku_ijin_usaha" => "required",
            "berlaku_kir" => "required",
            "berlaku_ijin_bongkar" =>"required",
            "dump" => "required",

          ]);

          if ($validator->passes()) {
            DB::beginTransaction();
            $dimensions = [array('300', '300', 'mobil')];
            try {
                  $image_mobil = isset($request->image_mobil) && !empty($request->image_mobil) ? FileUpload::uploadImage('image_mobil', $dimensions) : NULL;
                  $image_stnk = isset($request->image_stnk) && !empty($request->image_stnk) ? FileUpload::uploadImage('image_stnk', $dimensions) : NULL;
                  $data = Mobil::create([
                    "mobilrincian_id" => $request['mobilrincian_id'],
                    'nomor_plat' => $request['nomor_plat'],
                    'nomor_rangka'  => $request['nomor_rangka'],
                    'nomor_mesin'  => $request['nomor_mesin'],
                    'nomor_stnk'  => $request['nomor_stnk'],
                    'nomor_ijin_usaha'  => $request['nomor_ijin_usaha'],
                    'nomor_ijin_bongkar'  => $request['nomor_ijin_bongkar'],
                    'nomor_bpkb'  => $request['nomor_bpkb'],
                    'jenismobil_id'  => $request['jenismobil_id'],
                    'keterangan_mobil'  => $request['keterangan'],
                    'merkmobil_id'  => $request['merkmobil_id'],
                    'tipemobil_id'  => $request['tipemobil_id'],
                    'dump'  => $request['dump'],
                    'tahun'  => $request['tahun'],
                    'berlaku_stnk'  => $request['berlaku_stnk'],
                    'berlaku_pajak'  => $request['berlaku_pajak'],
                    'kir'  => $request['kir'],
                    'berlaku_kir'  => $request['berlaku_kir'],
                    'berlaku_ijin_usaha'  => $request['berlaku_ijin_usaha'],
                    'berlaku_ijin_bongkar'  => $request['berlaku_ijin_bongkar'],
                    'image_mobil'  =>  $image_mobil,
                    'image_stnk'  => $image_stnk,
                  ]);
              DB::commit();
              $response = response()->json($this->responseStore(true, route('backend.mobil.index')));
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
        $config['page_title'] = "Detail Kendaraan";

        $page_breadcrumbs = [
          ['url' => route('backend.mobil.index'), 'title' => "Detail Kendaraan"],
          ['url' => '#', 'title' => "Update Kendaraan"],
        ];
        $mobil = Mobil::with('merkmobil', 'tipemobil', 'jenismobil')->findOrFail($id);
        $data = [
          'mobil' => $mobil,
        ];

        return view('backend.mobil.show', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function edit($id)
    {
        $config['page_title'] = "Update Kendaraan";

        $page_breadcrumbs = [
          ['url' => route('backend.mobil.index'), 'title' => "Daftar Kendaraan"],
          ['url' => '#', 'title' => "Update Kendaraan"],
        ];
        $mobil = Mobil::with('merkmobil', 'tipemobil', 'jenismobil')->findOrFail($id);
        $data = [
          'mobil' => $mobil,
        ];

        return view('backend.mobil.edit', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
        "merkmobil_id" => "required",
        "mobilrincian_id" => "required",
        "tipemobil_id" => "required",
        "jenismobil_id" => "required",
        'nomor_plat' => 'required|unique:mobil,nomor_plat,' . $id,
        "tahun" => "required",
        "berlaku_stnk" => "required",
        "berlaku_pajak" => "required",
        "berlaku_ijin_usaha" => "required",
        "berlaku_kir" => "required",
        "berlaku_ijin_bongkar" =>"required",
        "dump" => "required",
      ]);

      if ($validator->passes()) {
        $dimensions = [array('300', '300', 'mobil')];
        DB::beginTransaction();
        try {
            $data = Mobil::find($id);
            $image_mobil = isset($request->image_mobil) && !empty($request->image_mobil) ? FileUpload::uploadImage('image_mobil', $dimensions) :  $data['image_mobil'];
            $image_stnk = isset($request->image_stnk) && !empty($request->image_stnk) ? FileUpload::uploadImage('image_stnk', $dimensions) :  $data['image_stnk'];
            $data->update([
                    "mobilrincian_id" => $request['mobilrincian_id'],
                    'nomor_plat' => $request['nomor_plat'],
                    'nomor_rangka'  => $request['nomor_rangka'],
                    'nomor_mesin'  => $request['nomor_mesin'],
                    'nomor_stnk'  => $request['nomor_stnk'],
                    'nomor_ijin_usaha'  => $request['nomor_ijin_usaha'],
                    'nomor_ijin_bongkar'  => $request['nomor_ijin_bongkar'],
                    'nomor_bpkb'  => $request['nomor_bpkb'],
                    'jenismobil_id'  => $request['jenismobil_id'],
                    'keterangan_mobil'  => $request['keterangan'],
                    'merkmobil_id'  => $request['merkmobil_id'],
                    'tipemobil_id'  => $request['tipemobil_id'],
                    'dump'  => $request['dump'],
                    'tahun'  => $request['tahun'],
                    'berlaku_stnk'  => $request['berlaku_stnk'],
                    'berlaku_pajak'  => $request['berlaku_pajak'],
                    'kir'  => $request['kir'],
                    'berlaku_kir'  => $request['berlaku_kir'],
                    'berlaku_ijin_usaha'  => $request['berlaku_ijin_usaha'],
                    'berlaku_ijin_bongkar'  => $request['berlaku_ijin_bongkar'],
                    'image_mobil'  =>  $image_mobil,
                    'image_stnk'  => $image_stnk,
            ]);
          DB::commit();
          $response = response()->json($this->responseStore(true, route('backend.mobil.index')));

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
         $data = Mobil::findOrFail($id);
            if ($data->delete()) {
              Fileupload::deleteFile($data->image_mobil, "images/mobil", "images/original");
              FileUpload::deleteFile($data->image_stnk, "images/mobil", "images/original");
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
      $jenismobil_id = $request->jenismobil_id;
      $driver_id = $request->driver_id;
      $status_jalan = $request->status_jalan;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = Mobil::where('mobil.nomor_plat', 'LIKE', '%' . $request->q . '%')
        ->when($jenismobil_id, function ($query, $jenismobil_id) {
            return $query->where('mobil.jenismobil_id', $jenismobil_id);
         })
         ->when($status_jalan, function ($query, $status_jalan) {
            return $query->where('mobil.status_jalan', '!=', $status_jalan);
         })
         ->when($driver_id, function ($query, $driver_id) {
            return $query->whereHas('joborder', function ($query) use($driver_id) {
                return $query->where('joborder.driver_id', $driver_id);
            });
         })
        ->leftJoin('jenismobil',  'jenismobil.id', '=', 'mobil.jenismobil_id')
        ->orderBy('mobil.nomor_plat')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('mobil.id as id, CONCAT(mobil.nomor_plat,"-",jenismobil.name,"-", IF(dump = "Iya", "Dump", "No Dump")) as text')
        ->get();

      $count = Mobil::where('mobil.nomor_plat', 'LIKE', '%' . $request->q . '%')
        ->when($driver_id, function ($query, $driver_id) {
            return $query->whereHas('joborder', function ($query) use($driver_id) {
                return $query->where('joborder.driver_id', $driver_id);
            });
        })
        ->leftJoin('jenismobil',  'jenismobil.id', '=', 'mobil.jenismobil_id')
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
            $data = Mobil::find($request['id']);
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

}
