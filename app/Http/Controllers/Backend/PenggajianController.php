<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Penggajian;
use App\Models\Driver;
use App\Models\Driverlogkasbon;
use App\Models\Kasbonjurnallog;
use App\Models\Kasbon;
use App\Models\Joborder;
use App\Models\KonfirmasiJo;
use App\Models\Mobil;
use App\Traits\NoUrutTrait;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Throwable;
class PenggajianController extends Controller
{
    use ResponseStatus,NoUrutTrait;

    function __construct()
    {
      $this->middleware('can:backend-penggajian-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-penggajian-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-penggajian-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-penggajian-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Gaji";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Gaji"],
        ];
        $penggajian = Penggajian::find($request['penggajian_id']);
        $belum_bayar = Penggajian::selectRaw('sum(sisa_gaji) as belum_bayar')->where('status_payment', '!=', '2')->first();
        $data = [
          'gaji' => $penggajian,
          'belum_bayar' => $belum_bayar
        ];
        if ($request->ajax()) {
            $month = date("m",strtotime($request['bulan_kerja']));
            $year = date("Y",strtotime($request['bulan_kerja']));
          $data = Penggajian::selectRaw('penggajian.*')->with('driver', 'mobil', 'payment');
            if ($request->filled('status_payment')) {
                 $data->where('status_payment', $request['status_payment']);
            }
            if ($request->filled('driver_id')) {
                $data->where('driver_id', $request['driver_id']);
            }

            if ($request->filled('mobil_id')) {
                $data->where('mobil_id', $request['mobil_id']);
            }

            if ($request->filled('id')) {
                $data->where('id', $request['id']);
            }


             if ($request->filled('bulan_kerja')) {
                $data->whereMonth('tgl_gaji',  $month)->whereYear('tgl_gaji', $year);
             }




            if ($request->filled('tgl_awal')) {
                    $data->whereDate('tgl_gaji', '>=', $request['tgl_awal']);
            }
            if ($request->filled('tgl_akhir')) {
                $data->whereDate('tgl_gaji', '<=', $request['tgl_akhir']);
            }

          return DataTables::of($data)
            ->editColumn('bulan_kerja', function ($row) {
                return Carbon::parse($row->bulan_kerja)->isoFormat('MMMM Y');
            })
            ->addColumn('action', function ($row) {
                $show = '<a href="' . route('backend.penggajian.show', $row->id) . '" class="dropdown-item">Detail</a>';
                $edit = '<a class="dropdown-item" href="penggajian/' . $row->id . '/edit">Ubah</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';
                $payment = '<a href="' . route('backend.paymentgaji.create',  ['penggajian_id'=> $row->id]) . '" class="dropdown-item">Pembayaran</a>';
                $cicilan = '<a class="dropdown-item" href="paymentgaji/' . $row->id . '/edit">Pelunasan</a>';
                $list_payment = '<a href="' . route('backend.paymentgaji.index', ['penggajian_id'=> $row->id]) . '" class="dropdown-item">List Payment</a>';

                $cek_payment = $row->status_payment == '0'  ? $payment : ($row->status_payment == '1'  ? $cicilan : '');
                $cek_edit =  $row->status_payment == '0' ? $edit : '';
                $cek_delete =  $row->status_payment == '0' ? $delete : '';
                $cek_list_payment = $row->status_payment > '0' ? $list_payment : '';


                return '<div class="dropdown">
                <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                    Aksi <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $show.'
                    '. $cek_list_payment.'
                    '. $cek_payment.'
                    '. $cek_edit.'
                    '. $cek_delete.'
                </div>
            </div>';

            })
            ->rawColumns(['bulan_kerja', 'action'])
            ->make(true);
        }

        return view('backend.penggajian.index', compact('config', 'page_breadcrumbs', 'data'));
    }

