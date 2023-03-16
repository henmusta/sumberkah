<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MenuManager;
use App\Models\MenuPermission;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuPermissionController extends Controller
{
  use ResponseStatus;

//  public function __construct()
//  {
//    $this->middleware('can:menu-list', ['only' => ['index', 'show']]);
//    $this->middleware('can:menu-create', ['only' => ['index', 'create', 'store']]);
//    $this->middleware('can:menu-edit', ['only' => ['index', 'edit', 'update']]);
//    $this->middleware('can:menu-delete', ['only' => ['index', 'destroy']]);
//  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|unique:menu_permissions,title',
      'icon' => 'string',
      'type' => 'required',
    ]);

    if ($validator->passes()) {
      $data = MenuPermission::create([
        'title' => ucwords($request['title']),
        'icon' => $request['icon'],
        'type' => $request['type'],
      ]);

      if ($data->save()) {
        $response = response()->json($this->responseStore(true));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:menu_permissions,title,' . $id,
      'type' => 'required',
    ]);

    if ($validator->passes()) {
      $data = MenuPermission::find($id);
      $data->update([
        'title' => ucwords($request['title']),
        'icon' => $request['icon'],
        'type' => $request['type'],
      ]);
      $response = response()->json($this->responseUpdate(true));
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy(MenuPermission $menuPermission)
  {
    if ($menuPermission->delete()) {
      $response = response()->json($this->responseDelete(true));
    }
    return $response;
  }

  public function select2(Request $request)
  {

    $type = $request['type'];
    $role_id = $request['role_id'];
    $menuManager = '';
    if(isset($role_id)){
        $menuManager = MenuManager::where('role_id', $request['role_id'])->whereNotNull('menu_permission_id')->get();
    }
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $data = MenuPermission::where('title', 'LIKE', '%' . $request->q . '%')
      ->when($type, function ($query, $type) {
        return $query->where('type', $type);
       })
       ->when($menuManager, function ($query, $menuManager) {
        return $query->whereNotIn('id', $menuManager->pluck('menu_permission_id'));
       })
      ->orderBy('title')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, title as text')
      ->get();

    $count = MenuPermission::where('title', 'LIKE', '%' . $request->q . '%')
       ->when($menuManager, function ($query, $menuManager) {
        return $query->whereNotIn('id', $menuManager->pluck('menu_permission_id'));
       })
      ->when($type, function ($query, $type) {
        return $query->where('type', $type);
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
