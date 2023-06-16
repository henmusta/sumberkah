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
                <div class="invoice-title text-center">
                    <h2 class="main-content-label mb-1">Invoice</h2>
                    <div class="mb-4">
                           {{-- <img  src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="logo" height="50"> --}}
                    </div>
                    <div class="text-muted">
                    </div>
                </div>
                <div class="row" style="">
                    <div class="col-12">
                        <table>
                            <tr>
                                <td style="width: 300px; ">Customer</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td style="font-weight:bold">{{$data['invoice']['customer']['name'] ?? ''}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Nomor Invoice</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['invoice']['kode_invoice'] ?? ''}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Tanggal Invoice</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">    {{ \Carbon\Carbon::parse($data['invoice']['tgl_invoice'])->format('d-m-Y')}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Batas Pembayaran</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['invoice']['payment_hari'] ?? ''}} Hari ({{\Carbon\Carbon::parse($data['invoice']['tgl_jatuh_tempo'])->format('d-m-Y')}})</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Muatan</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['invoice']['joborder'][0]['muatan']['name'] ?? ''}}</td>
                            </tr>
                            @if( $data['invoice']['status_payment'] == '2')
                                @php($class =  'bg-success')
                                @php($text =  'Lunas')
                            @elseif ( $data['invoice']['status_payment'] == '1')
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
                                <td style="width: 300px; ">Keterangan</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['invoice']['keterangan_invoice'] ?? ''}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row" style="padding-top:30px;">

                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kode Joborder</th>
                                        <th>Tanggal Muat</th>
                                        <th>Tanggal Bongkar</th>
                                        <th>Nomor Polisi</th>
                                        <th>Dari</th>
                                        <th>Ke</th>
                                        @php($colspan_1 = '8')
                                        @php($colspan_2 = '9')
                                        @if($data['invoice']['joborder'][0]['rute']['ritase_tonase'] != 'Ritase')
                                        <th>Total Muatan</th>
                                            @php($colspan_1 = '9')
                                            @php($colspan_2 = '10')
                                        @endif
                                        <th>Harga</th>
                                        <th>Total</th>
                                      </tr>
                                </thead>
                                <tbody>
                                    @php($no=1)
                                    @foreach ($data['konfirmasijo'] as $val)
                                        <tr>
                                            <td width="2%" class="text-center">{{$no++}}</td>
                                            <td>{{$val->kode_joborder}}</td>
                                            <td>{{$val->tgl_muat}}</td>
                                            <td>{{$val->tgl_bongkar}}</td>
                                            <td>{{$val->joborder['mobil']['nomor_plat']}}</td>
                                            <td>{{$val->joborder['ruteawal']['name']}}</td>
                                            <td>{{$val->joborder['ruteakhir']['name']}}</td>
                                            @if($val->joborder['rute']['ritase_tonase'] != 'Ritase')
                                                 @php($cek_bm = fmod($val->berat_muatan, 1) != 0 ? 3 : 0)
                                                 <td>{{number_format($val->berat_muatan, $cek_bm,',','.')}}</td>
                                            @endif
                                            <td class="text-end"> Rp.
                                                @php($cek_thr = fmod($val->joborder['rute']['harga'], 1) != 0 ? 3 : 0)
                                                {{ number_format($val->joborder['rute']['harga'],$cek_thr,',','.')}}</td>
                                            <td class="text-end"> Rp.

                                                {{ number_format(ceil($val->total_harga),0,',','.')}}</td>
                                           </tr>
                                    @endforeach
                                </tbody>
                                @if($data['invoice']['tambahan_potongan'] != 'None')
                                <tr style="page-break-inside:avoid;">
                                    <th class="text-end" colspan="9">{{$data['invoice']['tambahan_potongan']}} Harga</th>
                                    <th class="text-end">Rp. {{number_format($data['invoice']['nominal_tambahan_potongan'],0,',','.')}}</th>
                                </tr>
                                @endif
                                <tr style="page-break-inside:avoid;">
                                    @php($sub_total = $data['invoice']['total_harga'] - $data['invoice']['nominal_ppn'] )
                                    <th class="text-end" colspan="{{$colspan_1}}">Total</th>
                                    <th class="text-end">Rp.

                                        {{number_format(ceil($sub_total),0,',','.')}}</th>
                                </tr>
                                <tr style="page-break-inside:avoid;">
                                    <th class="text-end" colspan="{{$colspan_1}}">PPN 11%</th>
                                    <th class="text-end">Rp.

                                        {{number_format(ceil($data['invoice']['nominal_ppn']),0,',','.')}}</th>
                                </tr>
                                <tr style="page-break-inside:avoid;">
                                    <th class="text-end" colspan="{{$colspan_1}}">Grand Total</th>
                                    <th class="text-end">Rp.

                                        {{number_format(ceil($data['invoice']['total_harga']),0 ,',','.')}}</th>
                                </tr>
                                {{-- <tr style="page-break-inside:avoid;">
                                    <td colspan="9">Total</td>
                                    <td>a</td>
                                </tr>
                                <tr style="page-break-inside:avoid;">
                                    <td colspan="9">Total</td>
                                    <td>a</td>
                                </tr>
                                <tr style="page-break-inside:avoid;">
                                    <td colspan="9">Total</td>
                                    <td>a</td>
                                </tr>
                                <tr style="page-break-inside:avoid;">
                                    <td colspan="9">Total</td>
                                    <td>a</td>
                                </tr>
                                <tfoot style="overflow: hidden;">

                                </tfoot> --}}
                            </table>
                            <table>
                                <tfoot style=" border: none;" >
                                    @php($terbilang = Riskihajar\Terbilang\Facades\Terbilang::make($data['invoice']['total_harga'], ' rupiah')  ?? '' )
                                    <tr style=" border: none;">
                                        <th style=" border: none;" class="text-left" colspan="{{$colspan_2}}">Terbilang = #   {{ucwords($terbilang)}} #</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="row" style="padding-top:10px;">
                    <div class="col-12">
                        <table>
                            <tr>
                                <td colspan="3" style="width: 300px; ">Harap Pembayaran Di Transfer Ke</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Bank</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td style="font-weight:bold">OCBC NISP</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">A/N</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">PT.Sumber Karya Berkah</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">No. Rek</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">3308.0000.5911</td>
                            </tr>
                        </table>

                        <table id="ttd" style="margin-top: 20px; margin-left: 15px;">
                            <tr>
                            <td style="min-width: 33%; font-weight: normal; text-align: center">Hormat Kami</td>
                            </tr>
                            <tr>
                            <td style="padding-top: 100px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ _ _ _ _ _)</td>
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
@media print
{
    @page {
      size: A4; /* DIN A4 standard, Europe */
      margin: 10mm 10mm 10mm 10mm;
    }
    html, body {
        width: 210mm;
        height: 282mm;
        font-size: 10px;
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
