<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

@page {
        /* size: 21cm 15cm; */
        size: F4 landscape;
        margin: 0;
        margin: 10mm 10mm 25mm 10mm;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0 5px;
      /* height: 50% !important; */
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


    .text-end{
        text-align: right !important;
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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Invoice</h3>
        @if($data['tgl_awal'] != null && $data['tgl_akhir'] != null)
        <h5  style=" text-align: center; margin-top:25px;margin-bottom: 0">TANGGAL : {{\Carbon\Carbon::parse($data['tgl_awal'])->format('d-m-Y')}} S/D {{\Carbon\Carbon::parse($data['tgl_akhir'])->format('d-m-Y')}} </h5>
        @endif
    </div>



    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr >
                <th>No</th>
                <th class="text-center">Kode Invoice</th>
                <th>Tanggal Invoice</th>
                <th>Customer</th>
                <th>Total Tagihan</th>
                <th>Sisa Tagihan</th>
                <th>Batas Pembayaran</th>
                <th>Status Pembayaran</th>
                <th>Operator (Waktu)</th>
            </tr>
        </thead>
        <tbody>
            @php($no=1)
            @foreach ($data['invoice'] as $val)
            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
                <tr>
                    <td width="2%" class="text-center">{{$no++}}</td>
                    <td>{{$val->kode_invoice}}</td>
                    <td>{{$val->tgl_invoice}}</td>
                    <td>{{$val->customer['name']}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->total_harga,0,',','.')}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->sisa_tagihan,0,',','.')}}</td>
                    <td>{{$val->tgl_jatuh_tempo}}</td>
                    <td>{{$status_payment}}</td>
                    <td>{{ $val['createdby']->name ?? '' }} ( {{  \Carbon\Carbon::parse($val['created_at'])->format('d-m-Y H:i:s')  }} )</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($data['invoice']->sum('total_harga'),0,',','.')}}</th>
                <th class="text-end" id="">Rp. {{ number_format($data['invoice']->sum('sisa_tagihan'),0,',','.')}}</th>
                <th></th>
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
