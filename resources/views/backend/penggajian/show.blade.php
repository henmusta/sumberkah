@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid full">
        <div class="card">
            <div class="card-header">
                <div class="float-end">
                    <a onclick="printDiv('printableArea')" class="btn btn-success me-1"><i class="fa fa-print"></i></a>
                    <a onclick="window.history.back();" class="btn btn-primary w-md">Kembali</a>
                </div>
            </div>
            <div class="card-body" id="printableArea">
                {{-- <div class="invoice-title text-center">
                    <h2 class="main-content-label mb-1">Penggajian</h2>
                    <div class="mb-4">

                    </div>
                    <div class="text-muted">

                    </div>
                </div> --}}

                 <table width="100%">
                    <tr>
                      <td style="width: 20%; font-weight: normal; text-align: center"></td>
                      <td style="width: 60%; font-weight: bold; font-size: 18px; text-align: center">Penggajian</td>
                      <td style="width: 20%; font-weight: normal; text-align: right"><p id="hideshow">{{ $data['penggajian']['createdby']['name'] ?? '' }} ( {{  \Carbon\Carbon::parse($data['penggajian']['created_at'])->format('d-m-Y H:i:s')  }} )</p></td>
                    </tr>
                  </table><br><br>
                <div class="row" style="padding-top:10px;">
                    <div class="col-12">
                        <table>
                            @if( $data['penggajian']['status_payment'] == '2')
                            @php($class =  'bg-success')
                            @php($text =  'Lunas')
                        @elseif ( $data['penggajian']['status_payment'] == '1')
                            @php($class =  'bg-warning')
                            @php($text =  'Belum Lunas')
                        @else
                            @php($class = 'bg-danger')
                            @php($text =  'Belum Bayar')
                        @endif

                            {{-- <tr>
                                <td style="width: 300px;">Status Pembayaran</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold"><span class="badge bg-pill {{$class}}">{{$text}}</span> </td>
                            </tr> --}}
                            <tr>
                                <td style="width: 300px; ">Tanggal Slip Gaji</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                {{-- <td style="font-weight:bold"> {{ \Carbon\Carbon::parse($data['penggajian']['tgl_gaji'])->isoFormat('dddd, D MMMM Y')}}</td> --}}
                                <td  style="font-weight:bold"> {{ \Carbon\Carbon::parse($data['penggajian']['tgl_gaji'])->format('d-m-Y')}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Kode Slip Gaji</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['penggajian']['kode_gaji'] ?? ''}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Supir</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['penggajian']['driver']['name'] ?? ''}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Nomor Plat</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['penggajian']['mobil']['nomor_plat'] ?? ''}} ( {{$data['penggajian']['mobil']['jenismobil']['name']}} )</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Bulan Kerja</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td style="font-weight:bold"> {{ \Carbon\Carbon::parse($data['penggajian']['bulan_kerja'])->isoFormat('MMMM Y')}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Keterangan</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['penggajian']['keterangan_gaji'] ?? ''}}</td>
                            </tr>

                            <tr>
                                <td style="width: 300px; ">Tanggal Payment</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold"> {{ isset($data['penggajian']['payment'][0]->tgl_payment) ? \Carbon\Carbon::parse($data['penggajian']['payment'][0]->tgl_payment)->format('d-m-Y')  : ''}}</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="row" style="padding-top:20px;">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kode Joborder</th>
                                        <th>Tgl Joborder</th>
                                        <th>Tgl Konfirmasi</th>
                                        <th>Tgl Muat</th>
                                        <th>Tgl Bongkar</th>
                                        <th>Customer</th>
                                        <th>Muatan</th>
                                        <th>Dari</th>
                                        <th>Ke</th>
                                        <th width="10%">Uang Jalan</th>
                                        <th width="5%">{{$data['penggajian']['joborder'][0]['rute']['ritase_tonase'] ?? ''}}</th>
                                        <th width="10%">Biaya Lain</th>
                                        <th width="10%">Gaji</th>
                                      </tr>
                                </thead>
                                <tbody>
                                    @php($no=1)
                                    @php($uang_jalan = $biaya_lain = $gaji = 0)
                                    @foreach ($data['konfirmasijo'] as $val)
                                        @php($uang_jalan += $val->joborder['rute']['uang_jalan'])
                                        @php($biaya_lain += $val->joborder['biaya_lain'])
                                        @php($gaji += $val->joborder['rute']['gaji'])
                                        <tr>
                                            <td width="2%" class="text-center">{{$no++}}</td>
                                            <td>{{$val->kode_joborder}}</td>
                                            <td>{{$val->joborder['tgl_joborder']}}</td>
                                            <td>{{$val->tgl_konfirmasi}}</td>
                                            <td>{{$val->tgl_muat}}</td>
                                            <td>{{$val->tgl_bongkar}}</td>
                                            <td>{{$val->joborder['customer']['name']}}</td>
                                            <td>{{$val->joborder['muatan']['name']}}</td>
                                            <td>{{$val->joborder['ruteawal']['name']}}</td>
                                            <td>{{$val->joborder['ruteakhir']['name']}}</td>
                                            <td class="text-end"> Rp. {{ number_format($val->joborder['rute']['uang_jalan'],0,',','.')}}</td>
                                            @php($cek_bm = fmod($val->berat_muatan, 1) != 0 ? 3 : 0)
                                            <td>{{number_format($val->berat_muatan, $cek_bm,',','.')}}</td>
                                            <td class="text-end"> Rp. {{ number_format($val->joborder['biaya_lain'],0,',','.')}}</td>
                                            <td class="text-end"> Rp. {{ number_format($val->joborder['rute']['gaji'],0,',','.')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tfoot>
                                        {{-- {{DD($data['konfirmasijo']->collect('joborder')->collect('rute')->collect(uang_jalan))}} --}}
                                        <tr>
                                            <th class="text-end" colspan="10">Total</th>
                                            <th class="text-end"> Rp. {{ number_format($uang_jalan,0,',','.')}}</th>
                                            @php($cek_sbm = fmod($data['konfirmasijo']->sum('berat_muatan'), 1) != 0 ? 3 : 0)
                                            <td class="text-end">{{number_format($data['konfirmasijo']->sum('berat_muatan'), $cek_sbm,',','.')}}</td>
                                            <th class="text-end"> Rp. {{ number_format($biaya_lain,0,',','.')}}</th>
                                            <th class="text-end"> Rp. {{ number_format($gaji,0,',','.')}}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-end" colspan="13">Bonus</th>
                                            <th class="text-end"> Rp. {{ number_format($data['penggajian']['bonus'],0,',','.')}}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-end" colspan="13">Potong Kasbon</th>
                                            <th class="text-end"> Rp. {{ number_format($data['penggajian']['nominal_kasbon'],0,',','.')}}</th>
                                        </tr>

                                        <tr>
                                            <th class="text-end" colspan="13">Total</th>
                                            <th class="text-end"> Rp. {{ number_format($data['penggajian']['total_gaji'],0,',','.')}}</th>
                                        </tr>
                                    </tfoot>
                                </tfoot>
                            </table>
                            <table>
                                <tfoot style=" border: none;" >
                                    @php($terbilang = Riskihajar\Terbilang\Facades\Terbilang::make($data['penggajian']['total_gaji'], ' rupiah')  ?? '' )
                                    <tr style=" border: none;">
                                        <th style=" border: none;" class="text-left" colspan="14">Terbilang = #   {{ucwords($terbilang)}} #</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="row" style="padding-top:10px;">
                    <div class="col-12" id="hideshow">
                        <table id="ttd" style="margin-left: 15px;" width="100%">
                            <tr>
                                 <th style="min-width: 33%; font-weight: normal; text-align: center">Dibuat Oleh</th>
                                 <th style="min-width: 33%; font-weight: normal; text-align: center">Disetujui Oleh</th>
                                 <th style="min-width: 33%; font-weight: normal; text-align: center">Diserahkan Oleh</th>
                                 <th style="min-width: 33%; font-weight: normal; text-align: center">Diterima Oleh</th>
                            </tr>
                            <tr>
                                 <td style="padding-top: 70px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ _ _ _ _ _)</td>
                                 <td style="padding-top: 70px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ _ _ _ _ _)</td>
                                 <td style="padding-top: 70px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ _ _ _ _ _)</td>
                                 <td style="padding-top: 70px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ _ _ _ _ _)</td>
                            </tr>
                      </table>
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
      margin: 8mm 8mm 8mm 8mm;
    }
    html, body {
        width: 210mm;
        /* height: 282mm; */
        font-size: 9px;
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
