@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="float-end">
                    <a onclick="printDiv('printableArea')" class="btn btn-success me-1"><i class="fa fa-print"></i></a>
                    <a onclick="window.history.back();" class="btn btn-primary w-md">Kembali</a>
                </div>
            </div>
            <div class="card-body" id="printableArea">
                <div class="invoice-title">
                    <h6 class="main-content-label mb-1">{{ $config['page_title'] ?? '' }}</h6>
                    <div class="mb-4">
                           <img  src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="logo" height="50">
                    </div>
                    <div class="text-muted">
                        {{ \Carbon\Carbon::parse($data['mobil']['created_at'])->isoFormat('dddd, D MMMM Y')}}
                        {{-- {{ $data['legislasi']['created_at'] ?? '' }} --}}
                    </div>
                </div>



                <div class="row" style="padding-top:10px;">
                    <div class="col-6">
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="">
                                        <th class="text-left">Merek mobil</th>
                                        <td class="text-left">{{ $data['mobil']['merkmobil']['name'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Tipe mobil</th>
                                        <td class="text-left">{{ $data['mobil']['tipemobil']['name'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Jenis mobil</th>
                                        <td class="text-left">{{ $data['mobil']['jenismobil']['name'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Plat No mobil</th>
                                        <td class="text-left">{{ $data['mobil']['nomor_plat'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">No Rangka</th>
                                        <td class="text-left">{{ $data['mobil']['nomor_rangka'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">No Mesin</th>
                                        <td class="text-left">{{ $data['mobil']['nomor_mesin'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Tahun</th>
                                        <td class="text-left">{{ $data['mobil']['tahun'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">No BPKB</th>
                                        <td class="text-left">{{ $data['mobil']['nomor_bpkb'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">No Ijin Usaha</th>
                                        <td class="text-left">{{ $data['mobil']['nomor_ijin_usaha'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">No Ijin Bongkar Muat</th>
                                        <td class="text-left">{{ $data['mobil']['nomor_ijin_bongkar'] ?? '' }}</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="">
                                        <th class="text-left">No Stnk</th>
                                        <td class="text-left">{{ $data['mobil']['nomor_stnk'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Masa Berlaku Stnk (1th)</th>
                                        <td class="text-left">{{ $data['mobil']['berlaku_stnk'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Masa Berlaku Stnk (5th)</th>
                                        <td class="text-left">{{ $data['mobil']['berlaku_pajak'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Masa Berlaku Ijin Usaha</th>
                                        <td class="text-left">{{ $data['mobil']['berlaku_ijin_usaha'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">No Kir</th>
                                        <td class="text-left">{{ $data['mobil']['kir'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Masa Berlaku Kir</th>
                                        <td class="text-left">{{ $data['mobil']['berlaku_kir'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Masa Berlaku Ijin Bongkar</th>
                                        <td class="text-left">{{ $data['mobil']['berlaku_ijin_bongkar'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Image Mobil</th>
                                        <td class="text-left">
                                            <img
                                            src="{{ $data['mobil']['image_mobil'] != NULL ? asset("/storage/images/mobil/".$data['mobil']['image_mobil']) : asset('assets/backend/images/noimg.png') }}"
                                            height="20px"
                                            width="20px" alt="">
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Image Stnk</th>
                                        <td class="text-left">
                                            <img
                                            src="{{ $data['mobil']['image_stnk'] != NULL ? asset("/storage/images/mobil/".$data['mobil']['image_stnk']) : asset('assets/backend/images/noimg.png') }}"
                                            height="20px"
                                            width="20px" alt="">
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Dump</th>
                                        <td class="text-left">{{ $data['mobil']['dump'] ?? '' }}</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top:10px;">
                    <div class="col-12">
                        <label for="select2Merk">Keterangan<span class="text-danger">*</span></label>
                        <textarea class="form-control">{{ $data['mobil']['keterangan_mobil'] ?? '' }}</textarea>
                    </div>

                </div>
                <!-- end row -->

                <div class="mt-xl-0 mt-4"  style="padding-top:30px;">


                    <div class="d-flex align-items-start">

                        @if(count($data['joborder']) > 0)
                        <div class="flex-grow-1">
                            <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Joborder Terkoneksi</button>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="multi-collapse collapse" id="multiCollapseExample2" style="">
                                <div class="card border shadow-none card-body text-muted mb-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">No</th>
                                                            <th>Kode Joborder</th>
                                                            <th>Tanggal Transaksi</th>
                                                            <th>Keterangan</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php($no=1)
                                                        @foreach ($data['joborder'] as $val)
                                                            <tr>
                                                                <td width="2%" class="text-center">{{$no++}}</td>
                                                                <td>{{$val->kode_joborder}}</td>
                                                                <td>{{$val->tgl_joborder}}</td>
                                                                <td>{{$val->keterangan_joborder}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                    </div>




                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
@media print
{
    @page {
      size: A4; /* DIN A4 standard, Europe */
      margin: 27mm 16mm 27mm 16mm;
    }
    html, body {
        width: 210mm;
        /* height: 297mm; */
        height: 282mm;
        font-size: 16px;
        color: #000;
        background: #FFF;
        overflow:visible;
    }
    body {
        padding-top:15mm;
    }
    table {
        border: solid #000 !important;
        border-width: 1px 0 0 1px !important;
    }
    th, td {
        border: solid #000 !important;
        border-width: 0 1px 1px 0 !important;
    }
}
</style>
@endsection
@section('script')
  <script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}



    $(document).ready(function () {
        // $('#tanggal_lahir').datepicker({ dateFormat: "yy-mm-dd" });








    });
  </script>
@endsection
