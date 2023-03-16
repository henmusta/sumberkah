<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Alamatrute;
use App\Models\Muatan;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class MuatanController extends Controller
{
    use ResponseStatus;
    public function index(Request $request)
    {
        $config['page_title'] = "Data Muatan";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Muatan"],
        ];
        if ($request->ajax()) {
            $data = Muatan::query();
            return DataTables::of($data)
              ->addColumn('action', function ($row) {
                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" >
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                    data-bs-id="' . $row->id . '"
                    data-bs-name="' . $row->name . '"
                    data-bs-keterangan="' . $row->keterangan . '"
                    class="edit dropdown-item">Edit</a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Delete</a>
                </div>
            </div>';
              })
              ->make(true);
          }

        return view('backend.muatan.index', compact('config', 'page_breadcrumbs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:muatan,name'
          ]);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                  $data =  Muatan::create([
                    'name' => ucwords($request['name'])
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
        'name' => 'required|unique:muatan,name,' . $id
      ]);

      if ($validator->passes()) {
        DB::beginTransaction();
        try {
            $data = Muatan::find($id);
            $data->update([
              'name' => ucwords($request['name']),
              'keterangan' =>$request['keterangan'],
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
      $data = Muatan::findOrFail($id);
      if ($data->delete()) {
        $response = response()->json($this->responseDelete(true));

      }
      return $response;
    }

    public function select2(Request $request)
    {
      $jenismobil_id = $request->jenismobil_id;
      $customer_id = $request->customer_id;
      $page = $request->page;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = Muatan::where('name', 'LIKE', '%' . $request->q . '%')
        ->when($jenismobil_id, function ($query, $jenismobil_id) {
            return $query->whereHas('rute', function ($query) use($jenismobil_id) {
                return $query->where('rute.jenismobil_id', $jenismobil_id);
            });
        })

        ->when($customer_id, function ($query, $customer_id) {
            return $query->whereHas('rute', function ($query) use($customer_id) {
                return $query->where('rute.customer_id', $customer_id);
            });
        })
        ->orderBy('name')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, name as text')
        ->get();

      $count =  Muatan::where('name', 'LIKE', '%' . $request->q . '%')
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
