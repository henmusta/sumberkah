<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Alamatrute;
use App\Models\Jenismobil;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class AlamatruteController extends Controller
{
    use ResponseStatus;
    public function index(Request $request)
    {
        $config['page_title'] = "Alamat Rute";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Alamat Rute"],
        ];
        if ($request->ajax()) {
            $data = Alamatrute::query();
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

        return view('backend.alamatrute.index', compact('config', 'page_breadcrumbs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:alamatrute,name',
          ]);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                  $data =  Alamatrute::create([
                    'name' => ucwords($request['name']),
                    'keterangan' =>$request['keterangan'],
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
        'name' => 'required|unique:alamatrute,name,' . $id,
      ]);

      if ($validator->passes()) {
        DB::beginTransaction();
        try {
            $data = Alamatrute::find($id);
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
      $data = Alamatrute::findOrFail($id);
      if ($data->delete()) {
        $response = response()->json($this->responseDelete(true));

      }
      return $response;
    }

    public function select2(Request $request)
    {
      $jenismobil_id = $request->jenismobil_id;
      $customer_id = $request->customer_id;
      $muatan_id = $request->muatan_id;
      $page = $request->page;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = Alamatrute::where('name', 'LIKE', '%' . $request->q . '%')
        ->orderBy('name')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, name as text')
        ->get();

      $count =  Alamatrute::where('name', 'LIKE', '%' . $request->q . '%')
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

    public function select2first(Request $request)
    {
      $jenismobil_id = $request->jenismobil_id;
      $customer_id = $request->customer_id;
      $muatan_id = $request->muatan_id;
      $tipe = $request->tipe;
      $page = $request->page;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = Alamatrute::where('name', 'LIKE', '%' . $request->q . '%')
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
        ->when($muatan_id, function ($query, $muatan_id) {
            return $query->whereHas('rute', function ($query) use($muatan_id) {
                return $query->where('rute.muatan_id', $muatan_id);
            });
        })
        ->orderBy('name')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, name as text')
        ->get();

      $count =  Alamatrute::where('name', 'LIKE', '%' . $request->q . '%')
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
        ->when($muatan_id, function ($query, $muatan_id) {
            return $query->whereHas('rute', function ($query) use($muatan_id) {
                return $query->where('rute.muatan_id', $muatan_id);
            });
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

    public function select2last(Request $request)
    {
      $jenismobil_id = $request->jenismobil_id;
      $customer_id = $request->customer_id;
      $muatan_id = $request->muatan_id;
      $first_rute_id = $request->first_rute_id;
      $page = $request->page;
      $resultCount = 10;
      $offset = ($page - 1) * $resultCount;
      $data = Alamatrute::where('name', 'LIKE', '%' . $request->q . '%')
        ->when($jenismobil_id, function ($query, $jenismobil_id) {
            return $query->whereHas('rute_last', function ($query) use($jenismobil_id) {
                return $query->where('rute.jenismobil_id', $jenismobil_id);
            });
        })
        ->when($customer_id, function ($query, $customer_id) {
            return $query->whereHas('rute_last', function ($query) use($customer_id) {
                return $query->where('rute.customer_id', $customer_id);
            });
        })
        ->when($muatan_id, function ($query, $muatan_id) {
            return $query->whereHas('rute_last', function ($query) use($muatan_id) {
                return $query->where('rute.muatan_id', $muatan_id);
            });
        })
        ->when($first_rute_id, function ($query, $first_rute_id) {
            return $query->whereHas('rute_last', function ($query) use($first_rute_id) {
                return $query->where('rute.first_rute_id', $first_rute_id);
            });
         })
        ->orderBy('name')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, name as text')
        ->get();

      $count =  Alamatrute::where('name', 'LIKE', '%' . $request->q . '%')
        ->when($jenismobil_id, function ($query, $jenismobil_id) {
            return $query->whereHas('rute_last', function ($query) use($jenismobil_id) {
                return $query->where('rute.jenismobil_id', $jenismobil_id);
            });
        })
        ->when($customer_id, function ($query, $customer_id) {
            return $query->whereHas('rute_last', function ($query) use($customer_id) {
                return $query->where('rute.customer_id', $customer_id);
            });
        })
        ->when($muatan_id, function ($query, $muatan_id) {
            return $query->whereHas('rute_last', function ($query) use($muatan_id) {
                return $query->where('rute.muatan_id', $muatan_id);
            });
         })
         ->when($first_rute_id, function ($query, $first_rute_id) {
            return $query->whereHas('rute_last', function ($query) use($first_rute_id) {
                return $query->where('rute.first_rute_id', $first_rute_id);
            });
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
