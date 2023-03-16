<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Kasbonjurnallog;
use App\Models\Driver;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use App\Traits\NoUrutTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;


class MutasikasbonController extends Controller
{
    use ResponseStatus,NoUrutTrait;
    function __construct()
    {
      $this->middleware('can:backend-mutasikasbon-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-mutasikasbon-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-mutasikasbon-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-mutasikasbon-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Driver";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Driver"],
        ];

        if ($request->ajax()) {
            $driver_id =$request['driver_id'];

            $tgl_awal = date('Y-m-d', strtotime($request['tgl_awal']));
            $tgl_akhir =  date('Y-m-d', strtotime($request['tgl_akhir']));
            // dd( $tgl_awal);
            // $tgl_awal = $request['tgl_awal'];
            // $tgl_akhir =;

            $total_debit_awal = $total_kredit_awal =   $total_debit = $total_kredit = $saldo_awal = $saldo_akhir = 0;
            $data = array();
            $get_saldo_awal =  Kasbonjurnallog::whereDate('tgl_kasbon', '<',  $tgl_awal)->get();
            foreach($get_saldo_awal as $key => $i){
                $total_debit_awal += $i['debit'];
                $total_kredit_awal += $i['kredit'];
            }
            $saldo_awal = $total_kredit_awal - $total_debit_awal;
            // dd( $get_saldo_awal );
            // array_push($data,$saldo_awal['saldo_awal']);
                $get_data = Kasbonjurnallog::selectRaw('kasbon_jurnallog.*')->with('driver','joborder', 'gaji')
                ->when($tgl_awal, function ($query, $tgl_awal) {
                    return $query->whereDate('tgl_kasbon', '>=',  $tgl_awal);
                })
                ->when($tgl_akhir, function ($query, $tgl_akhir) {
                    return $query->whereDate('tgl_kasbon', '<=', $tgl_akhir);
                })
                ->when($driver_id, function ($query, $driver_id) {
                    return $query->where('driver_id', $driver_id);
                })
                ->get();
                // dd($get_data);



                if(count($get_data) > 0){
                    // dd( $get_data);
                    foreach($get_data as $key => $val){
                        // dd($val);

                        $total_debit += $val['debit'];
                        $total_kredit += $val['kredit'];
                        $data[] = $val;
                        // $get_data[0]['saldo_awal'] =  $saldo_awal;
                        $get_data[$key]['saldo'] =  $val['kredit'] - $val['debit'];

                        $new_saldo = $saldo_awal;
                        if ($key == 0) {
                            $new_saldo = $new_saldo + (float)$get_data[0]->saldo;
                            $get_data[0]->new_saldo = $new_saldo;
                        }
                        else{
                            $new_saldo = (float)$get_data[$key-1]->new_saldo + (float)$get_data[$key]->saldo;
                            $get_data[$key]->new_saldo = $new_saldo;
                            // $get_data[$key]->saldo_awal = $new_saldo;
                        }

                 }
                //  $data[0]['saldo_akhir'] =  end($data)->new_saldo;
                }

          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $show = '<a href="' . route('backend.mutasikasbon.show', $row->id) . '" class="dropdown-item">Detail</a>';
                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '.  $show.'
                </div>
            </div>';

            })
            ->make(true);
            // $data = [
            //     'saldo_awal' => 0,
            //     'total_debit' => 0,
            //     'total_kredit' =>  0,
            //     'saldo_akhir' => 0
            // ];

        //   return response()->json($data);
        }

        return view('backend.mutasikasbon.index', compact('config', 'page_breadcrumbs'));
    }

    public function show($id)
    {
        $config['page_title'] = "Mutasi Kasbon Supir";

        $page_breadcrumbs = [
          ['url' => route('backend.mutasikasbon.index'), 'title' => "Daftar Supir"],
          ['url' => '#', 'title' => "Mutasi Kasbon Supir"],
        ];
        $mutasi = Kasbonjurnallog::with('driver')->where('driver_id', $id);
        $data = [
          'mutasi' => $mutasi->first(),
          'mutasi_kasbon' => $mutasi->get(),
        ];

        return view('backend.mutasikasbon.show', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function datatablecekdriver(Request $request)
    {
      if ($request->ajax()) {
            $driver_id = $request['driver_id'];
            $bulan_kerja = $request['bulan_kerja'] ?? Carbon::now()->format('Y-m-d');
            $month = date("m",strtotime($bulan_kerja));
            $year = date("Y",strtotime($bulan_kerja));

            $data = Driver::query();


            return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $show = '<a href="' . route('backend.mutasikasbon.show', $row->id) . '" class="dropdown-item">Detail</a>';
                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '.  $show.'
                </div>
            </div>';

            })->make(true);
            }
    }


    public function ceksaldo(Request $request)
    {
        $driver_id =$request['driver_id'];

        $tgl_awal = date('Y-m-d', strtotime($request['tgl_awal']));
        $tgl_akhir =  date('Y-m-d', strtotime($request['tgl_akhir']));
        $total_debit_awal = $total_kredit_awal =   $total_debit = $total_kredit = $saldo_awal = $saldo_akhir = 0;
        $data = array();
        $get_saldo_awal =  Kasbonjurnallog::whereDate('tgl_kasbon', '<',  $tgl_awal)->where('driver_id', $driver_id)->get();
        foreach($get_saldo_awal as $key => $i){
            $total_debit_awal += $i['debit'];
            $total_kredit_awal += $i['kredit'];
        }

        $saldo_awal = $total_kredit_awal - $total_debit_awal;
        $get_data = Kasbonjurnallog::selectRaw('kasbon_jurnallog.*')->with('driver','joborder', 'gaji')
        ->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_kasbon', '>=',  $tgl_awal);
        })
        ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_kasbon', '<=', $tgl_akhir);
        })
        ->when($driver_id, function ($query, $driver_id) {
            return $query->where('driver_id', $driver_id);
        })
        ->get();

        if(count($get_data) > 0){
            // dd( $get_data);
            foreach($get_data as $key => $val){
                // dd($val);

                $total_debit += $val['debit'];
                $total_kredit += $val['kredit'];
                $data[] = $val;
                // $get_data[0]['saldo_awal'] =  $saldo_awal;
                $get_data[$key]['saldo'] =  $val['kredit'] - $val['debit'];

                $new_saldo = $saldo_awal;
                if ($key == 0) {
                    $new_saldo = $new_saldo + (float)$get_data[0]->saldo;
                    $get_data[0]->new_saldo = $new_saldo;
                }
                else{
                    $new_saldo = (float)$get_data[$key-1]->new_saldo + (float)$get_data[$key]->saldo;
                    $get_data[$key]->new_saldo = $new_saldo;
                    // $get_data[$key]->saldo_awal = $new_saldo;
                }

         }
            $data[0]['saldo_akhir'] =  end($data)->new_saldo;
        }else{
            $data[0]['saldo_akhir'] = $saldo_awal;
        }
        $data = [
            'saldo_awal' => $saldo_awal,
            'total_debit' => $total_debit,
            'total_kredit' =>  $total_kredit,
            'saldo_akhir' => $data[0]['saldo_akhir']
        ];

      return response()->json($data);
    }



}
