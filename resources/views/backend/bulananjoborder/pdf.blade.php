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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Bulanan Joborder</h3>
        <p style=" text-align: center; margin-bottom: 0; font-size:8px;"> Print By : {{ Auth::user()->name ?? '' }} ( {{  \Carbon\Carbon::now()->format('d-m-Y H:i:s')  }} )</p>
    </div>


    @php($no=1)
    @php($getdata = $data['data'])
    @for ($item = 0; $item < count($getdata); $item++)
    {{-- {{dd($getdata[$item]['bulan'])}} --}}
    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr>
                <th width="10%" colspan="16" style="text-align: left !important">{{$getdata[$item]['bulan']}}</th>
            </tr>
        </thead>
        <thead style="background-color: #fff !important; color:black;">
            <tr>
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
            @php($total_uj=$sisa_uj = 0)
            @php($alldata = $getdata[$item]['alldata']->get())
            @for ($i = 0; $i < count($alldata); $i++)
            @php($status_payment = $alldata[$i]['status_payment'] == '0' ? 'Belum Bayar' : ($alldata[$i]['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            @php($status_jo = $alldata[$i]['status_joborder'] == '0' ? 'Ongoing' : 'Done')
            @php($total_uj += $alldata[$i]->total_uang_jalan)
            @php($sisa_uj += $alldata[$i]->sisa_uang_jalan)
                <tr>
                    <td width="2%" class="text-center">{{$no++}}</td>
                    <td>{{ $alldata[$i]['kode_joborder']}}</td>
                    <td>{{ $alldata[$i]['tgl_joborder']}}</td>
                    <td width="20px">{{$status_jo}}</td>
                    <td>{{ $alldata[$i]['driver']->name }}</td>
                    <td width="50px">{{ $alldata[$i]['mobil']['nomor_plat']}}</td>
                    <td>{{ $alldata[$i]['jenismobil']->name }}</td>
                    <td>{{ $alldata[$i]['customer']->name }}</td>
                    <td>{{ $alldata[$i]['muatan']->name }}</td>
                    <td>{{ $alldata[$i]['ruteawal']->name }}</td>
                    <td>{{ $alldata[$i]['ruteakhir']->name }}</td>
                    <td  width="60px" class="text-end">Rp. {{ number_format( $alldata[$i]['total_uang_jalan'],0,',','.')}}</td>
                    <td>{{$status_payment}}</td>
                    <td  class="text-end">Rp. {{ number_format( $alldata[$i]['sisa_uang_jalan'],0,',','.')}}</td>
                    <td>{{ $alldata[$i]['keterangan_joborder']}}</td>
                    <td>{{ $alldata[$i]['createdby']->name ?? '' }} ( {{  \Carbon\Carbon::parse( $alldata[$i]['created_at'])->format('d-m-Y H:i:s')  }} )</td>
                </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr>
                <th colspan="11"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($total_uj,0,',','.')}}</th>
                <th></th>
                <th class="text-end" id="">Rp. {{ number_format($sisa_uj,0,',','.')}}</th>
                <th width="5%"></th>
                <th width="5%"></th>
             </tr>
        </tfoot>
    </table><br>
    @endfor
</body>
</html>
