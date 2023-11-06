<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

@page {
        /* size: 21cm 15cm; */
/* size: potrait;         */
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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Dashboard Invoice Jatuh Tempo</h3>
        <p style=" text-align: center; margin-bottom: 0; font-size:8px;"> Print By : {{ Auth::user()->name ?? '' }} ( {{  \Carbon\Carbon::now()->format('d-m-Y H:i:s')  }} )</p>
    </div>



    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr >
                <th>No</th>
                <th>Kode Invoice</th>
                <th>Tanggal Invoice</th>
                <th>Customer</th>
                <th>Nominal Invoice</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @php($no=1)
            @foreach ($data['data']->get() as $val)
                <tr>
                    <td width="2%" class="text-center">{{$no++}}</td>
                    <td class="text-center">{{$val['kode_invoice']}}</td>
                    <td class="text-center">{{$val['tgl_invoice']}}</td>
                    <td class="text-left">{{$val['customer']['name']}}</td>
                    <th class="text-end" id="">Rp. {{ number_format($val['total_harga'],0,',','.')}}</th>
                    <td class="text-center">{{$val['tgl_jatuh_tempo']}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($data['data']->sum('total_harga'),0,',','.')}}</th>
                <th style="text-align:right"></th>
             </tr>
        </tfoot>

    </table>




</body>
</html>
