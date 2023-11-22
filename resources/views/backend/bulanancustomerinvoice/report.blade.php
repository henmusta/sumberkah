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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Invoice Customer</h3>
    </div>

    @foreach ($data['data'] as $item)
    <table id="pakettable" width="100%">
        <thead  style="background-color: #fff !important; color:black;" width="100%">
            <tr>
                <th colspan="7" class="text-left">{{$item['customer']}}</th>
            </tr>
        </thead>
        <thead style="background-color: #fff !important; color:black; " >
           <tr>
               <th width="10%" class="text-center">Kode Invoice</th>
               <th width="10%">Tanggal Invoice</th>
               <th width="30%">Customer</th>
               <th width="15%">Total Tagihan</th>
               <th width="15%">Sisa Tagihan</th>
               <th width="10%">Batas Pembayaran</th>
               <th width="10%">Status Pembayaran</th>
           </tr>
       </thead>
       <tbody>
        @php($total = $sisa = 0)
            @foreach ($item['alldata']->get() as $val)
            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            @php($total += $val->total_harga)
            @php($sisa += $val->sisa_tagihan)
            <tr>
                <td><a href="{{ route('backend.invoice.index') }}?invoice_id={{$val->id}}" target="_blank">{{$val->kode_invoice}}</a></td>
                <td>{{$val->tgl_invoice}}</td>
                <td>{{$val->customer['name'] ?? ''}}</td>
                <td class="text-end">Rp. {{ number_format($val->total_harga,0,',','.')}}</td>
                <td class="text-end">Rp. {{ number_format($val->sisa_tagihan,0,',','.')}}</td>
                <td>{{$val->tgl_jatuh_tempo ?? '-'}}</td>
                <td>{{$status_payment ?? '-'}}</td>
            </tr>
            @endforeach
       </tbody>
       <tr>
        <th colspan="3"style="text-align:right">Total: </th>
        <th class="text-end" id="">Rp. {{ number_format($total,0,',','.')}}</th>
        <th class="text-end" id="">Rp. {{ number_format($sisa,0,',','.')}}</th>
        <th></th>
        <th></th>
     </tr>
    </table><br><br>
    @endforeach






</body>
</html>
