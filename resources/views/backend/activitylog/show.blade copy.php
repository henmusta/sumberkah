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
                    <div class="row" style="">
                        <div class="col-12">
                            <table>
                                <tr>
                                    <td style="width: 300px; ">Modul Menu</td>
                                    <td style="width: 2px; padding-right: 10px">:</td>
                                    <td style="font-weight:bold">{{$data['log']['log_name'] ?? ''}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 300px; ">Jenis Activity</td>
                                    <td style="width: 2px; padding-right: 10px">:</td>
                                    <td style="font-weight:bold">{{$data['log']['event'] ?? ''}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 300px; ">User Activity</td>
                                    <td style="width: 2px; padding-right: 10px">:</td>
                                    <td style="font-weight:bold">{{$data['log']['createdby']['name'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td style="width: 300px; ">Tanggal</td>
                                    <td style="width: 2px; padding-right: 10px">:</td>
                                    <td style="font-weight:bold">{{  \Carbon\Carbon::parse($data['log']['created_at'])->format('d-m-Y H:i:s')  }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center"  style="padding-top:30px;">
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h5 class="card-title mb-3">Field Database Tercatat</h5>
                                        <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                            <thead style="color:rgb(214, 214, 214);">
                                                @foreach ($data['file']['field'] as $key => $item )
                                                <tr>
                                                   <th>{{$item}}</th>
                                                </tr>
                                                @endforeach
                                            </thead>
                                        </table>
                                    </div>
                                    {{-- <div class="col-sm-4">
                                        <h5 class="card-title mb-3">Data Lama</h5>
                                        <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                            <thead style="color:rgb(214, 214, 214);">
                                                @foreach ($data['file']['Lama'] as $key => $item )
                                                <tr>
                                                    @php($style = $item != '' ? $item : '')
                                                   <th style="height:50px !important">{{$item}}</th>
                                                </tr>
                                                @endforeach
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="col-sm-4">
                                        <h5 class="card-title mb-3">Data Baru</h5>
                                        <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                            <thead style="color:rgb(238, 229, 229);">
                                                @foreach ($data['file']['Baru'] as $key => $item )
                                                <tr>
                                                {{-- {{dd($data['file']['Lama'][$key], $data['file']['Baru'][$key])}} --}}
                                                    @if($data['file']['Lama'][$key] !=  $data['file']['Baru'][$key])
                                                    <th style="background-color: rgb(230, 231, 238); color:black">{{$item}}</th>
                                                    @else
                                                    <th>{{$item}}</th>
                                                    @endif

                                                </tr>
                                                @endforeach
                                            </thead>
                                        </table>
                                    </div> --}}
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
