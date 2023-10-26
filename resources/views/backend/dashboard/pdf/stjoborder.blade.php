<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

@page {
        /* size: 21cm 15cm; */
        size: landscape;
        margin: 0;
        margin: 10mm 10mm 20mm 10mm;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0 5px;
      /* height: 50% !important; */
    }

    .text-end{
        text-align: right !important;
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


    .text-center{
        text-align: center !important;
    }

    .headertable{
        width: 100%;
        padding: 50px;
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
        font-size:10px;
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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Dashboard Status Joborder</h3>
        <p style=" text-align: center; margin-bottom: 0; font-size:8px;"> Print By : {{ Auth::user()->name ?? '' }} ( {{  \Carbon\Carbon::now()->format('d-m-Y H:i:s')  }} )</p>
    </div>



    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr >
                <th>No</th>
                <th>Kode Jo</th>
                <th>Tanggal Jo</th>
                <th>Driver</th>
                <th>Nopol</th>
                <th>Jenis Mobil</th>
                <th>Customer</th>
                <th>Rute Awal</th>
                <th>Rute Akhir</th>
                <th>Muatan</th>
                <th>Tonase</th>
                <th>Total UJ</th>
                <th>Kode Gaji</th>
                <th>Tanggal Pay Gaji</th>
                <th>Kode Invoice</th>
                <th>Total Tagihan Invoice</th>
            </tr>
        </thead>
        <tbody>
            @php($no=1)
            @php($total_invoice=0)
            @foreach ($data['data']->get() as $val)
            @if (isset($val['invoice']['total_harga']))
                @php($total_invoice += $val['invoice']['total_harga']);
            @endif

                <tr>
                    <td width="2%" class="text-center">{{$no++}}</td>
                    <td class="text-center">{{$val['kode_joborder'] ?? ''}}</td>
                    <td class="text-left">{{$val['tgl_joborder'] ?? ''}}</td>
                    <td class="text-left">{{$val['driver']['name'] ?? ''}}</td>
                    <td class="text-left">{{$val['mobil']['nomor_plat'] ?? ''}}</td>
                    <td class="text-left">{{$val['jenismobil']['name'] ?? ''}}</td>
                    <td class="text-left">{{$val['customer']['name'] ?? ''}}</td>
                    <td class="text-left">{{$val['ruteawal']['name'] ?? ''}}</td>
                    <td class="text-left">{{$val['ruteakhir']['name'] ?? ''}}</td>
                    <td class="text-left">{{$val['muatan']['name'] ?? ''}}</td>
                    @php($cek_bm = fmod($val->konfirmasijo[0]['berat_muatan'], 1) != 0 ? 3 : 0)
                    <td class="text-center">{{number_format($val->konfirmasijo[0]['berat_muatan'], $cek_bm,',','.') ?? '-'}}</td>
                    <th class="text-end" id="">Rp. {{ number_format($val['total_uang_jalan'],0,',','.')}}</th>
                    <td class="text-left">{{$val['gaji']['kode_gaji'] ?? ''}}</td>
                    <td class="text-left">{{$val['gaji']->payment[0]['tgl_payment'] ?? ''}}</td>
                    <td class="text-left">{{$val['invoice']['kode_invoice'] ?? ''}}</td>
                    <th class="text-end" id="">Rp. {{ number_format($val['invoice']['total_harga'],0,',','.') ?? ''}}</th>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="11"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($data['data']->sum('total_uang_jalan'),0,',','.')}}</th>
                <th colspan="3"style="text-align:right"></th>
                <th class="text-end" id="">Rp. {{ number_format($total_invoice,0,',','.')}}</th>
             </tr>
        </tfoot>
    </table>




</body>
</html>
