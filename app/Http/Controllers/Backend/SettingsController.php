<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Helpers\FileUpload;
use App\Models\Settings;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    // use ResponseStatus;
    // public function index(Request $request)
    // {
    //     $config['page_title'] = "Settings Logo";
    //     $page_breadcrumbs = [
    //       ['url' => '#', 'title' => "Daftar Settings Logo"],
    //     ];

    //     if ($request->ajax()) {
    //         $data = Settings::query();
    //         return DataTables::of($data)
    //           ->addIndexColumn()
    //           ->addColumn('action', function ($row) {

    //             return '<div class="dropdown">
    //                         <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
    //                             Aksi <i class="mdi mdi-chevron-down"></i>
    //                         </a>

    //                         <div class="dropdown-menu" data-popper-placement="bottom-start" >
    //                         <a class="dropdown-item" href="settings/' . $row->id . '/edit">Ubah</a>
    //                         </div>
    //                     </div>';
    //           })->editColumn('icon', function (Settings $Settings) {
    //             $data = asset('assets/img/profile-photos/1.png');
    //             if(isset($Settings->icon)){
    //              $data =  asset("/storage/images/logo/$Settings->icon");
    //             }
    //             return '<img class="rounded-circle" src="'.$data.'"alt="photo" style="width:75px; height: 75px;">';
    //           }) ->editColumn('sidebar_logo', function (Settings $Settings) {
    //             $data = asset('assets/img/profile-photos/1.png');
    //             if(isset($Settings->sidebar_logo)){
    //              $data =  asset("/storage/images/logo/$Settings->sidebar_logo");
    //             }
    //             return '<img class="rounded-circle" src="'.$data.'"alt="photo" style="width:75px; height: 75px;">';
    //           })->editColumn('favicon', function (Settings $Settings) {
    //             $data = asset('assets/img/profile-photos/1.png');
    //             if(isset($Settings->favicon)){
    //              $data =  asset("/storage/images/logo/$Settings->favicon");
    //             }
    //             return '<img class="rounded-circle" src="'.$data.'"alt="photo" style="width:75px; height: 75px;">';
    //           })

    //           ->rawColumns(['icon','sidebar_logo','favicon', 'action'])
    //           ->make(true);
    //       }
    //     return view('backend.settings.index', compact('config', 'page_breadcrumbs'));
    // }
    use ResponseStatus;
    public function index()
    {
        $config['page_title'] = " Settings";

        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Settings"],
        ];
        $data = Settings::first();

        return view('backend.settings.index', compact('page_breadcrumbs', 'config', 'data'));
    }

    public function edit($id)
    {

      $config['page_title'] = "Edit Settings Logo";

      $page_breadcrumbs = [
        ['url' => route('backend.settings.index'), 'title' => "Settings Logo"],
        ['url' => '#', 'title' => "Edit Settings Logo"],
      ];
      $data = Settings::findOrFail($id);

      return view('backend.settings.edit', compact('page_breadcrumbs', 'config', 'data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
      $validator = Validator::make($request->all(), [

        // 'name' => 'required',]
        'logo' => 'image|mimes:jpg,png,jpeg',
        'name' => 'required',
        'icon' => 'image|mimes:jpg,png,jpeg',
        'sidebar_logo' => 'image|mimes:jpg,png,jpeg',
        'favicon'  => 'image|mimes:jpg,png,jpeg',
        'layout' => 'required',
        'layout-mode' => 'required',
        'layout-position' => 'required',
        'layout-width' => 'required',
        'topbar-color' => 'required',
        'sidebar-size' => 'required',
        'sidebar-color' => 'required'
      ]);

      $data = Settings::findOrFail($id);
      if ($validator->passes()) {
        $image = NULL;
        $dimensions = [array('300', '300', 'logo')];
        $dimensions_icon = [array('600', '600', 'logo')];
        $dimensions_sidebar_logo = [array('224', '66', 'logo')];

        try {
          DB::beginTransaction();
          if (isset($request['sidebar_logo']) && !empty($request['sidebar_logo'])) {
            $sidebar_logo = FileUpload::uploadImage('sidebar_logo', $dimensions_sidebar_logo, 'storage', $data['sidebar_logo']);
          } else {
            $sidebar_logo =  $data['sidebar_logo'];
          }
          if (isset($request['icon']) && !empty($request['icon'])) {
            $icon = FileUpload::uploadImage('icon', $dimensions_icon, 'storage', $data['icon']);
          } else {
            $icon =  $data['icon'];
          }
          if (isset($request['favicon']) && !empty($request['favicon'])) {
            $favicon = FileUpload::uploadImage('favicon', $dimensions, 'storage', $data['favicon']);
          } else {
            $favicon = $data['favicon'];
          }

          $data->update([
            'favicon' => $favicon,
            'icon' => $icon,
            'sidebar_logo' => $sidebar_logo,
            'name' => $request['name'],
            'layout' => $request['layout'],
            'layout_mode' =>  $request['layout-mode'],
            'layout_position' => $request['layout-position'],
            'layout_width' => $request['layout-width'],
            'topbar_color' => $request['topbar-color'],
            'sidebar_size'=> $request['sidebar-size'],
            'sidebar_color'=> $request['sidebar-color'],
          ]);

          DB::commit();
          $response = response()->json($this->responseUpdate(true, route('backend.settings.index')));
        } catch (\Throwable $e) {
          dd($e);
          DB::rollback();
          $response = response()->json($this->responseUpdate(false));
        }
      } else {
        $response = response()->json(['error' => $validator->errors()->all()]);
      }

      return $response;
    }
}
