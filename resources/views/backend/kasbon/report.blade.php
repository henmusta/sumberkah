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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Kasbon</h3>
        @if($data['tgl_awal'] != null && $data['tgl_akhir'] != null)
        <h5  style=" text-align: center; margin-top:25px;margin-bottom: 0">TANGGAL : {{\Carbon\Carbon::parse($data['tgl_awal'])->format('d-m-Y')}} S/D {{\Carbon\Carbon::parse($data['tgl_akhir'])->format('d-m-Y')}} </h5>
        @endif
    </div>



    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr >
                <th>No</th>
                <th class="text-center">Tanggal Transaksi</th>
                <th>Kode Kasbon</th>
                <th>Driver</th>
                <th>Transaksi</th>
                <th>Nominal</th>
                <th>Status</th>
                <th width="150px">Operator (Waktu)</th>
            </tr>
        </thead>
        <tbody>
            @php($no=1)
            @foreach ($data['kasbon'] as $val)
            @php($status = $val['validasi'] == '0' ? 'Pending' : 'Acc'))
                <tr>

                    {{-- {data: 'tgl_kasbon', name: 'tgl_kasbon'},
                    {data: 'kode_kasbon', name: 'kode_kasbon'},
                    {data: 'driver.name', name: 'driver.name'},
                    {data: 'jenis', name: 'jenis'},
                    {data: 'nominal', name: 'nominal'},
                    {data: 'validasi', name: 'validasi'}, --}}

                    <td width="2%" class="text-center">{{$no++}}</td>
                    <td>{{$val->kode_kasbon}}</td>
                    <td>{{$val->tgl_kasbon}}</td>
                    <td>{{$val->driver['name']}}</td>
                    <td>{{$val->jenis}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->nominal,0,',','.')}}</td>
                    <td>{{$status}}</td>
                    <td>{{$val['createdby']->name}} ( {{\Carbon\Carbon::parse($val->created_at)->format('d-m-Y H:i:s')}} )</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($data['kasbon']->sum('nominal'),0,',','.')}}</th>
                <th></th>
                <th></th>
             </tr>
        </tfoot>
    </table>


    <footer>
        Page <span class="page-number"></span>
    </footer>


</body>
</html>
