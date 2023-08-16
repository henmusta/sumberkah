<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

    @page {
        size: F4 landscape;
        margin: 0;
        margin: 10mm 10mm 10mm 10mm;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0 5px;
      /* height: 50% !important; */
    }

    .text-end{
        text-align: right !important;
    }


    .headertable{
        width: 100%;
        padding: 50px;
    }

           footer {
                /* Place the footer at the bottom of each page */
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;

                /* Any other appropriate styling */
                color: #070808;
                font-size: 10px;
                font-weight: bold;
            }

            /* Show current page number via CSS counter feature */
            .page-number:before {
                content: counter(page);
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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Joborder</h3>
        @if($data['tgl_awal'] != null && $data['tgl_akhir'] != null)
        <h5  style=" text-align: center; margin-top:25px;margin-bottom: 0">TANGGAL : {{\Carbon\Carbon::parse($data['tgl_awal'])->format('d-m-Y')}} S/D {{\Carbon\Carbon::parse($data['tgl_akhir'])->format('d-m-Y')}} </h5>
        @endif
        <p style=" text-align: center; margin-bottom: 0; font-size:8px;"> Print By : {{ Auth::user()->name ?? '' }} ( {{  \Carbon\Carbon::now()->format('d-m-Y H:i:s')  }} )</p>
    </div>



    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr >
                <th>No</th>
                <th class="text-center">Id JO</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Driver</th>
                <th>No Plat Polisi</th>
                <th>Jenis Mobil</th>
                <th>Customer</th>
                <th>Muatan</th>
                <th>Alamat Awal (Dari)</th>
                <th>Alamat Akhir (Ke)</th>
                <th width="8%">Total Uj</th>
                <th>Pembayaran</th>
                <th width="8%">Sisa Uj</th>
                <th>Keterangan</th>
                <th>Operator (Waktu)</th>
            </tr>
        </thead>
        <tbody>
            @php($no=1)
            @foreach ($data['jo'] as $val)
            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            @php($status_jo = $val['status_joborder'] == '0' ? 'Ongoing' : 'Done')
                <tr>
                    <td width="2%" class="text-center">{{$no++}}</td>
                    <td>{{$val->kode_joborder}}</td>
                    <td>{{$val->tgl_joborder}}</td>
                    <td width="20px">{{$status_jo}}</td>
                    <td>{{$val->driver['name']}}</td>
                    <td width="50px">{{$val->mobil['nomor_plat']}}</td>
                    <td>{{$val->jenismobil['name']}}</td>
                    <td>{{$val->customer['name']}}</td>
                    <td>{{$val->muatan['name']}}</td>
                    <td>{{$val->ruteawal['name']}}</td>
                    <td>{{$val->ruteakhir['name']}}</td>
                    <td  width="60px" class="text-end">Rp. {{ number_format($val->total_uang_jalan,0,',','.')}}</td>
                    <td>{{$status_payment}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->sisa_uang_jalan,0,',','.')}}</td>
                    <td>{{$val->keterangan_joborder}}</td>
                    <td>{{ $val['createdby']->name ?? '' }} ( {{  \Carbon\Carbon::parse($val['created_at'])->format('d-m-Y H:i:s')  }} )</td>
                   </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="11"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($data['jo']->sum('total_uang_jalan'),0,',','.')}}</th>
                <th></th>
                <th class="text-end" id="">Rp. {{ number_format($data['jo']->sum('sisa_uang_jalan'),0,',','.')}}</th>
                <th></th>
                <th></th>
             </tr>
        </tfoot>
    </table>



</body>
</html>

