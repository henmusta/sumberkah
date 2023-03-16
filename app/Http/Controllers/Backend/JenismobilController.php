<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Jenismobil;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class JenismobilController extends Controller
{
    use ResponseStatus;
    public function index(Request $request)
    {
        $config['page_title'] = "Jenis Mobil";
        $page_breadcrumbs = [
          ['url' => 'jenismobil', 'title' => "Jenis Mobil"],
        ];
        if ($request->ajax()) {
            $data = Jenismobil::query();
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
                    class="edit dropdown-item">Edit</a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Delete</a>
                </div>
            </div>';
              })
              ->make(true);
          }

        return view('backend.jenismobil.index', compact('config', 'page_breadcrumbs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:jenismobil,name',
          ]);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                  $data =  Jenismobil::create([
                    'name' => ucwords($request['name']),
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
        'name' => 'required|unique:jenismobil,name,' . $id,
      ]);

      if ($validator->passes()) {
        DB::beginTransaction();
        try {
            $data = Jenismobil::find($id);
            $data->update([
              'name' => ucwords($request['name'])
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
      $data = Jenismobil::findOrFail($id);
      if ($data->delete()) {
        $response = response()->json($this->responseDelete(true));

      }
      return $response;
    }

    public function select2(Request $request)
    {
      $page = $request->page;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = Jenismobil::where('name', 'LIKE', '%' . $request->q . '%')
        ->orderBy('name')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, name as text')
        ->get();

      $count =  Jenismobil::where('name', 'LIKE', '%' . $request->q . '%')
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
