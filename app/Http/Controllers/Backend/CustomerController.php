<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;
use Cache;
class CustomerController extends Controller
{
    use ResponseStatus;


    function __construct()
    {
        $this->middleware('can:backend-customer-list', ['only' => ['index']]);
        $this->middleware('can:backend-customer-create', ['only' => ['create', 'store']]);
        $this->middleware('can:backend-customer-edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:backend-customer-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $config['page_title'] = "Table Customer";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Table Customer"],
        ];
        // dd(Cache::has('is_online' . auth()->user()->id));


        // $perm_list = Auth::user()->can('backend-customer-list');
        // dd($perm_list);
        if ($request->ajax()) {
            $data = Customer::query();
            return DataTables::of($data)
              ->addColumn('action', function ($row) {
                $perm = [
                    'list' => Auth::user()->can('backend-customer-list'),
                    'create' => Auth::user()->can('backend-customer-create'),
                    'edit' => Auth::user()->can('backend-customer-edit'),
                    'delete' => Auth::user()->can('backend-customer-delete'),
                ];


                $validasi = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalValidasi" data-bs-id="' . $row->id . '"  data-bs-validasi="' . $row->validasi. '" class="edit dropdown-item">Validasi</a>';

                $edit = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                data-bs-id="' . $row->id . '"
                data-bs-name="' . $row->name . '"
                data-bs-alamat="' . $row->alamat . '"
                data-bs-kontak="' . $row->kontak . '"
                data-bs-telp="' . $row->telp . '"
                data-bs-keterangan="' . $row->keterangan_customer . '"
                class="edit dropdown-item">Ubah</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Delete</a>';

                $cek_level_validasi = Auth::user()->roles()->first()->level == '1' ? $validasi : '';
                $cek_edit =  $row->validasi == '0' ? $edit : '';
                $cek_delete =  $row->validasi == '0' ? $delete : '';

                $cek_perm_edit = $perm['edit'] == 'true' ? $cek_edit : '';
                $cek_perm_delete = $perm['delete'] == 'true' ? $cek_delete : '';

                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>

                <div class="dropdown-menu" data-popper-placement="bottom-start" >
                    '. $cek_perm_edit .'
                    '. $cek_perm_delete .'
                    '. $cek_level_validasi . '
                </div>
            </div>';
              })
              ->make(true);
          }

        return view('backend.customer.index', compact('config', 'page_breadcrumbs'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:customer,name'
          ]);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                  $data =  Customer::create([
                    'name' => ucwords($request['name']),
                    'alamat' => $request['alamat'],
                    'kontak' => $request['kontak'],
                    'telp' => $request['telp'],
                    'keterangan_customer' => $request['keterangan'],
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


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:customer,name,' . $id,

      ]);

      if ($validator->passes()) {
        DB::beginTransaction();
        try {
            $data = Customer::find($id);
            $data->update([
              'name' => ucwords($request['name']),
              'alamat' => $request['alamat'],
              'kontak' => $request['kontak'],
              'telp' => $request['telp'],
              'keterangan_customer' => $request['keterangan'],
            ]);
          DB::commit();
          $response = response()->json($this->responseUpdate(true));

        } catch (Throwable $throw) {
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
      $data = Customer::findOrFail($id);
      if ($data->delete()) {
        $response = response()->json($this->responseDelete(true));

      }
      return $response;
    }

    public function select2(Request $request)
    {
      $page = $request->page;
      $jenismobil_id = $request->jenismobil_id;
      $validasi_id = $request->validasi_id;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = Customer::where('name', 'LIKE', '%' . $request->q . '%')
       ->when($jenismobil_id, function ($query, $jenismobil_id) {
            return $query->whereHas('rute', function ($query) use($jenismobil_id) {
                return $query->where('rute.jenismobil_id', $jenismobil_id);
             });
        })
        ->when($validasi_id, function ($query, $validasi_id) {
            return $query->where('validasi', $validasi_id);
        })
        ->orderBy('name')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, name as text')
        ->get();



        $count =  Customer::where('name', 'LIKE', '%' . $request->q . '%')
        ->when($jenismobil_id, function ($query, $jenismobil_id) {
            return $query->whereHas('rute', function ($query) use($jenismobil_id) {
                return $query->where('rute.jenismobil_id', $jenismobil_id);
             });
         })
         ->when($validasi_id, function ($query, $validasi_id) {
            return $query->where('validasi', $validasi_id);
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
            $data = Customer::find($request['id']);
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
