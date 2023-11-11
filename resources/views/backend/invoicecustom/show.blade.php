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
                    <h2 class="main-content-label mb-1">Invoice</h2>
                    <div class="mb-4">

                    </div>
                    <div class="text-muted">
                    </div>
                </div> --}}
                <table width="100%">
                    <tr>
                      <td style="width: 20%; font-weight: normal; text-align: center"></td>
                      <td style="width: 60%; font-weight: bold; font-size: 18px; text-align: center">Invoice</td>
                      <td style="width: 20%; font-weight: normal; text-align: right"><p id="hideshow">{{ $data['invoice']['createdby']['name'] ?? '' }} ( {{  \Carbon\Carbon::parse($data['invoice']['created_at'])->format('d-m-Y H:i:s')  }} )</p></td>
                    </tr>
                  </table><br><br>
                <div class="row" style="">
                    <div class="col-12">
                        <table>
                            <tr>
                                <td style="width: 300px; ">Customer</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">{{$data['invoice']['customer']['name'] ?? ''}}</td>
                            </tr>
                            <tr>
                                <td style="width: 300px; ">Tanggal Invoice</td>
                                <td style="width: 2px; padding-right: 10px">:</td>
                                <td  style="font-weight:bold">    {{ \Carbon\Carbon::parse($data['invoice']['tgl_invoice'])->format('d-m-Y')}}</td>
                            </tr>

                            <tr>
                                <td style="width: 300px; ">Keterangan Invoice</td>
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
                                        <th>Keterangan</th>
                                        <th class="text-center" width="150px">Harga</th>
                                      </tr>
                                </thead>
                                <tbody>
                                    @php($no=1)
                                    @foreach ($data['detail'] as $val)
                                        <tr>
                                            <td width="2%" class="text-center">{{$no++}}</td>
                                            <td>{{$val->keterangan}}</td>
                                            <td class="text-end">{{number_format(ceil($val->nominal),0 ,',','.')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-end">Jumlah</td>
                                            <td class="text-end" width="100px">{{number_format(ceil($data['invoice']['sub_total']),0 ,',','.')}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end">{{$data['invoice']['ppn'] == 'Iya' ? 'PPN 11%' : 'NONE'}}</td>
                                            <td class="text-end">{{number_format(ceil($data['invoice']['nominal_ppn']),0 ,',','.')}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end">Total</td>
                                            <td class="text-end">{{number_format(ceil($data['invoice']['total_harga']),0 ,',','.')}}</td>
                                        </tr>
                                </tfoot>
                            </table>
                            <table>
                                <tfoot style=" border: none;" >
                                    @php($terbilang = Riskihajar\Terbilang\Facades\Terbilang::make($data['invoice']['total_harga'], ' rupiah')  ?? '' )
                                    <tr style=" border: none;">
                                        <th style=" border: none;" class="text-left" colspan="3">Terbilang = #   {{ucwords($terbilang)}} #</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="row" style="padding-top:10px;" id="hideshow">
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
