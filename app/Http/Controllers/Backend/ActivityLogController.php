<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Throwable;

class ActivityLogController extends Controller
{
    use ResponseStatus;
    public function index(Request $request)
    {
        $config['page_title'] = "Data Log Aktivity";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Log Aktivity"],
        ];
        if ($request->ajax()) {
            $data = Activitylog::query();

            return DataTables::of($data)
                ->editColumn('tanggal', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($row) {
                    $show = '<a href="' . route('backend.activitylog.show', $row->id) . '" class="dropdown-item">Detail</a>';
                    return '<div class="dropdown">
                    <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                        Aksi <i class="mdi mdi-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu" data-popper-placement="bottom-start" >
                        '.$show.'
                    </div>
                </div>';
              })
              ->rawColumns(['tanggal', 'action'])
              ->make(true);
          }

        return view('backend.activitylog.index', compact('config', 'page_breadcrumbs'));
    }

    public function show($id)
    {
        $config['page_title'] = "Detail Log Aktivity";

        $page_breadcrumbs = [
          ['url' => route('backend.activitylog.index'), 'title' => "Log Aktivity"],
          ['url' => '#', 'title' => "Detail  Log Aktivity"],
        ];
        $log = ActivityLog::with('createdby')->findOrFail($id);

        $raw_file = json_decode($log['properties']);
        $file = array();
        // $html = '';
        foreach($raw_file as $key => $item){
            // $html .= '<tr>';
            foreach ($item as $i => $val){

                $file['field'][$i] = $i;
                if($key == 'old'){
                    $file['Lama'][$i] = $val;
                    // $html .= '<td>'.$file['Lama'][$i].'<td>';
                }else{
                    $file['Baru'][$i] = $val;
                    // $html .= '<td>'.$file['Baru'][$i].'<td>';
                }

            }
            // $html .= '<tr>';


                        //  '<td>'.$file['Lama'][$i].'<td>'+
                        //  '<td>'.$file['Baru'][$i].'<td>'+

        }
        // dd($html);

          $data = [
            'log' => $log,
            'file' => $file
          ];
        // dd($file['field'] );


        return view('backend.activitylog.show', compact('page_breadcrumbs', 'config', 'data'));
    }

}
