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
        padding: 50px;
    }

    #pakettable, #pakettable th, #pakettable td {
        padding: 8px;
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
    </div>

    @foreach ($data['data'] as $item)
    <table id="pakettable" width="100%">
        <thead  style="background-color: #fff !important; color:black;" width="100%">
            <tr>
                <th colspan="7" class="text-left">{{$item['bulan']}}</th>
            </tr>
        </thead>
        <thead style="background-color: #fff !important; color:black; " >
           <tr>
               <th class="text-center">Kode Invoice</th>
               <th>Tanggal Invoice</th>
               <th>Customer</th>
               <th>Total Tagihan</th>
               <th>Sisa Tagihan</th>
               <th>Batas Pembayaran</th>
               <th>Status Pembayaran</th>
           </tr>
       </thead>
       <tbody>
            @foreach ($item['alldata']->get() as $val)
            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            <tr>
                <td><a href="{{ route('backend.invoice.index') }}?invoice_id={{$val->id}}" target="_blank">{{$val->kode_invoice}}</a></td>
                <td>{{$val->tgl_invoice}}</td>
                <td>{{$val->customer['name']}}</td>
                <td class="text-end">Rp. {{ number_format($val->total_harga,0,',','.')}}</td>
                <td class="text-end">Rp. {{ number_format($val->sisa_tagihan,0,',','.')}}</td>
                <td>{{$val->tgl_jatuh_tempo ?? '-'}}</td>
                <td>{{$status_payment ?? '-'}}</td>
            </tr>
            @endforeach
       </tbody>

    </table><br><br>
    @endforeach






</body>
</html>