    public function create(Request $request)
    {

      $config['page_title'] = "Tambah Penggajian";
      $page_breadcrumbs = [
        ['url' => route('backend.penggajian.index'), 'title' => "Daftar Penggajian"],
        ['url' => '#', 'title' => "Tambah Penggajian"],
      ];

      $gaji = Penggajian::find($request['penggajian_id']);
      $data = [
        'gaji' => $gaji,
      ];

      return view('backend.penggajian.create', compact('page_breadcrumbs', 'config', 'data'));
    }



    public function store(Request $request)
    {


          $validator = Validator::make($request->all(), [
            'tgl_gaji'  => "required",
            'driver_id'  => "required",
            'mobil_id'  => "required",
            'bulan_kerja'  => "required",
            'nominal_kasbon'  => "required_if:nominal_kasbon,>,0",

            'sub_total'  => "required",
            'nominal_kasbon'  => "required",
            'bonus'  => "required",
            'total_gaji'  => "required",
            'konfirmasijoid'  => "required",
          ]);
        //   dd($request);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $kode =  $this->KodeGaji(Carbon::parse($request['tgl_gaji'])->format('d M Y'));

                // dd($kode);
                  $total_gaji = $request['sub_total'] + $request['bonus'] - $request['nominal_kasbon'];
                  $bulan_keja =$request['bulan_kerja'].'-01';
                  if($total_gaji < 0){
                    $response = response()->json($this->responseStore(true, 'Total Gaji Salah', route('backend.penggajian.create')));
                  }else{
                    // dd($request['kode_joborder']);
                    $data = Penggajian::create([
                        'kode_gaji'  => $kode,
                        'tgl_gaji'  => $request['tgl_gaji'],
                        'driver_id'  => $request['driver_id'],
                        'mobil_id'  => $request['mobil_id'],
                        'bulan_kerja'  => $bulan_keja,
                        'sub_total' => $request['sub_total'],
                        'bonus' => $request['bonus'],
                        'nominal_kasbon' => $request['nominal_kasbon'],
                        'total_gaji'  => $total_gaji,
                        'sisa_gaji'  => $total_gaji,
                        'keterangan_gaji' => $request['keterangan_gaji'],
                        'kode_joborder' => $request['kode_joborder'],
                        'keterangan_kasbon' => $request['keterangan_kasbon'],
                        'status_payment' => '0',
                        'created_by' => Auth::user()->id,
                      ]);

                      if(isset($data['id'])){

                        foreach($request['konfirmasijoid'] as $val){
                            $joborder = Joborder::findOrFail($val);
                            $joborder->update([
                                'penggajian_id' =>  $data['id'],
                                'kode_gaji' =>  $kode,
                            ]);

                            $konfirmasijo = KonfirmasiJo::where('joborder_id',$val);
                            $konfirmasijo->update([
                                'penggajian_id' =>  $data['id'],
                                'kode_gaji' =>  $kode,
                            ]);
                        }
                        // dd($request['nominal_kasbon']);
                        if($request['nominal_kasbon'] > 0){

                            $kode_gaji =  $this->KodeKasbon(Carbon::parse($request['tgl_gaji'])->format('d M Y'));
                            $kasbon = Kasbon::create([
                                'driver_id' => $request['driver_id'],
                                'penggajian_id' =>  $data['id'],
                                'kode_kasbon'=> $kode_gaji,
                                'jenis'=> 'Potong Gaji',
                                'tgl_kasbon'=> $request['tgl_gaji'],
                                'keterangan'=> $request['keterangan_kasbon'],
                                'nominal'=> $request['nominal_kasbon'],
                                'validasi' =>  '1',
                                'created_by' => Auth::user()->id
                            ]);

                            if(isset($kasbon['id'])){
                                $driver_total_kasbon = 0;
                                $driver = Driver::findOrFail($request['driver_id']);



                                // $cek_kasbon_id
                                // $cek_kasbon_id;
                                if($driver['kasbon'] >= $request['nominal_kasbon']){
                                    $driver_total_kasbon = $driver['kasbon'] - $request['nominal_kasbon'];
                                    // dd( $driver_total_kasbon);
                                    $driver->update([
                                        'kasbon'=> $driver_total_kasbon,
                                    ]);

                                }else{
                                    $response = response()->json([
                                        'error' => true,
                                        'message' => 'Potongan Kasbon Melebihi Jumlah Kasbon Tersedia'
                                    ]);
                                }

                                $kasbonjurnallog = Kasbonjurnallog::create([
                                    'kasbon_id' =>   $kasbon['id'],
                                    'penggajian_id' =>  $kasbon['penggajian_id'],
                                    'driver_id' =>  $kasbon['driver_id'],
                                    'kode_kasbon'=>  $kasbon['kode_kasbon'],
                                    'jenis'=>  $kasbon['jenis'],
                                    'tgl_kasbon'=>  $kasbon['tgl_kasbon'],
                                    'keterangan'=> $kasbon['keterangan'],
                                    'debit'=> $kasbon['nominal'],
                                    'kredit'=> '0'
                                ]);

                                // dd($kasbon['id']);
                                $data->update([
                                    'kasbon_id' =>   $kasbon['id']
                                ]);

                                // dd($kasbonjurnallog);
                            }



                        }


                        $driverlogkasbon = Driverlogkasbon::create([
                            'driver_id' => $request['driver_id'],
                            'penggajian_id' =>  $data['id'],
                            'nominal'=> $request['nominal_kasbon'] ?? '0'
                        ]);

                      }
                      $response = response()->json($this->responseStore(true, route('backend.penggajian.index')));
                  }


              DB::commit();

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
        $config['page_title'] = "Detail Penggajian";

        $page_breadcrumbs = [
          ['url' => route('backend.penggajian.index'), 'title' => "Detail Penggajian"],
          ['url' => '#', 'title' => "Detail Invoice"],
        ];
        $penggajian = Penggajian::with('driver', 'mobil', 'joborder', 'payment')->findOrFail($id);
        $konfirmasiJo = KonfirmasiJo::with('joborder')->where('penggajian_id',  $penggajian['id'])->orderBy('kode_joborder', 'asc')->get();
        // dd( $penggajian['payment']);
        $data = [
          'penggajian' => $penggajian,
          'konfirmasijo' => $konfirmasiJo,
        ];
        return view('backend.penggajian.show', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function select2(Request $request)
    {
      $page = $request->page;
      $resultCount = 10;
      $payment_gaji = $request['status_payment'];
      $offset = ($page - 1) * $resultCount;
      $data = Penggajian::where('kode_gaji', 'LIKE', '%' . $request->q . '%')
        ->when($payment_gaji, function ($query, $payment_gaji) {
            return $query->where('status_payment', '0');
         })
        ->orderBy('kode_gaji')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, kode_gaji as text')
        ->get();

      $count =  Penggajian::where('kode_gaji', 'LIKE', '%' . $request->q . '%')
        ->when($payment_gaji, function ($query, $payment_gaji) {
            return $query->where('status_payment', '0');
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

    public function findpenggajian(Request $request)
    {
      $gaji = Penggajian::findOrFail($request['id']);
      $driver = Driver::findOrFail($gaji['driver_id']);
      $mobil = Mobil::findOrFail($gaji['mobil_id']);
      $data = [
        'gaji' => $gaji,
        'driver' => $driver,
        'bulan_kerja' =>   date('Y-m', strtotime($gaji['bulan_kerja'])),
        'mobil' => $mobil
      ];
      return response()->json($data);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
         $data = Penggajian::findOrFail($id);
            if ($data->delete()) {
                $joborder = Joborder::where('penggajian_id', $data['id']);
                $joborder->update([
                    'penggajian_id' =>  null,
                    'kode_gaji' =>  null,
                ]);


                $driver = Driver::findOrFail($data['driver_id']);
                $driver_total_kasbon = $driver['kasbon'] + $data['nominal_kasbon'];
                $driver->update([
                    'kasbon'=> $driver_total_kasbon,
                ]);

                $logkasbon = Driverlogkasbon::where([['penggajian_id', $id],['driver_id', $data['driver_id']]]);
                if($logkasbon->first()){
                    $logkasbon->delete();
                }

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

    public function edit($id)
    {
        $config['page_title'] = "Update Gaji";

        $page_breadcrumbs = [
          ['url' => route('backend.penggajian.index'), 'title' => "Daftar Gaji"],
          ['url' => '#', 'title' => "Update Gaji"],
        ];
        $gaji = Penggajian::with('driver', 'mobil')->findOrFail($id);
        $data = [
          'gaji' => $gaji,
        ];

        return view('backend.penggajian.edit', compact('page_breadcrumbs', 'config', 'data'));
    }


    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
        'tgl_gaji'  => "required",
        'driver_id'  => "required",
        'mobil_id'  => "required",
        'bulan_kerja'  => "required",
        'nominal_kasbon'  => "required_if:nominal_kasbon,>,0",
        'sub_total'  => "required",
        'nominal_kasbon'  => "required",
        'bonus'  => "required",
        'total_gaji'  => "required",
        'konfirmasijoid'  => "required",
      ]);

      if ($validator->passes()) {
        DB::beginTransaction();
        try {
            $data = Penggajian::find($id);
            $total_gaji = $request['sub_total'] + $request['bonus'] - $request['nominal_kasbon'];
            $bulan_keja =$request['bulan_kerja'].'-01';
            if($total_gaji > 0){
                $kasbon = Kasbon::where('penggajian_id', $data['id'])->first();
                $driverlogkasbon = Driverlogkasbon::where('penggajian_id', $data['id'])->first();
                $driver = Driver::findOrFail($request['driver_id']);

                if($request['nominal_kasbon'] > 0){
                    $cek_kasbon_id = $kasbon->first();
                    $kode_gaji =  $this->KodeKasbon(Carbon::parse($request['tgl_gaji'])->format('d M Y'));
                    $kasbon = Kasbon::updateOrCreate([
                        'id' => $cek_kasbon_id['id'] ?? null
                    ],[
                        'penggajian_id' =>  $data['id'],
                        'kode_kasbon'=>  $cek_kasbon_id['kode_kasbon'] ?? $kode_gaji,
                        'driver_id' => $request['driver_id'],
                        'tgl_kasbon'=>  $cek_kasbon_id['tgl_kasbon'] ?? $request['tgl_gaji'],
                        'jenis'=> 'Potong Gaji',
                        'keterangan'=> $request['keterangan_kasbon'],
                        'nominal'=> $request['nominal_kasbon'],
                        'validasi' =>  '1',
                        'created_by' => Auth::user()->id
                    ]);
                    // $update_kasbon = $kasbon->first();
                    // dd($kasbon, $data['id']);

                    if(isset($kasbon['id'])){
                        $kasbonjurnallog = Kasbonjurnallog::updateOrCreate([
                            'kasbon_id' => $kasbon['id']
                        ],[
                            'kasbon_id' =>  $kasbon['id'],
                            'penggajian_id' =>  $data['id'],
                            'kode_kasbon'=>  $kasbon['kode_kasbon'] ??  $kode_gaji,
                            'driver_id' => $request['driver_id'],
                            'tgl_kasbon'=>  $kasbon['tgl_kasbon'] ?? $request['tgl_gaji'],
                            'jenis'=> 'Potong Gaji',
                            'keterangan'=> $kasbon['keterangan'] ?? 'Pembayaran Bon Kode Slip Gaji '.$data['kode_gaji'],
                            'debit'=> $request['nominal_kasbon'],
                            'validasi' =>  '1'
                        ]);
                    }



                    if($kasbon->first()){
                        $driver_total_kasbon = 0;
                        $count_total_kasbon = $driverlogkasbon['nominal'] + $driver['kasbon'];
                        if( $count_total_kasbon > $request['nominal_kasbon']){
                            $driver_total_kasbon = $count_total_kasbon - $request['nominal_kasbon'];
                            $driver->update([
                                'kasbon'=> $driver_total_kasbon,
                            ]);

                            $driverlogkasbon->update([
                                'nominal'=> $request['nominal_kasbon']
                            ]);


                        }

                        // else{
                        //     $response = response()->json([
                        //         'error' => true,
                        //         'message' => 'Potongan Kasbon Melebihi Jumlah Kasbon Tersedia'
                        //     ]);
                        // }

                    }



                }else{
                    if(isset($kasbon['id'])){
                        $kasbon->delete();
                    }

                    $count_total_kasbon = $driverlogkasbon['nominal'] + $driver['kasbon'];
                    $driver_total_kasbon = $count_total_kasbon - $request['nominal_kasbon'];
                    $driver->update([
                        'kasbon'=> $driver_total_kasbon,
                    ]);
                    $driverlogkasbon->update([
                        'nominal'=> $request['nominal_kasbon']
                    ]);
                }
                $kasbon_id = isset($kasbon['id']) ? $kasbon['id'] : null;
                $kode =  $this->KodeGaji(Carbon::parse($request['tgl_gaji'])->format('d M Y'));
                $kode_update =  $data['tgl_gaji'] != $request['tgl_gaji'] ? $kode : $data['kode_gaji'];
                $data->update([
                    'kode_gaji'  =>   $kode_update,
                    'tgl_gaji'  => $request['tgl_gaji'],
                    'driver_id'  => $request['driver_id'],
                    'mobil_id'  => $request['mobil_id'],
                    'bulan_kerja'  => $bulan_keja,
                    'sub_total' => $request['sub_total'],
                    'bonus' => $request['bonus'],
                    'nominal_kasbon' => $request['nominal_kasbon'],
                    'total_gaji'  => $total_gaji,
                    'sisa_gaji'  => $total_gaji,
                    'kasbon_id' =>   $kasbon_id,
                    'kode_joborder' =>  $request['kode_joborder'],
                    'keterangan_gaji' => $request['keterangan_gaji'],
                    'keterangan_kasbon' => $request['keterangan_kasbon'],
                    'updated_by' => Auth::user()->id,
                    'status_payment' => '0'
                ]);



                if(isset($data['id'])){

                    $joborder_id = [];
                    foreach($request['konfirmasijoid'] as $val){
                        $joborder_id[] = $val;
                    }

                    $cek_jo = Joborder::where([
                        ['penggajian_id' , $data['id']],
                    ])->whereNotIn('id', $joborder_id);
                    if($cek_jo->get()){
                        $jo = $cek_jo->update([
                            'penggajian_id' =>  null,
                            'kode_gaji' =>  null,
                        ]);
                    }

                    $cek_konjo = KonfirmasiJo::where([
                        ['penggajian_id' , $data['id']],
                    ])->whereNotIn('joborder_id', $joborder_id);
                    if($cek_konjo->get()){
                        $konjo = $cek_konjo->update([
                            'penggajian_id' =>  null,
                            'kode_gaji' =>  null,
                        ]);
                    }

                    foreach($request['konfirmasijoid'] as $val){
                        // dd($val);
                        $joborder = Joborder::findOrFail($val);

                        $joborder->update([
                            'penggajian_id' =>  $data['id'],
                            'kode_gaji' =>  $data['kode_gaji'],
                        ]);
                        $konfirmasijo = KonfirmasiJo::where('joborder_id', $val);

                        $konfirmasijo->update([
                            'penggajian_id' =>  $data['id'],
                            'kode_gaji' =>  $data['kode_gaji'],
                        ]);
                    }



                    // dd();

                }

            }
            // $tgl_jatuh_tempo = Carbon::createFromFormat('Y-m-d',  $request['tgl_invoice'])->addDays($request['payment_hari'])->format('Y-m-d');
            if($driver['kasbon'] < 0){
                        $response = response()->json([
                            'error' => true,
                            'message' => 'Potongan Kasbon Melebihi Jumlah Kasbon Tersedia'
                        ]);
            }elseif($total_gaji < 0){
                $response = response()->json($this->responseStore(false, 'Total Gaji Salah', route('backend.invoice.create')));
            }else{
                DB::commit();
                $response = response()->json($this->responseStore(true, route('backend.penggajian.index')));
            }

        } catch (Throwable $throw) {
            dd($throw);
          DB::rollBack();
          $response = response()->json($this->responseUpdate(false));
        }
      } else {
        $response = response()->json(['error' => $validator->errors()->all()]);
      }
      return $response;
    }

    public function pdf(Request $request)
    {

        $status_payment = $request['status_payment'];
        $driver_id = $request['driver_id'];
        $id = $request['id'];
        $mobil_id = $request['mobil_id'];
        $bulan_kerja = $request['bulan_kerja'] ;
        $tgl_awal = $request['tgl_awal'];
        $tgl_akhir = $request['tgl_akhir'];



        // dd( $validasi);
        $data = Penggajian::with('driver', 'mobil')
        ->when( $status_payment != null, function ($query, $status_payment) {
            return $query->where('status_payment', $status_payment);
         })->when( $driver_id, function ($query, $driver_id) {
            return $query->where('driver_id', $driver_id);
         })->when( $mobil_id, function ($query, $mobil_id) {
            return $query->where('mobil_id', $mobil_id);
         })->when( $bulan_kerja, function ($query, $bulan_kerja) {
            $month = date("m",strtotime($bulan_kerja));
            $year = date("Y",strtotime($bulan_kerja));
            return $query->whereMonth('tgl_gaji',  $month)->whereYear('tgl_gaji', $year);
         })->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_gaji', '>=', $tgl_awal);
         })
         ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_gaji', '<=', $tgl_akhir);
         })->get();


                $data = [
                    'gaji' => $data,
                    'tgl_awal' => $request['tgl_awal'],
                    'tgl_akhir' => $request['tgl_akhir'],
                ];

        $pdf =  PDF::loadView('backend.penggajian.report',  compact('data'));
        $PAPER_F4 = array(0,0,609.4488,935.433);
        $pdf->setPaper( $PAPER_F4, 'landscape');
        $pdf->render();
        $font       = $pdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $pdf->get_canvas()->page_text(33, 590, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
        $fileName = 'Laporan-Penggajian : '. $tgl_awal . '-SD-' .$tgl_akhir;
        return $pdf->stream("${fileName}.pdf");
    }


    public function excel(Request $request)
    {


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $status_payment = $request['status_payment'];
        $driver_id = $request['driver_id'];
        $id = $request['id'];
        $mobil_id = $request['mobil_id'];
        $bulan_kerja = $request['bulan_kerja'] ;
        $tgl_awal = $request['tgl_awal'];
        $tgl_akhir = $request['tgl_akhir'];



        // dd( $validasi);
        $data = Penggajian::with('driver', 'mobil')
        ->when( $status_payment != null, function ($query, $status_payment) {
            return $query->where('status_payment', $status_payment);
         })->when( $driver_id, function ($query, $driver_id) {
            return $query->where('driver_id', $driver_id);
         })->when( $mobil_id, function ($query, $mobil_id) {
            return $query->where('mobil_id', $mobil_id);
         })->when( $bulan_kerja, function ($query, $bulan_kerja) {
            $month = date("m",strtotime($bulan_kerja));
            $year = date("Y",strtotime($bulan_kerja));
            return $query->whereMonth('tgl_gaji',  $month)->whereYear('tgl_gaji', $year);
         })->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_gaji', '>=', $tgl_awal);
         })
         ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_gaji', '<=', $tgl_akhir);
         })->get();

        //  dd($data);

        // if ($request->filled('driver_id')) {
        //     $data->where('driver_id', $request['driver_id']);
        // }

        // if ($request->filled('mobil_id')) {
        //     $data->where('mobil_id', $request['mobil_id']);
        // }

        // if ($request->filled('id')) {
        //     $data->where('id', $request['id']);
        // }


        //  if ($request->filled('bulan_kerja')) {
        //     $data->whereMonth('tgl_gaji',  $month)->whereYear('tgl_gaji', $year);
        //  }




        // if ($request->filled('tgl_awal')) {
        //         $data->whereDate('tgl_gaji', '>=', $request['tgl_awal']);
        // }
        // if ($request->filled('tgl_akhir')) {
        //     $data->whereDate('tgl_gaji', '<=', $request['tgl_akhir']);
        // }
        // $data->get();



        // $data = Kasbon::with('driver','joborder')
        //  ->when( $jenis, function ($query, $jenis) {
        //     return $query->where('jenis2', $jenis);
        //  })
        //  ->when( $driver_id, function ($query,  $driver_id) {
        //     return $query->where('driver_id',   $driver_id);
        //  })
        //  ->when($validasi != null, function ($query, $validasi) {
        //     return $query->where('validasi',  $validasi);
        //  })
        //  ->when( $id, function ($query,  $id) {
        //     return $query->where('id',   $id);
        //  })->when($tgl_awal, function ($query, $tgl_awal) {
        //     return $query->whereDate('tgl_kasbon', '>=', $tgl_awal);
        //  })
        //  ->when($tgl_akhir, function ($query, $tgl_akhir) {
        //     return $query->whereDate('tgl_kasbon', '<=', $tgl_akhir);
        //  })->get();

        //  dd( $validasi);



         $sheet->setCellValue('A1', 'Laporan Gaji');
         $spreadsheet->getActiveSheet()->mergeCells('A1:K1');
         $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


         if($request['tgl_awal'] != null && $request['tgl_akhir'] != null){
            $spreadsheet->getActiveSheet()->mergeCells('A2:K2');
            $sheet->setCellValue('A2', 'Tanggal : '. date('d-m-Y', strtotime($request['tgl_awal'])) .' S/D '. date('d-m-Y', strtotime($request['tgl_akhir'])));
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
         }


         $rows3 = 3;
         $sheet->setCellValue('A'.$rows3, 'Kode Gaji');
         $sheet->setCellValue('B'.$rows3, 'Tanggal Slip Gaji');
         $sheet->setCellValue('C'.$rows3, 'Driver');
         $sheet->setCellValue('D'.$rows3, 'No Polisi');
         $sheet->setCellValue('E'.$rows3, 'Bulan Kerja');
         $sheet->setCellValue('F'.$rows3, 'Gaji Pokok');
         $sheet->setCellValue('G'.$rows3, 'Bonus');
         $sheet->setCellValue('H'.$rows3, 'Potongan Kasbon');
         $sheet->setCellValue('I'.$rows3, 'Total Gaji');
         $sheet->setCellValue('J'.$rows3, 'Status');
         $sheet->setCellValue('K'.$rows3, 'Operator (Waktu)');

         for($col = 'A'; $col !== 'L'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}
         $x = 4;
         foreach($data as $val){
                 $status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas');
                 $sheet->setCellValue('A' . $x, $val['kode_gaji']);
                 $sheet->setCellValue('B' . $x, $val['tgl_gaji']);
                 $sheet->setCellValue('C' . $x, $val['driver']['name']);
                 $sheet->setCellValue('D' . $x, $val['mobil']['nomor_plat'] ?? '');
                 $sheet->setCellValue('E' . $x, $val['bulan_kerja']  ?? '');
                 $sheet->setCellValue('F' . $x, $val['sub_total']  ?? '');
                 $sheet->setCellValue('G' . $x, $val['bonus']  ?? '');
                 $sheet->setCellValue('H' . $x, $val['nominal_kasbon']  ?? '');
                 $sheet->setCellValue('I' . $x, $val['total_gaji']  ?? '');
                 $sheet->setCellValue('J' . $x, $status_payment);
                 $sheet->setCellValue('K' . $x, $val['createdby']->name . ' ( ' .date('d-m-Y', strtotime($val['created_at'])) .' )');
                 $x++;
         }
      $cell   = count($data) + 4;

      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$cell, 'Total :');
      $spreadsheet->getActiveSheet()->mergeCells( 'A' . $cell . ':E' . $cell . '');
      $spreadsheet->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

      $spreadsheet->getActiveSheet()->getStyle('F3:F'.$cell)->getNumberFormat()->setFormatCode('#,##0');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$cell, '=SUM(F3:F' . $cell . ')');

      $spreadsheet->getActiveSheet()->getStyle('G3:G'.$cell)->getNumberFormat()->setFormatCode('#,##0');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$cell, '=SUM(G3:G' . $cell . ')');

      $spreadsheet->getActiveSheet()->getStyle('H3:H'.$cell)->getNumberFormat()->setFormatCode('#,##0');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$cell, '=SUM(H3:H' . $cell . ')');

      $spreadsheet->getActiveSheet()->getStyle('I3:I'.$cell)->getNumberFormat()->setFormatCode('#,##0');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$cell, '=SUM(I3:I' . $cell . ')');

      $writer = new Xlsx($spreadsheet);
      $filename = 'Laporan Gaji';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');

    }

    public function sisapayment(Request $request)
    {

        $status_payment = $request['status_payment'];
        $driver_id = $request['driver_id'];
        $id = $request['id'];
        $mobil_id = $request['mobil_id'];
        $bulan_kerja = $request['bulan_kerja'] ;
        $tgl_awal = $request['tgl_awal'];
        $tgl_akhir = $request['tgl_akhir'];
        // $data = [
        //     'legislasi' => $legislasi,
        //     'agenda' =>  $agenda,
        //     'aspirasi' =>  $aspirasi,
        //     'survey' => $survey,
        //     'partisipan' =>  $partisipan,
        //     'ChartLegislasiJson' => $ChartLegislasi,
        //     'ChartTahapanJson' =>  $ChartTahapan,
        //     'ChartAspirasiJson' => $ChartAspirasi,
        //     'ChartSurveyJson' => $ChartSurvey,
        //   ];



        //    $results = array(
        //     "data" =>  $data
        //   );
        // $sisapayment = Penggajian::
        $data = Penggajian::selectRaw('sum(sisa_gaji) as sisa_payment')->with('driver', 'mobil')
        ->where('status_payment', '!=', '2')
        ->when( $status_payment != null, function ($query, $status_payment) {
            return $query->where('status_payment', $status_payment);
         })->when( $driver_id, function ($query, $driver_id) {
            return $query->where('driver_id', $driver_id);
         })->when( $mobil_id, function ($query, $mobil_id) {
            return $query->where('mobil_id', $mobil_id);
         })->when( $bulan_kerja, function ($query, $bulan_kerja) {

            $month = date("m",strtotime($bulan_kerja));
            $year = date("Y",strtotime($bulan_kerja));
            return $query->whereMonth('tgl_gaji',  $month)->whereYear('tgl_gaji', $year);
         })->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_gaji', '>=', $tgl_awal);
         })
         ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_gaji', '<=', $tgl_akhir);
         })->first();
        //   dd(response()->json($results['data']->sortByDesc('urut')));

        return response()->json($data);

    }




}
