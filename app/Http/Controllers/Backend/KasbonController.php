<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Kasbon;
use App\Models\Kasbonjurnallog;
use App\Models\Driver;
use App\Models\Joborder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use App\Traits\NoUrutTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class KasbonController extends Controller
{
    use ResponseStatus,NoUrutTrait;
    function __construct()
    {
      $this->middleware('can:backend-kasbon-list', ['only' => ['index', 'show']]);
      $this->middleware('can:backend-kasbon-create', ['only' => ['create', 'store']]);
      $this->middleware('can:backend-kasbon-edit', ['only' => ['edit', 'update']]);
      $this->middleware('can:backend-kasbon-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $config['page_title'] = "Data Kasbon";
        $page_breadcrumbs = [
          ['url' => '#', 'title' => "Data Kasbon"],
        ];
        if ($request->ajax()) {
          $data = Kasbon::with('driver','joborder');
            if ($request->filled('jenis')) {
                $data->where('jenis', $request['jenis']);
            }

            if ($request->filled('driver_id')) {
                $data->where('driver_id', $request['driver_id']);
            }

            if ($request->filled('id')) {
                $data->where('id', $request['id']);
            }

            if ($request->filled('validasi')) {
                $data->where('validasi', $request['validasi']);
            }

            if ($request->filled('tgl_awal')) {
                    $data->whereDate('tgl_kasbon', '>=', $request['tgl_awal']);
            }
            if ($request->filled('tgl_akhir')) {
                $data->whereDate('tgl_kasbon', '<=', $request['tgl_akhir']);
            }
          return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $show = '<a href="' . route('backend.kasbon.show', $row->id) . '" class="dropdown-item">Detail</a>';
                $edit = '<a class="dropdown-item" href="kasbon/' . $row->id . '/edit">Ubah</a>';
                $validasi = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalValidasi" data-bs-id="' . $row->id . '"  data-bs-validasi="' . $row->validasi . '"  data-bs-nominal="' . $row->nominal. '" class="edit dropdown-item">Validasi</a>';
                $delete = '  <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a>';

                $cek_validasi =  $row->jenis != 'Potong Gaji' && $row->jenis != 'Potong Joborder' ?  $validasi : '';
                $cek_edit =  $row->validasi != '1' && $row->jenis != 'Potong Gaji' && $row->jenis != 'Potong Joborder' ? $edit : '';
                $cek_delete = $row->validasi != '1' && $row->jenis != 'Potong Gaji' && $row->jenis != 'Potong Joborder'  ? $delete : '';

                return '<div class="dropdown">
                            <a href="#" class="btn btn-secondary" data-bs-toggle="dropdown">
                                Aksi <i class="mdi mdi-chevron-down"></i>
                            </a>
                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                    '. $show.'
                    '. $cek_edit.'
                    '. $cek_delete .'
                    '. $cek_validasi .'
                </div>
            </div>';

            })
            ->make(true);
        }

        return view('backend.kasbon.index', compact('config', 'page_breadcrumbs'));
    }


    public function create()
    {
      $config['page_title'] = "Tambah Kasbon";
      $page_breadcrumbs = [
        ['url' => route('backend.kasbon.index'), 'title' => "Daftar Kasbon"],
        ['url' => '#', 'title' => "Tambah Kasbon"],
      ];
      return view('backend.kasbon.create', compact('page_breadcrumbs', 'config'));
    }

    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
            'driver_id' => 'required',
            'jenis'=> 'required',
            'tgl_kasbon'=> 'required',
            'nominal'=> 'required',
            // 'keterangan'  => "required",
          ]);

          if ($validator->passes()) {
            DB::beginTransaction();
            try {
                  $kode =  $this->KodeKasbon(Carbon::parse($request['tgl_kasbon'])->format('d M Y'));
              //    $status = $request['jenis'] == 'Pembayaran' ? '1' : '0';
                  $data = Kasbon::create([
                    'driver_id' => $request['driver_id'],
                    'kode_kasbon'=> $kode,
                    'jenis'=> $request['jenis'],
                    'tgl_kasbon'=> $request['tgl_kasbon'],
                    'keterangan'=> $request['keterangan'],
                    'nominal'=> $request['nominal'],
                    'validasi' => '0'
                  ]);

                //   if(isset($data['id']) && $request['jenis'] == "Pembayaran" ){

                //         $cek_jurnal  = $request['jenis'] == "Pembayaran" ? 'debit' : 'kredit';
                //         $driver = Driver::findOrFail($request['driver_id']);
                //         $total_kasbon = $driver['kasbon'] - $request['nominal'];
                //         $driver->update([
                //             'kasbon'=> $total_kasbon,
                //         ]);

                //          $kasbonjurnallog = Kasbonjurnallog::create([
                //             'kasbon_id' =>   $data['id'],
                //             'driver_id' =>  $data['driver_id'],
                //             'kode_kasbon'=>  $data['kode_kasbon'],
                //             'jenis'=>  $data['jenis'],
                //             'tgl_kasbon'=>  $data['tgl_kasbon'],
                //             'keterangan'=> $data['keterangan'],
                //              $cek_jurnal=> $data['nominal']
                //         ]);
                //   }

              DB::commit();
              $response = response()->json($this->responseStore(true, route('backend.kasbon.index')));
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
        $config['page_title'] = "Detail Kasbon";

        $page_breadcrumbs = [
          ['url' => route('backend.kasbon.index'), 'title' => "Detail Kasbon"],
          ['url' => '#', 'title' => "Detail Invoice"],
        ];
        $kasbon = Kasbon::with('driver')->findOrFail($id);
        $data = [
          'kasbon' => $kasbon,
        ];

        return view('backend.kasbon.show', compact('page_breadcrumbs', 'config', 'data'));
    }



    public function validasi(Request $request)
    {
        // dd( $request);
          $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nominal' => 'required'
          ]);
        //   dd($request['file']);
          if ($validator->passes()) {
            $data = Kasbon::find($request['id']);
            DB::beginTransaction();
            try {
                  $driver = Driver::findOrFail($data['driver_id']);


                //   if();
                  $cek_jurnal  = $data['jenis'] == "Pembayaran" ? 'debit' : 'kredit';
                  if($request['validasi'] == "1" ){

                    if($data['jenis'] == 'Pengajuan'){
                        $total_kasbon = ($driver['kasbon'] + $request['nominal']) ;
                    }else{
                        $total_kasbon = ($driver['kasbon'] - $request['nominal']) ;
                    }

                    $kasbonjurnallog = Kasbonjurnallog::create([
                        'kasbon_id' =>   $data['id'],
                        'joborder_id' =>  $data['joborder_id'],
                        'penggajian_id' =>  $data['penggajian_id'],
                        'driver_id' =>  $data['driver_id'],
                        'kode_kasbon'=>  $data['kode_kasbon'],
                        'jenis'=>  $data['jenis'],
                        'tgl_kasbon'=>  $data['tgl_kasbon'],
                        'keterangan'=> $data['keterangan'],
                         $cek_jurnal => $data['nominal']
                    ]);

                  }else{

                    if($data['jenis'] == 'Pengajuan'){
                        $total_kasbon = ($driver['kasbon'] - $request['nominal']) ;
                    }else{
                        $total_kasbon = ($driver['kasbon'] + $request['nominal']) ;
                    }

                    $kasbonjurnallog = Kasbonjurnallog::where('kasbon_id', $data['id']);
                    $kasbonjurnallog->delete();
                  }

                  if($total_kasbon < 0){
                    $response = response()->json([
                        'error' => true,
                        'message' => 'Gagal Validasi Kasbon Driver Akan Minus'
                    ]);
                  }else{
                    $driver->update([
                        'kasbon'=> $total_kasbon,
                      ]);

                      $data->update([
                        'validasi' => $request['validasi'],
                      ]);
                    DB::commit();
                    $response = response()->json($this->responseStore(true));
                  }

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

    public function edit($id)
    {
        $config['page_title'] = "Update Kasbon";

        $page_breadcrumbs = [
          ['url' => route('backend.kasbon.index'), 'title' => "Update Kasbon"],
          ['url' => '#', 'title' => "Update Kasbon"],
        ];
        $kasbon = Kasbon::with('driver')->findOrFail($id);
        $data = [
          'kasbon' => $kasbon,
        ];

        return view('backend.kasbon.edit', compact('page_breadcrumbs', 'config', 'data'));
    }

    public function update(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
        'driver_id' => 'required',
        'jenis'=> 'required',
        'tgl_kasbon'=> 'required',
        'nominal'=> 'required',
      ]);

      if ($validator->passes()) {
        DB::beginTransaction();
        try {
            $data = Kasbon::find($id);
            $datalog = Kasbonjurnallog::where('kasbon_id', $id);

            $cek_jurnal  = $request['jenis'] == "Pembayaran" ? 'debit' : 'kredit';
            $status = $request['jenis'] == 'Pembayaran' ? '1' : '0';
            $driver = Driver::findOrFail($request['driver_id']);
            $total_kasbon = 0;
            if($request['jenis'] == "Pembayaran" ){
                $total_kasbon = $driver['kasbon'] - $request['nominal'];
                $total_kasbon = $driver['kasbon'] - $request['nominal'];
                $driver->update([
                    'kasbon'=> $total_kasbon,
                ]);
                $datalog->update([
                    'driver_id' => $request['driver_id'],
                    'jenis'=> $request['jenis'],
                    'tgl_kasbon'=> $request['tgl_kasbon'],
                    'keterangan'=> $request['keterangan'],
                    'debit'=> $data['nominal']
                ]);


            }


            if( $total_kasbon < 0){
                DB::rollBack();
                $response = response()->json([
                    'error' => true,
                    'message' => 'Gagal! Kasbon Driver Akan Minus'
                ]);
                //
            }else{
                $data->update([
                    'driver_id' => $request['driver_id'],
                    'jenis'=> $request['jenis'],
                    'tgl_kasbon'=> $request['tgl_kasbon'],
                    'keterangan'=> $request['keterangan'],
                    'nominal'=> $request['nominal'],
                    'validasi'=> $status,
                ]);
                DB::commit();
                $response = response()->json($this->responseUpdate(true, route('backend.kasbon.index')));
            }







        } catch (Throwable $throw) {
          DB::rollBack();
          $response = response()->json($this->responseUpdate(false));
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
      $data = Kasbon::where('kode_kasbon', 'LIKE', '%' . $request->q . '%')
        // ->when($konfirmasi_gaji, function ($query, $konfirmasi_gaji) {
        //     return $query->where('status_payment', $konfirmasi_gaji);
        //  })
        ->orderBy('kode_kasbon')
        ->skip($offset)
        ->take($resultCount)
        ->selectRaw('id, kode_kasbon as text')
        ->get();

      $count =  Kasbon::where('kode_kasbon', 'LIKE', '%' . $request->q . '%')
        // ->when($konfirmasi_invoice, function ($query, $konfirmasi_invoice) {
        //     return $query->where('status_payment', $konfirmasi_invoice);
        // })
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


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
         $data = Kasbon::findOrFail($id);
            if ($data->delete()) {

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

    public function excel(Request $request)
    {


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $jenis = $request['jenis'];
        $driver_id = $request['driver_id'];
        $id = $request['id'];
        $validasi = $request['validasi'] ;
        $tgl_awal = $request['tgl_awal'];
        $tgl_akhir = $request['tgl_akhir'];
        // dd( $validasi);
        $data = Kasbon::with('driver','joborder')
         ->when( $jenis, function ($query, $jenis) {
            return $query->where('jenis2', $jenis);
         })
         ->when( $driver_id, function ($query,  $driver_id) {
            return $query->where('driver_id',   $driver_id);
         })
         ->when($validasi != null, function ($query, $validasi) {
            return $query->where('validasi',  $validasi);
         })
         ->when( $id, function ($query,  $id) {
            return $query->where('id',   $id);
         })->when($tgl_awal, function ($query, $tgl_awal) {
            return $query->whereDate('tgl_kasbon', '>=', $tgl_awal);
         })
         ->when($tgl_akhir, function ($query, $tgl_akhir) {
            return $query->whereDate('tgl_kasbon', '<=', $tgl_akhir);
         })->get();

        //  dd( $validasi);



         $sheet->setCellValue('A1', 'Laporan Kasbon');
         $spreadsheet->getActiveSheet()->mergeCells('A1:F1');
         $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

         $rows2 = 2;
         $sheet->setCellValue('A'.$rows2, 'Tanggal Transaksi');
         $sheet->setCellValue('B'.$rows2, 'Kode Kasbon');
         $sheet->setCellValue('C'.$rows2, 'Driver');
         $sheet->setCellValue('D'.$rows2, 'Jenis Transaksi');
         $sheet->setCellValue('E'.$rows2, 'Nominal');
         $sheet->setCellValue('F'.$rows2, 'Status');
         for($col = 'A'; $col !== 'F'; $col++){$sheet->getColumnDimension($col)->setAutoSize(true);}
         $x = 3;
         foreach($data as $val){
                $status_validasi = $val['validasi'] == '0' ? 'Pending' : 'Acc';
                 $sheet->setCellValue('A' . $x, $val['tgl_kasbon']);
                 $sheet->setCellValue('B' . $x, $val['kode_kasbon']);
                 $sheet->setCellValue('C' . $x, $val['driver']['name']);
                 $sheet->setCellValue('D' . $x, $val['jenis'] ?? '');
                 $sheet->setCellValue('E' . $x, $val['nominal'] ?? '');
                 $sheet->setCellValue('F' . $x, $status_validasi);
                 $x++;
         }
      $cell   = count($data) + 3;
      $spreadsheet->getActiveSheet()->getStyle('E3:E'.$cell)->getNumberFormat()->setFormatCode('#,##0');

      $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$cell, '=SUM(E3:E' . $cell . ')');

      $writer = new Xlsx($spreadsheet);
      $filename = 'Laporan Kasbon';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');

    }

}
