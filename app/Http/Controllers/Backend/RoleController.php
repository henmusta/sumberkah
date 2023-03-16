<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\KegiatanRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
//   public function __construct()
//   {
//     $this->middleware('can:backend-roles-list', ['only' => ['index', 'show']]);
//     $this->middleware('can:backend-roles-create', ['only' => ['index', 'create', 'store']]);
//     $this->middleware('can:backend-roles-edit', ['only' => ['index', 'edit', 'update']]);
//     $this->middleware('can:backend-roles-delete', ['only' => ['index', 'destroy']]);
//   }

  public function index(Request $request)
  {
    $config['page_title'] = "Roles";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Roles"],
    ];

    if ($request->ajax()) {
    //  $data = Role::where('parent', '0');
      $data = Role::query();
      return DataTables::of($data)
        ->addColumn('action', function ($row) {

          $actionBtn = '<div class="dropdown">
          <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
            Aksi <i class="mdi mdi-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li> <a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-id="' . $row->id . '" data-bs-name="' . $row->name . '" class="edit dropdown-item">Ubah</a></li>
            <li> <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
          </ul>
        </div>';
          return $actionBtn;

        })
        ->make(true);
    }

    return view('backend.roles.index', compact('config', 'page_breadcrumbs'));
  }


  public function show($id)
  {
    $config['page_title'] = "Detail Role";
    $page_breadcrumbs = [
      ['url' => route('backend.roles.index'), 'title' => "Detail  Role"],
      ['url' => '#', 'title' => "Detail Role"],
    ];

    $roles = Role::findOrFail($id);
    $data = [
      'role' => $roles,
    ];
    return view('backend.roles.show', compact('page_breadcrumbs', 'config', 'data'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:roles,name',
    ]);

    if ($validator->passes()) {
      Role::create([
        'name' => ucwords($request['name']),
      ]);

      $response = response()->json([
        'status' => 'success',
        'message' => 'Data berhasil disimpan'
      ]);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:roles,name,' . $id,
    ]);

    if ($validator->passes()) {
      $data = Role::find($id);
      $data->update([
        'name' => ucwords($request['name']),
      ]);
      // if($data->slug != 'super-admin'){
      //   $data->update([
      //     'name' => ucwords($request['name']),
      //   ]);
      // }
      $response = response()->json([
        'status' => 'success',
        'message' => 'Data berhasil diubah',
      ]);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy(Role $role)
  {
    $response = response()->json([
      'status' => 'error',
      'message' => 'Data gagal dihapus'
    ]);
    if ($role->slug != 'super-admin') {
      $role->delete();
      $response = response()->json([
        'status' => 'success',
        'message' => 'Data berhasil dihapus'
      ]);
    }
    return $response;
  }

  public function select2(Request $request)
  {
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $parent_true = $request->parent_true;
    $parent_false = $request->parent_false;
    $data = Role::where('roles.name', 'LIKE', '%' . $request->q . '%')
       ->when($parent_true, function ($query, $parent_true) {
            return $query->where('parent', '0');
       })
       ->when($parent_false, function ($query, $parent_false) {
        return $query->where('parent', '!=', '0');
        })
      ->orderBy('roles.name')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('roles.id, roles.name as text')
      ->get();

    $count = Role::where('roles.name', 'LIKE', '%' . $request->q . '%')
        ->when($parent_true, function ($query, $parent_true) {
            return $query->where('parent', '0');
         })
        ->when($parent_false, function ($query, $parent_false) {
           return $query->where('parent', '!=', '0');
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
