<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Models\Role;
use App\Models\Jabatan;
use App\Models\User;
use App\Rules\MatchOldPassword;
use App\Traits\ResponseStatus;
use App\Models\MenuManager;
use App\Models\MenuPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yajra\DataTables\Facades\DataTables;
use Throwable;
use Cache;
class UserController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:backend-users-list', ['only' => ['index', 'show']]);
    // $this->middleware('can:backend-users-create', ['only' => ['create', 'store']]);
    // $this->middleware('can:backend-users-edit', ['only' => ['edit', 'update']]);
    // $this->middleware('can:backend-users-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {


    // dd(Auth::user()->roles()->first());
    $config['page_title'] = "Pengguna Aplikasi";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Pengguna Aplikasi"],
    ];

    if ($request->ajax()) {
      $data = User::with('roles');
      $data->orderBy('name', 'ASC');
      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
        return '<div class="dropdown">
                    <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                        Aksi <i class="mdi mdi-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                        <a class="dropdown-item" href="users/' . $row->id . '/edit">Ubah</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalReset" data-bs-id="' . $row->id . '" class="dropdown-item">Reset Password</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalOffline" data-bs-id="' . $row->id . '" class="dropdown-item">Trigger OFFLINE</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>
                    </div>
                </div>';

        })
        ->addColumn('cek_online', function ($row) {
            $cek_online = Cache::has('is_online' . $row->id);
            return $cek_online;
        })
        ->editColumn('image', function (User $user) {
           $data = asset('assets/backend/images/users/avatar-1.jpg');
          if(isset($user->image)){
           $data =  asset("/storage/images/thumbnail/$user->image");
          }
          return '<img class="rounded-circle" src="'.$data.'"alt="photo" style="width:75px; height: 75px;">';
        })
        ->rawColumns(['image', 'action', 'cek_online'])
        ->make(true);
    }
    return view('backend.users.index', compact('config', 'page_breadcrumbs'));
  }

  public function create()
  {
    $config['page_title'] = "Tambah Pengguna";
    $page_breadcrumbs = [
      ['url' => route('backend.users.index'), 'title' => "Daftar Pengguna"],
      ['url' => '#', 'title' => "Tambah Pengguna"],
    ];
    return view('backend.users.create', compact('page_breadcrumbs', 'config'));
  }

  public function edit($id)
  {

    $config['page_title'] = "Edit Pengguna";

    $page_breadcrumbs = [
      ['url' => route('backend.users.index'), 'title' => "Daftar Pengguna"],
      ['url' => '#', 'title' => "Edit Pengguna"],
    ];
    $logInUser = Auth::user()->roles()->first()->slug ?? NULL;
    if ($id == Auth::id() || in_array($logInUser, ['super-admin', 'admin'])) {
      $user = User::findOrFail($id);
      $roles = Role::query()->select('slug');
      $userRole = $user->roles()->first();
      $roles->when($userRole->slug != 'super-admin', function ($q) {
        return $q->where('slug', '!=', 'super-admin')->pluck('slug', 'slug');
      });

      $data = [
        'user' => $user,
        'roles' => $roles->get()->toArray(),
        'userRole' => $userRole
      ];
    } else {
      return abort('401', 'Unauthorized');
    }

    return view('backend.users.edit', compact('page_breadcrumbs', 'config', 'data'));
  }



  public function store(Request $request)
  {

    $validator = Validator::make($request->all(), [
        'role_id' => 'required|integer',
        'name' => 'required',
        'password' => 'required|between:6,255|confirmed',
        'email' => 'required|email|unique:users,email',
        'username' => 'required|unique:users,username',
        'active' => 'required|between:0,1',
        'image' => 'image|mimes:jpg,png,jpeg',
      ]);


    if ($validator->passes()) {
      $dimensions = [array('300', '300', 'thumbnail')];
      DB::beginTransaction();
      try {
        $img = isset($request->image) && !empty($request->image) ? FileUpload::uploadImage('image', $dimensions) : NULL;
        $data = User::create([
          'name' => ucwords($request['name']),
          'image' => $img,
          'email' => $request['email'],
          'username' => $request['username'],
          'password' => Hash::make($request['password']),
          'active' => $request['active'],
        ]);
        if($data->save()){
            $role = Role::find($request['role_id']);
            $new_role = $role['name'].'-'.$request['name'];
            $create_new_role = Role::create([
                'name' => ucwords($new_role),
                'slug' => $new_role,
                'parent' =>  $role['id'],
                'level' =>  $role['level']
            ]);
            $data->markEmailAsVerified();
            $data->roles()->attach($create_new_role);

            $get_menumanager = MenuManager::with('menupermission', 'permissions')->where('role_id', $role['id'])->get();
          //  dd($Menumanager);
            $parent = array();
            foreach($get_menumanager as $key => $val){
                $menu_permisi =  $val->menupermission['id'] ?? null;

                // if($val['parent_id'] == 0){
                //     $parent[$key][]= $val['parent_id'];
                //     $parent[$key][]= $val['title'];
                //     $parent[$key][]= $val['role_id'];
                //     $parent[$key][]= $val['menu_permission_id'];
                // }
                 $menuManager = MenuManager::create([
                    'menu_permission_id' => $menu_permisi,
                    'role_id' => $create_new_role['id'],
                    'title' => $val['title'],
                    'path_url' => $val['path_url'],
                    'icon' => $val['icon'],
                    'sort' => $val['sort'],
                    'parent_id' => $val['parent_id']
                  ]);

                //   if($menuManager)


                // if($val['parent_id'] != 0){
                //     // dd($menuManager, $val);
                //     $getnew_menumanager = MenuManager::where([['role_id', $create_new_role['id']],['menu_permission_id', $val['menu_permission_id']]])->first();
                //     $getold_parent = MenuManager::where([['id', $val['parent_id']],
                //     ])->first();
                //     $getnew_parent = MenuManager::where([['role_id', $create_new_role['id']],
                //                                          ['title', $getold_parent['title']],
                //                                          ['path_url', $getold_parent['path_url']],
                //                                          ['menu_permission_id', $getold_parent['menu_permission_id']],
                //                                          ['sort', $getold_parent['sort']],
                //                                          ])->get();
                //     dd($getnew_parent);


                //     dd($val['parent_id'], $getnew_menumanager);
                // }


                //   $menuPermission = MenuPermission::with('permissions')->find($menu_permisi);
                //   if(isset($menuPermission['id'])){
                //     collect($menuPermission->permissions)->map(function ($permission) use ($create_new_role, $menuManager) {
                //         $menuManager->permissions()->attach($permission->id, ['role_id' => $create_new_role['id']]);
                //       });

                //   }
            }
        //    dd($parent);

            $parent_new = array();
            // $getnew_menumanager = MenuManager::where([['role_id', $create_new_role['id']],['id', $menuManager['id']]])->first();
            $getnew_menuManager = MenuManager::where('role_id', $create_new_role['id'])->get();
            foreach ($getnew_menuManager as $obj => $item){
              //  $get_menumanager[$key]->$val['title'];
            //   dd( $item);
              if($item['parent_id'] != 0){
                $get_oldparent = MenuManager::find($item['parent_id']);
                $get_newparent = MenuManager::where([['role_id', $create_new_role['id']],
                    ['title',$get_oldparent['title']],
                    ['path_url',$get_oldparent['path_url']],
                    ['menu_permission_id',$get_oldparent['menu_permission_id']],
                    ['sort',$get_oldparent['sort']],
                    ['icon',$get_oldparent['icon']],
                    ])->first();
                    // $get_newparent['id'];
                $update_parent = MenuManager::find($item['id']);
                $update_parent->update([
                    'parent_id' => $get_newparent['id'],
                ]);
              }
            }

        };
        // $data->markEmailAsVerified();
        // $role = Role::find($request['role_id']);
        // $data->roles()->attach($role);
        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.users.index')));
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

  public function update(Request $request, $id)
  {
    $logInUser = Auth::user()->roles()->first()->slug ?? NULL;
    if ($id == Auth::id() || in_array($logInUser, ['super-admin', 'admin'])) {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|integer',
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
            'active' => 'required|between:0,1',
            'image' => 'image|mimes:jpg,png,jpeg',
          ]);
      $data = User::findOrFail($id);
      if ($validator->passes()) {
        $image =  $data['image'];
        $dimensions = [array('300', '300', 'thumbnail')];
        try {
          DB::beginTransaction();
          if (isset($request['image']) && !empty($request['image'])) {
            $image = FileUpload::uploadImage('image', $dimensions, 'storage', $data['image']);
          }
          $data->update([
            'name' => ucwords($request['name']),
            'email' => $request['email'],
            'username' => $request['username'],
            'active' => $request['active'],
            'image' => $image,
          ]);

          if (isset($request['role_id'])) {
            $role = Role::findOrFail($request['role_id']);
            $data->roles()->detach();
            $data->roles()->attach($role);
          }


          DB::commit();
          $response = response()->json($this->responseUpdate(true, route('backend.users.index')));
        } catch (Throwable $e) {
            dd($e);
          DB::rollback();
          $response = response()->json($this->responseUpdate(false));
        }
      } else {
        $response = response()->json(['error' => $validator->errors()->all()]);
      }
    } else {
      return abort('401');
    }
    return $response;
  }

//   public function destroy($id)
//   {

//     return $response;
//   }


 public function destroy($id)
    {
        DB::beginTransaction();
        try {
                $data = User::find($id);
                $role = Role::find($data->roles()->first()->id);
            if ($data->delete()) {
                    $role->delete();
                    Fileupload::deleteFile($data->image, "images/thumbnail", "images/original");
                    $response = response()->json($this->responseDelete(true));
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

  public function resetpassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|integer',
    ]);

    if ($validator->passes()) {
      $data = User::find($request->id);
      $data->password = Hash::make($data['email']);
      if ($data->save()) {
        $response = response()->json($this->responseUpdate(true));;
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }


  public function offline(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|integer',
    ]);

    if ($validator->passes()) {
        Cache::forget('is_online' .  $request['id']);
        $response = response()->json($this->responseUpdate(true));
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }


  public function changepassword(Request $request)
  {
    $data = Auth::user();

    $validator = Validator::make($request->all(), [
      'old_password' => ['required', new MatchOldPassword(Auth::id())],
      'password' => 'required|between:6,255|confirmed',
    ]);

    if ($validator->passes()) {
      $data->password = Hash::make($request['password']);
      if ($data->save()) {
        $response = response()->json($this->responseUpdate(true));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function select2(Request $request)
  {
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $kecamatan_id = $request->kecamatan;
    $data = User::where('users.name', 'LIKE', '%' . $request->q . '%')
      ->when($kecamatan_id, function ($query, $kecamatan_id) {
            return $query->where('users.kecamatan_id', $kecamatan_id);
       })
      ->leftJoin('kecamatan_tb', 'kecamatan_tb.id', '=', 'users.kecamatan_id')
      ->groupBy('users.id')
      ->orderBy('users.name')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('users.id, users.name as text')
      ->get();

    $count = User::where('users.name', 'LIKE', '%' . $request->q . '%')
    ->when($kecamatan_id, function ($query, $kecamatan_id) {
        return $query->where('users.kecamatan_id', $kecamatan_id);
     })
    ->leftJoin('kecamatan_tb', 'kecamatan_tb.id', '=', 'users.kecamatan_id')
    ->groupBy('users.id')
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

  public function import(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'importusers' => 'required|mimes:xlsx',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $reader = new Xlsx();
        $spreadsheet = $reader->load($request['importusers']);
        $sheetData = $spreadsheet->getSheetByName('user')->toArray();
        unset($sheetData[0]);
        if (count($sheetData) > 0) {
          $chunkuser = array_chunk($sheetData, 500);
          $row_in = 0;
          $row_out = 0;
          $row_fail = array();
          foreach ($chunkuser as $itemChunk):
            foreach ($itemChunk as $item):

              $name = $item[0];
              $email = $item[1];
              $username= $item[2];
              $kecamatan = $item[3];
              $role = $item[4];


              // $imp_marketing = str_replace(" ", "_", $name);
              $user = User::where('name', 'LIKE', "%{$name}%")->first();

              $camat = Kecamatan::where('name', 'LIKE', "%{$kecamatan}%")->first();

              if(isset($camat['id'])){
                  if ($user != null || ($user) != "") {
                    $user->update(['name' => $name]);
                  } else {
                    $row_in ++;
                    $user = User::create([
                      'name' => ucwords($name),
                      'email' => $email,
                      'username' => $username,
                      'password' => Hash::make($email),
                      'kecamatan_id' => $camat['id'],
                      'active' => '1'
                    ]);
                    $user->save();
                    $user->markEmailAsVerified();
                    $role = Role::find(3);
                    $user->roles()->attach($role);
                    $user->kecamatan()->attach($camat);

                  }

              }else{
                $row_out ++;
                $row_fail[] = $name;
              }


            endforeach;
        endforeach;
        $data_gagal =  count($row_fail) > 0 ? ' Gagal :'. $row_out. ' Data Gagal'.json_encode($row_fail) : 'Semua Data Tersimpan';
        // $data_gagal = json_encode($row_fail);
        // dd();
        // $json = ;
           $status = [
            'status' => 'success',
             'message' => 'Data Import Berhasil : '.$row_in. $data_gagal,
             'redirect' => 'reload'];

          ini_set('max_execution_time', 3000);
          DB::commit();
          $response = response()->json($status);
        }

      } catch (Throwable $throw) {
        dd($throw);
        DB::rollBack();
        $response = response()->json($this->responseStore(false, 'Pastikan format sudah sesuai dengan contoh'));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }


}
