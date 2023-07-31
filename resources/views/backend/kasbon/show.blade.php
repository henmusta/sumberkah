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
                {{-- <div class="invoice-title text-center">
                    <h2 class="main-content-label mb-1">NOTA KASBON</h2>
                    <div class="mb-4">

                    </div>
                    <div class="text-muted">
                    </div>
                </div> --}}

                <table width="100%">
                    <tr>
                      <td style="width: 30%; font-weight: normal; text-align: center"></td>
                      <td style="width: 40%; font-weight: bold; font-size: 20px; text-align: center">NOTA KASBON</td>
                      <td style="width: 30%; font-weight: normal; text-align: right"><p id="hideshow">{{ $data['kasbon']['createdby']['name'] ?? '' }} ( {{  \Carbon\Carbon::parse($data['kasbon']['created_at'])->format('d-m-Y H:i:s')  }} )</p></td>
                    </tr>
                  </table>

                <div class="row" style="padding-top:10px;">
                    <div class="col-12">
                        <table>
                            <tr>
                                <td style="width: 300px; ">Tanggal</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">    {{ \Carbon\Carbon::parse($data['kasbon']['tgl_kasbon'])->format('d-m-Y')}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Nomor Nota Kasbon</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td style="font-weight:bold">{{$data['kasbon']['kode_kasbon'] ?? ''}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Driver</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['kasbon']['driver']['name'] ?? ''}}</td>
                            </tr>

                            <tr>
                                <td style="width: 300px; ">Jenis Transaksi</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['kasbon']['jenis'] ?? ''}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Nominal</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{number_format($data['kasbon']['nominal'],0,',','.') ?? ''}}</td>
                            </tr>
                            <tr>
                                @php($terbilang = Riskihajar\Terbilang\Facades\Terbilang::make($data['kasbon']['nominal'], ' rupiah')  ?? '' )
                                <td style="width: 300px; ">Terbilang</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{ ucwords($terbilang) ?? ''}}</td>
                            </tr>
                            <tr>

                                <td style="width: 300px; ">Keterangan</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['kasbon']['keterangan'] ?? ''}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row" style="padding-top:10px;">
                    <div class="col-12" id="hideshow">
                        <table id="ttd" style="margin-top: 20px; margin-left: 15px;" width="100%">
                            <tr>
                                 <th style="min-width: 33%; font-weight: normal; text-align: center">Dibuat Oleh</th>
                                 <th style="min-width: 33%; font-weight: normal; text-align: center">Disetujui Oleh</th>
                                 <th style="min-width: 33%; font-weight: normal; text-align: center">Diserahkan Oleh</th>
                                 <th style="min-width: 33%; font-weight: normal; text-align: center">Diterima Oleh</th>
                            </tr>
                            <tr>
                                 <td style="padding-top: 100px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ )</td>
                                 <td style="padding-top: 100px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ )</td>
                                 <td style="padding-top: 100px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ )</td>
                                 <td style="padding-top: 100px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ )</td>
                            </tr>
                      </table>
                    </div>
                </div>




            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
#hideshow {
  display: none;
}

@media print
{
    #hideshow {
      display: block;
    }
    @page {
      size: A4; /* DIN A4 standard, Europe */
      margin: 10mm 10mm 10mm 10mm;
    }
    html, body {
        width: 210mm;
        /* height: 282mm; */
        font-size: 18px;
        color: #000;
        background: #FFF;
        overflow:visible;
    }
    body {
        padding-top:15mm;
    }


    #Datatable {
        border: solid #000 !important;
        border-width: 1px 0 0 1px !important;
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
