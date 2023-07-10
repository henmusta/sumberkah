<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Mobil;
use App\Models\Joborder;
use App\Models\Invoice;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
      $config['page_title'] = "DASHBOARD";
      $page_breadcrumbs = [
        ['url' => '#', 'title' => "DASHBOARD"],
      ];
      return view('backend.dashboard.index', compact('config', 'page_breadcrumbs'));
    }
    public function dtdriver(Request $request)
    {
      if ($request->ajax()) {
        $data = Driver::where('validasi', '1');
        if ($request->filled('status_jalan')) {
            $data->where('status_jalan', '!=', $request['status_jalan']);
        }
        if ($request->filled('berlaku_sim')) {
            $data->whereRaw('DATEDIFF(NOW(),tgl_sim) > -45');
        }
        return DataTables::of($data)
        ->addColumn('exp_sim', function ($row) {
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_sim =Carbon::parse($row->tgl_sim);

            $diff = $tgl_sim->diffInDays($tgl_now);
            if( $tgl_now > $tgl_sim){
                $diff = -$tgl_sim->diffInDays($tgl_now);
            }
            if($diff > 0){
                $text = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }elseif($diff < 0){
                $text = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }else{
                $text = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }

            return $text;
         })
         ->rawColumns(['exp_sim'])
         ->make(true);
      }
    }

    public function dtmobil(Request $request)
    {
      if ($request->ajax()) {
        $data = Mobil::with('merkmobil', 'tipemobil', 'jenismobil')->where('validasi', '1');
        if ($request->filled('status_jalan')) {
            $data->where('status_jalan', '!=', $request['status_jalan']);
        }

        if ($request->filled('berlaku_stnk')) {
            $data->whereRaw('DATEDIFF(NOW(),berlaku_stnk) > -45');
        }

        if ($request->filled('berlaku_pajak')) {
            $data->whereRaw('DATEDIFF(NOW(),berlaku_pajak) > -45');
        }


        if ($request->filled('berlaku_kir')) {
            $data->whereRaw('DATEDIFF(NOW(),berlaku_kir) > -45');
        }



        if ($request->filled('berlaku_ijin_bongkar')) {
            $data->whereRaw('DATEDIFF(NOW(),berlaku_ijin_bongkar) > -45');
        }


        if ($request->filled('berlaku_ijin_usaha')) {
            $data->whereRaw('DATEDIFF(NOW(),berlaku_ijin_usaha) > -45');
        }


        return DataTables::of($data)
        ->addColumn('exp_stnk', function ($row) {
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_stnk =Carbon::parse($row->berlaku_stnk);

            $diff = $tgl_stnk->diffInDays($tgl_now);
            if( $tgl_now > $tgl_stnk){
                $diff = -$tgl_stnk->diffInDays($tgl_now);
            }
            if($diff > 0){
                $text = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }elseif($diff < 0){
                $text = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }else{
                $text = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }
            return $text;
         })
         ->addColumn('exp_pajak', function ($row) {
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_pajak =Carbon::parse($row->berlaku_pajak);
            $diff = $tgl_pajak->diffInDays($tgl_now);
            if( $tgl_now > $tgl_pajak){
                $diff = -$tgl_pajak->diffInDays($tgl_now);
            }
            if($diff > 0){
                $text = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }elseif($diff < 0){
                $text = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }else{
                $text = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }
            return $text;
         })

         ->addColumn('exp_kir', function ($row) {
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_kir =Carbon::parse($row->berlaku_kir);
            $diff = $tgl_kir->diffInDays($tgl_now);
            if( $tgl_now > $tgl_kir){
                $diff = -$tgl_kir->diffInDays($tgl_now);
            }
            if($diff > 0){
                $text = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }elseif($diff < 0){
                $text = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }else{
                $text = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }
            return $text;
         })

         ->addColumn('exp_bm', function ($row) {
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_bm =Carbon::parse($row->berlaku_ijin_bongkar);
            $diff = $tgl_bm->diffInDays($tgl_now);
            if( $tgl_now > $tgl_bm){
                $diff = -$tgl_bm->diffInDays($tgl_now);
            }
            if($diff > 0){
                $text = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }elseif($diff < 0){
                $text = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }else{
                $text = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }
            return $text;
         })

         ->addColumn('exp_iu', function ($row) {
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_iu =Carbon::parse($row->berlaku_ijin_usaha);
            $diff = $tgl_iu->diffInDays($tgl_now);
            if( $tgl_now > $tgl_iu){
                $diff = -$tgl_iu->diffInDays($tgl_now);
            }
            if($diff > 0){
                $text = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }elseif($diff < 0){
                $text = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }else{
                $text = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }
            return $text;
         })


         ->rawColumns(['exp_stnk', 'exp_pajak', 'exp_kir', 'exp_bm', 'exp_iu'])
         ->make(true);
      }
    }

    public function dtjo(Request $request)
    {
      if ($request->ajax()) {

        $data = Joborder::selectRaw('joborder.*, konfirmasi_joborder.tgl_konfirmasi')
                             ->leftJoin('konfirmasi_joborder','konfirmasi_joborder.joborder_id', '=', 'joborder.id')
                             ->with('customer','ruteawal','ruteakhir','muatan','mobil', 'driver', 'rute', 'jenismobil','konfirmasijo')
                             ->where('status_joborder', '1')
                            ->whereNull('joborder.invoice_id');
            // ->where('customer_id', $request['customer_id']);
        return DataTables::of($data)
        ->addColumn('action', function ($row) {
            $show = '<a href="' . route('backend.joborder.show', $row->id) . '" class="dropdown-item">Detail</a>';
            return '<div class="dropdown">
            <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                Aksi <i class="mdi mdi-chevron-down"></i>
            </a>
            <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                '. $show .'
            </div>
        </div>';

        })
          ->make(true);
      }
    }

    public function dtinvoice(Request $request)
    {
      if ($request->ajax()) {
        $tgl_now = Carbon::now()->format('Y-m-d');
        $data = Invoice::with('customer')->whereDate('tgl_jatuh_tempo', '<=', $tgl_now)->where('status_payment', '0');
            // ->where('customer_id', $request['customer_id']);
        return DataTables::of($data)
        ->addColumn('exp_due', function ($row) {
            $tgl_now = Carbon::now()->format('Y-m-d');
            $tgl_due =Carbon::parse($row->tgl_jatuh_tempo);
            $diff = $tgl_due->diffInDays($tgl_now);
            if( $tgl_now > $tgl_due){
                $diff = -$tgl_due->diffInDays($tgl_now);
            }
            if($diff > 0){
                $text = '<button type="button" class="btn btn-danger waves-effect waves-light"><i class="bx bx-check-double font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }elseif($diff < 0){
                $text = '<button type="button" class="btn btn-warning waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }else{
                $text = '<button type="button" class="btn btn-primary waves-effect waves-light"><i class="bx bx-error font-size-16 align-middle me-2"></i>'.  $diff .' Hari</button>';
            }
            return $text;
         })
        ->addColumn('action', function ($row) {
            $show = '<a href="' . route('backend.joborder.show', $row->id) . '" class="dropdown-item">Detail</a>';
            return '<div class="dropdown">
            <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                Aksi <i class="mdi mdi-chevron-down"></i>
            </a>
            <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                '. $show .'
            </div>
        </div>';

        })
        ->rawColumns(['exp_due', 'action'])
        ->make(true);
      }
    }



}
