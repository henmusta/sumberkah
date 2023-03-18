@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="float-end">
                    <a onclick="printDiv('printableArea')" class="btn btn-success me-1"><i class="fa fa-print"></i></a>
                    <a onclick="window.history.back();" class="btn btn-primary w-md">Kembali</a>
                </div>
            </div>
            <div class="card-body" id="printableArea">
                <h2 class="main-content-label mb-1">NOTA KAS KASBON</h2>
                <div class="mb-4">
                       {{-- <img  src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="logo" height="50"> --}}
                </div>
                <div class="text-muted">
                </div>
            </div>




                {{-- <div class="row" style="padding-top:10px;">
                    <div class="col-6">
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="">
                                        <th class="text-left">Tanggal Transaksi</th>
                                        <td class="text-left">{{ $data['kasbon']['tgl_kasbon']?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Driver</th>
                                        <td class="text-left">{{ $data['kasbon']['driver']['name']?? '' }}</td>
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
                                        <th class="text-left">Jenis</th>
                                        <td class="text-left">{{ $data['kasbon']['jenis']?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Nominal</th>
                                        <td class="text-left">{{number_format($data['kasbon']['nominal'],0,',','.')  ?? '' }}</td>
                                    </tr>

                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}


{{--
                <div class="row" style="padding-top:10px;">
                    <div class="col-12">
                        <label for="select2Merk">Keterangan<span class="text-danger">*</span></label>
                        <textarea class="form-control">{{ $data['kasbon']['keterangan'] ?? '' }}</textarea>
                    </div>

                </div> --}}
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
