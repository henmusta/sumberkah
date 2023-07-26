<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

    @page {
        /* size: 21cm 15cm; */
        size: landscape;
        margin: 0;
        margin: 10mm 10mm 10mm 10mm;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0 5px;
      /* height: 50% !important; */
    }

    .cover{

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
        padding: 50px;
    }

    #pakettable, #pakettable th, #pakettable td {
        padding: 10px;
        border: 1px solid black;
        border-collapse: collapse;
    }

    #pakettable, td {
        font-size:12px;
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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Payment Joborder</h3>
        <h5  style=" text-align: center; margin-top:25px;margin-bottom: 0">TANGGAL : {{\Carbon\Carbon::parse($data['tgl_awal'])->format('d-m-Y')}} S/D {{\Carbon\Carbon::parse($data['tgl_akhir'])->format('d-m-Y')}} </h5>
    </div>



    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr >
                <th>No</th>
                <th>Tanggal Payment</th>
                <th>Kode Joborder</th>
                <th>Jenis Pembayaran</th>
                <th>Operator (Waktu)</th>
                <th>Keterangan Kasbon</th>
                <th>Nominal Pembayaran</th>
                <th>Nominal Kasbon</th>
            </tr>
        </thead>
        <tbody>
            @php($no=1)
            @php($total_uj = $nominal = $nominal_kasbon = 0)
            @foreach ($data['payment'] as $val)
                @php($total_uj += $val['joborder']->total_uang_jalan)
                <tr>
                    <td width="2%" class="text-center">{{$no++}}</td>
                    <td>{{$val->tgl_payment}}</td>
                    <td><a href="{{ route('backend.joborder.index') }}?joborder_id={{$val['joborder']->id}}" target="_blank">{{$val->kode_joborder}}</a></td>
                    <td>{{$val->jenis_payment}}</td>
                    <td>{{$val['joborder']->createdby['name']}} ( {{\Carbon\Carbon::parse($val['joborder']->created_at)->format('d-m-Y H:i:s')}} )</td>
                    <td>{{$val->keterangan_kasbon}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->nominal,0,',','.')}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->nominal_kasbon,0,',','.')}}</td>
                   </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($data['payment']->sum('nominal'),0,',','.')}}</th>
                <th class="text-end" id="">Rp. {{ number_format($data['payment']->sum('nominal_kasbon'),0,',','.')}}</th>
             </tr>
             <tr>
                <th colspan="6"style="text-align:right">Grand Total: </th>
                @php($grand_total = $total_uj - $data['payment']->sum('nominal_kasbon'))
                <th class="text-end" colspan="2" id="">Rp. {{ number_format($grand_total,0,',','.')}}</th>
             </tr>
        </tfoot>
    </table>





</body>
</html>
