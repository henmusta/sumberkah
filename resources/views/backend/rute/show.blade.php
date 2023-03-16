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
                        {{ \Carbon\Carbon::parse($data['rute']['created_at'])->isoFormat('dddd, D MMMM Y')}}
                        {{-- {{ $data['legislasi']['created_at'] ?? '' }} --}}
                    </div>
                </div>



                <div class="row" style="padding-top:10px;">
                    <div class="col-6">
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="">
                                        <th class="text-left">Customer</th>
                                        <td class="text-left">{{ $data['rute']['customer']['name'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Alamat Awal (Dari)</th>
                                        <td class="text-left">{{ $data['rute']['ruteawal']['name'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Alamat Akhir</th>
                                        <td class="text-left">{{ $data['rute']['alamatakhir']['name'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Muatan</th>
                                        <td class="text-left">{{ $data['rute']['muatan']['name'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Jneis Mobil</th>
                                        <td class="text-left">{{ $data['rute']['jenismobil']['name'] ?? '' }}</td>
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
                                        <th class="text-left">Uang Jalan</th>
                                        <td class="text-left">{{number_format($data['rute']['uang_jalan'],0,',','.') ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Jenis Muatan</th>
                                        <td class="text-left">{{$data['rute']['ritase_tonase'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Harga/Satuan</th>
                                        <td class="text-left">{{number_format($data['rute']['harga'],0,',','.') ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Gaji</th>
                                        <td class="text-left">{{number_format($data['rute']['gaji'],0,',','.') ?? '' }}</td>
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
                        <textarea class="form-control">{{ $data['rute']['keterangan'] ?? '' }}</textarea>
                    </div>

                </div>
                <!-- end row -->




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
