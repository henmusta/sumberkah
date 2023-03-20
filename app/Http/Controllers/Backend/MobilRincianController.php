<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MobilRincian;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;


class MobilRincianController extends Controller
{
    use ResponseStatus;

    function __construct()
    {
        $this->middleware('can:backend-mobilrincian-list', ['only' => ['index']]);
        $this->middleware('can:backend-mobilrincian-create', ['only' => ['create', 'store']]);
        $this->middleware('can:backend-mobilrincian-edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:backend-mobilrincian-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Tipe Kendaraan";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Tipe Kendaraan"],
        ];
        if ($request->ajax()) {
          $data = MobilRincian::with('merkmobil', 'tipemobil', 'jenismobil');
          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $validasi = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalValidasi" data-bs-id="' . $row->id . '"  data-bs-validasi="' . $row->validasi. '" class="edit dropdown-item">Validasi</a>';
                $show = '<a href="' . route('backend.mobilrincian.show', $row->id) . '" class="dropdown-item">Detail</a>';
                $edit = '<a class="dropdown-item" href="mobilrincian/' . $row->id . '/edit">Ubah</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';

                $perm = [
                    'list' => Auth::user()->can('backend-customer-list'),
                    'create' => Auth::user()->can('backend-customer-create'),
                    'edit' => Auth::user()->can('backend-customer-edit'),
                    'delete' => Auth::user()->can('backend-customer-delete'),
                ];
                $cek_level_validasi = Auth::user()->roles()->first()->level == '1' ? $validasi : '';
                $cek_edit =  $row->validasi == '0'  ? $edit : '';
                $cek_delete =  $row->validasi == '0' ? $delete : '';

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
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.mobilrincian.index', compact('config', 'page_breadcrumbs'));
    }

    public function create()
    {
      $config['page_title'] = "Tambah Tipe Kendaraan";
      $page_breadcrumbs = [
        ['url' => route('backend.mobilrincian.index'), 'title' => "Daftar Tipe Kendraan"],
        ['url' => '#', 'title' => "Tambah Tipe Kendaraan"],
      ];
      return view('backend.mobilrincian.create', compact('page_breadcrumbs', 'config'));
    }


    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
            "merkmobil_id" => "required",
            "tipemobil_id" => "required",
            "jenismobil_id" => "required",
            "dump" => "required"
          ]);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                  $data = MobilRincian::create([
                    'jenismobil_id'  => $request['jenismobil_id'],
                    'merkmobil_id'  => $request['merkmobil_id'],
                    'tipemobil_id'  => $request['tipemobil_id'],
                    'dump'  => $request['dump']
                  ]);
              DB::commit();
              $response = response()->json($this->responseStore(true, route('backend.mobilrincian.index')));
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
        $config['page_title'] = "Update Tipe Kendaraan";

        $page_breadcrumbs = [
          ['url' => route('backend.mobilrincian.index'), 'title' => "Daftar Tipe Kendaraan"],
          ['url' => '#', 'title' => "Update Tipe Kendaraan"],
        ];
        $mobil = Mobilrincian::with('merkmobil', 'tipemobil', 'jenismobil')->findOrFail($id);
        $data = [
          'mobil' => $mobil,
        ];

        return view('backend.mobilrincian.edit', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
             "merkmobil_id" => "required",
            "tipemobil_id" => "required",
            "jenismobil_id" => "required",
            "dump" => "required"
      ]);

      if ($validator->passes()) {
        $dimensions = [array('300', '300', 'mobil')];
        DB::beginTransaction();
        try {
            $data = MobilRincian::find($id);
            $data->update([
                'jenismobil_id'  => $request['jenismobil_id'],
                'merkmobil_id'  => $request['merkmobil_id'],
                'tipemobil_id'  => $request['tipemobil_id'],
                'dump'  => $request['dump']
            ]);
          DB::commit();
          $response = response()->json($this->responseStore(true, route('backend.mobilrincian.index')));
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
        $config['page_title'] = "Detail Tipe Kendaraan";

        $page_breadcrumbs = [
          ['url' => route('backend.mobilrincian.index'), 'title' => "Detail Tipe Kendaraan"],
          ['url' => '#', 'title' => "Update Tipe Kendaraan"],
        ];
        $mobil = MobilRincian::with('merkmobil', 'tipemobil', 'jenismobil')->findOrFail($id);
        $data = [
          'mobil' => $mobil,
        ];

        return view('backend.mobilrincian.show', compact('page_breadcrumbs', 'config', 'data'));
    }

    public function datatablecekmobilrincian(Request $request)
    {
      if ($request->ajax()) {
            $jenismobil_id = $request->jenismobil_id;
            $merkmobil_id = $request->merkmobil_id;
            $tipemobil_id = $request->tipemobil_id;
            $dump = $request->dump;
            // dd($create );
            $data = MobilRincian::with('merkmobil', 'tipemobil', 'jenismobil')->where('mobilrincian.validasi', '1')
            ->when($jenismobil_id, function ($query, $jenismobil_id) {
                return $query->where('mobilrincian.jenismobil_id', $jenismobil_id);
            })
            ->when( $merkmobil_id, function ($query,  $merkmobil_id) {
                return $query->where('mobilrincian.merkmobil_id',  $merkmobil_id);
            })
            ->when($tipemobil_id, function ($query, $tipemobil_id) {
                return $query->where('mobilrincian.tipemobil_id', $tipemobil_id);
            })
            ->when($dump, function ($query, $dump) {
                return $query->where('mobilrincian.dump', $dump);
            });
            // ->where('customer_id', $request['customer_id']);
        return DataTables::of($data)
          ->make(true);
      }
    }


    public function select2(Request $request)
    {
      $page = $request->page;
      $jenismobil_id = $request->jenismobil_id;
      $merkmobil_id = $request->merkmobil_id;
      $tipemobil_id = $request->tipemobil_id;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = MobilRincian::where('mobilrincian.id', 'LIKE', '%' . $request->q . '%')
         ->when($jenismobil_id, function ($query, $jenismobil_id) {
            return $query->where('mobilrincian.jenismobil_id', $jenismobil_id);
         })
         ->when( $merkmobil_id, function ($query,  $merkmobil_id) {
            return $query->where('mobilrincian.merkmobil_id',  $merkmobil_id);
         })
         ->when($tipemobil_id, function ($query, $tipemobil_id) {
            return $query->where('mobilrincian.tipemobil_id', $tipemobil_id);
         })
        ->leftJoin('jenismobil',  'jenismobil.id', '=', 'mobilrincian.jenismobil_id')
        ->leftJoin('merkmobil',  'merkmobil.id', '=', 'mobilrincian.merkmobil_id')
        ->leftJoin('tipemobil',  'tipemobil.id', '=', 'mobilrincian.tipemobil_id')
        ->orderBy('mobilrincian.id')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('mobilrincian.id as id, CONCAT(merkmobil.name,"-",tipemobil.name, "-",jenismobil.name,"-", IF(dump = "Iya", "Dump", "No Dump")) as text')
        ->get();

        $count = MobilRincian::where('mobilrincian.id', 'LIKE', '%' . $request->q . '%')
            ->when($jenismobil_id, function ($query, $jenismobil_id) {
            return $query->where('mobilrincian.jenismobil_id', $jenismobil_id);
            })
            ->when( $merkmobil_id, function ($query,  $merkmobil_id) {
            return $query->where('mobilrincian.merkmobil_id',  $merkmobil_id);
            })
            ->when($tipemobil_id, function ($query, $tipemobil_id) {
            return $query->where('mobilrincian.tipemobil_id', $tipemobil_id);
            })
        ->leftJoin('jenismobil',  'jenismobil.id', '=', 'mobilrincian.jenismobil_id')
        ->leftJoin('merkmobil',  'merkmobil.id', '=', 'mobilrincian.merkmobil_id')
        ->leftJoin('tipemobil',  'tipemobil.id', '=', 'mobilrincian.tipemobil_id')
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


    public function findmobilrincian(Request $request)
    {
    //    dd($request['konfirmasijo_id']);

      $Mobil = MobilRincian::with('merkmobil', 'tipemobil', 'jenismobil')->findOrFail($request['id']);
      $data = [
        'mobil' => $Mobil,
      ];

      return response()->json($data);
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
            $data = MobilRincian::find($request['id']);
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
         $data = MobilRincian::findOrFail($id);
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


}
