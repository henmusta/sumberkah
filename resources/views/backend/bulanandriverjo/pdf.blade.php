<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

@page {
        /* size: 21cm 15cm; */
        /* size: landscape; */
        margin: 0;
        margin: 10mm 10mm 10mm 10mm;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0 5px;
      /* height: 50% !important; */
    }

    table {page-break-before:auto !important;}
    .cover{

    }
    .text-left{
        text-align: left !important;
    }

    .text-end{
        text-align: right !important;
    }
    .headertable{
        width: 100%;
        padding: 50px;
    }

    a {
        color: inherit;
        text-decoration: none;
    }


    .headertable,
      .headertable th, .headertable td {

        margin: auto;
        border: 1px solid black;
        border-collapse: collapse;
    }

    #pakettable{
        width: 100%;
        padding: 0px;
    }

    #pakettable, #pakettable th, #pakettable td {
        padding: 2px;
        border: 1px solid black;
        border-collapse: collapse;
    }

    #pakettable, td {
        font-size:8px;
    }

    .header {
      /* position: fixed; */
      top: 0;
    }

    #ttd {
      width: 100%;
      margin-top: 5px;
      font-size: 10px;
      font-weight: normal;
    }
  </style>
</head>
<body>

    <div class="header" style="padding-bottom: 20px;">
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Bulanan Driver Joborder</h3>
        <p style=" text-align: center; margin-bottom: 0; font-size:8px;"> Print By : {{ Auth::user()->name ?? '' }} ( {{  \Carbon\Carbon::now()->format('d-m-Y H:i:s')  }} )</p>
    </div>


    @php($no=1)

    @foreach ($data['data'] as $key => $item)
    @php($count = count($data['data']) - 1)
    @php($cek = count($item['alldata']->get()) > 5 && $key != $count  ?  'page-break-after: always !important;' : '')
    <table id="pakettable">
        <thead  style="background-color: #fff !important; color:black;" width="100%">
            <tr>
                <th colspan="15" class="text-left">{{$item['driver']}}</th>
            </tr>
        </thead>
        <thead style="background-color: #fff !important; color:black;">
            <tr>
                <th width="5%" class="text-center">Id JO</th>
                <th width="5%">Tanggal</th>
                <th width="3%">Status</th>
                <th width="10%">Driver</th>
                <th width="5%">No Plat Polisi</th>
                <th width="5%">Jenis Mobil</th>
                <th width="10%">Customer</th>
                <th width="6%">Muatan</th>
                <th width="5%">Alamat Awal (Dari)</th>
                <th width="5%">Alamat Akhir (Ke)</th>
                <th width="5%">Total Uj</th>
                <th width="5%">Pembayaran</th>
                <th width="5%">Sisa Uj</th>
                <th width="15%">Keterangan</th>
                <th width="11%">Operator Waktu</th>
            </tr>
        </thead>
        <tbody>
            @php($total_uj = $sisa_uj = 0)
            @foreach ($item['alldata']->get() as $val)



            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            @php($status_jo = $val['status_joborder'] == '0' ? 'Ongoing' : 'Done')
            @php($total_uj += $val->total_uang_jalan)
            @php($sisa_uj += $val->sisa_uang_jalan)
                <tr>
                    <td><a href="{{ route('backend.joborder.index') }}?joborder_id={{$val->id}}" target="_blank">{{$val->kode_joborder}}</a></td>
                    <td>{{$val->tgl_joborder}}</td>
                    <td>{{$status_jo}}</td>
                    <td>{{$val->driver['name']}}</td>
                    <td>{{$val->mobil['nomor_plat']}}</td>
                    <td>{{$val->jenismobil['name']}}</td>
                    <td>{{$val->customer['name']}}</td>
                    <td>{{$val->muatan['name']}}</td>
                    <td>{{$val->ruteawal['name']}}</td>
                    <td>{{$val->ruteakhir['name']}}</td>
                    <td class="text-end">Rp. {{ number_format($val->total_uang_jalan,0,',','.')}}</td>
                    <td>{{$status_payment}}</td>
                    <td class="text-end">Rp. {{ number_format($val->sisa_uang_jalan,0,',','.')}}</td>
                    {{-- <td width="5%">{{$val->keterangan_joborder ?? '-'}}</td> --}}
                    <td width="5%">{{$val->keterangan_joborder ?? '-'}}</td>
                    <td>{{ $val['createdby']->name ?? '' }} ( {{  \Carbon\Carbon::parse($val['created_at'])->format('d-m-Y H:i:s')  }} )</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="10"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($total_uj,0,',','.')}}</th>
                <th></th>
                <th class="text-end" id="">Rp. {{ number_format($sisa_uj,0,',','.')}}</th>
                <th ></th>
                <th ></th>
             </tr>
        </tfoot>
    </table><br>
    @endforeach



</body>
</html>
