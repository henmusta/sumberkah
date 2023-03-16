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
                <div class="invoice-title">
                    <h6 class="main-content-label mb-1">{{ $config['page_title'] ?? '' }}</h6>
                    <div class="mb-4">
                           <img  src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="logo" height="50">
                    </div>
                    <div class="text-muted">
                        {{ \Carbon\Carbon::parse($data['joborder']['tgl_joborder'])->isoFormat('dddd, D MMMM Y')}}
                        {{-- {{ $data['legislasi']['created_at'] ?? '' }} --}}
                    </div>
                </div>



                <div class="row" style="padding-top:10px;">
                    <div class="col-6">
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="">
                                        <th class="text-left">Kode Joborder</th>
                                        <td class="text-left">{{ $data['joborder']['kode_joborder'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Tanggal Joborder</th>
                                        <td class="text-left">{{ $data['joborder']['tgl_joborder'] ?? '' }}</td>
                                    </tr>

                                    <tr class="">
                                        <th class="text-left">Driver</th>
                                        <td class="text-left">{{ $data['joborder']['driver']['name'] ?? '' }}</td>
                                    </tr>

                                    <tr class="">
                                        <th class="text-left">Nomor Plat Polisi</th>
                                        <td class="text-left">{{ $data['joborder']['mobil']['nomor_plat'] ?? '' }}</td>
                                    </tr>

                                    <tr class="">
                                        <th class="text-left">Customer</th>
                                        <td class="text-left">{{ $data['joborder']['customer']['name'] ?? '' }}</td>
                                    </tr>

                                    <tr class="">
                                        <th class="text-left">Kode JobOrder</th>
                                        <td class="text-left">{{ $data['joborder']['muatan']['name'] ?? '' }}</td>
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
                                        <th class="text-left">Alamat Awal (Dari)</th>
                                        <td class="text-left">{{ $data['joborder']['ruteawal']['name'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Alamat Akhir (Ke)</th>
                                        <td class="text-left">{{ $data['joborder']['ruteakhir']['name'] ?? '' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Total Uang Jalan</th>
                                        <td class="text-left">Rp. {{  number_format($data['joborder']['total_uang_jalan'],3,',','.') ?? '0' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Tanggal Payment</th>
                                        <td class="text-end">Rp. {{  number_format($data['joborder']['total_payment'],3,',','.') ?? '0' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Total Kasbon</th>
                                        <td class="text-end">Rp. {{  number_format($data['joborder']['total_kasbon'],3,',','.') ?? '0' }}</td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Sisa Tagihan Uang Jalan</th>
                                        <td class="text-end">Rp. {{  number_format($data['joborder']['sisa_uang_jalan'],3,',','.') ?? '0' }}</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    @if( $data['joborder']['status_payment'] == '2')
                                        @php($class =  'bg-success')
                                        @php($text =  'Lunas')
                                    @elseif ( $data['joborder']['status_payment'] == '1')
                                        @php($class =  'bg-warning')
                                        @php($text =  'Progress Payment')
                                    @else
                                        @php($class = 'bg-danger')
                                        @php($text =  'Belum Bayar')
                                    @endif
                                    <tr class="">
                                        <th class="text-left">Status Pembayaran</th>
                                        <th class="text-left"><span class="badge bg-pill {{$class}}">{{$text}}</span> </th>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Pembuat Joborder</th>
                                        <th class="text-left">{{ $data['joborder']['createdby']['name'] ?? '' }}  {{ $data['joborder']['created_at'] ?? '' }} </th>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Penutup Joborder</th>
                                        <th class="text-left">{{ $data['joborder']['konfirmasijo'][0]['createdby']['name'] ?? '' }}  {{ $data['joborder']['konfirmasijo'][0]['created_at'] ?? '' }} </th>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Pembuat Slip Gaji</th>
                                        <th class="text-left">{{ $data['joborder']['gaji']['createdby']['name'] ?? '' }}  {{ $data['joborder']['gaji']['created_at'] ?? '' }} </th>
                                    </tr>
                                    <tr class="">
                                        <th class="text-left">Pembuat Invoice</th>
                                        <th class="text-left">{{ $data['joborder']['invoice']['createdby']['name'] ?? '' }}  {{ $data['joborder']['invoice']['created_at'] ?? '' }} </th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>






                <div class="row" style="padding-top:10px;">
                    <h6 class="main-content-label mb-1">Pembayaran</h6>
                    <div class="col-12">
                        <div class="table-responsove">
                            <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Jenis Payment</th>
                                        <th>Keterangan</th>
                                        <th>Keterangan Kasbon</th>
                                        <th>Nominal</th>
                                        <th>Nominal Kasbon</th>
                                      </tr>
                                </thead>
                                <tbody>
                                    @php($total_cicilan =  0)
                                    @foreach ($data['joborder']['payment'] as $val)
                                        <tr>
                                            <td>{{$val->jenis_payment}}</td>
                                            <td>{{$val->keterangan}}</td>
                                            <td>{{$val->keterangan_kasbon}}</td>

                                            <td class="text-end"> Rp. {{ number_format($val->nominal,0,',','.')}}</td>
                                            <td class="text-end"> Rp. {{ number_format($val->nominal_kasbon,0,',','.')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-end" colspan="3">Total Payment</th>
                                        <th class="text-end">Rp. {{number_format($data['joborder']['payment']->SUM('nominal'),0,',','.')}}</th>
                                        <th class="text-end">Rp. {{number_format($data['joborder']['payment']->SUM('nominal_kasbon'),0,',','.')}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- end row -->


                <div class="row" style="padding-top:10px;">
                    <div class="col-12">
                        <label for="select2Merk">Keterangan<span class="text-danger">*</span></label>
                        <textarea class="form-control">{{ $data['joborder']['keterangan_joborder'] ?? '' }}</textarea>
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
