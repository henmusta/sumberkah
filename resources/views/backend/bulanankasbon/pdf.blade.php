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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Bulanan Kasbon</h3>
        <p style=" text-align: center; margin-bottom: 0; font-size:8px;"> Print By : {{ Auth::user()->name ?? '' }} ( {{  \Carbon\Carbon::now()->format('d-m-Y H:i:s')  }} )</p>
    </div>


    @foreach ($data['data'] as $item)
    @php($count = count($data['data']) - 1);
    @php($cek = count($item['alldata']->get()) > 5 && $key != $count  ?  'page-break-after: always !important;' : '')
    <table id="pakettable" width="100%" style="{{$cek}}">
        <thead  style="background-color: #fff !important; color:black;" width="100%">
            <tr>
                <th colspan="6" class="text-left">{{$item['bulan']}}</th>
            </tr>
        </thead>
        <thead style="background-color: #fff !important; color:black; " >
           <tr>
            <th class="text-center">Tanggal Transaksi</th>
            <th>Kode Kasbon</th>
            <th>Driver</th>
            <th>JenisTransaksi</th>
            <th>Nominal</th>
            <th>Operator (Waktu)</th>
           </tr>
       </thead>

       <tbody>
           @php($total  = 0)
            @foreach ($item['alldata']->get() as $val)
            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            @php($total += $val->nominal)
            <tr>
                <td>{{$val->tgl_kasbon ?? ''}}</a></td>
                <td><a href="{{ route('backend.kasbon.index') }}?kasbon_id={{$val->id}}" target="_blank">{{$val->kode_kasbon}}</a></td>
                <td>{{$val->driver['name'] ?? ''}}</td>
                <td>{{$val->jenis ?? ''}}</td>
                <td class="text-end">Rp. {{ number_format($val->nominal,0,',','.')}}</td>
                <td>{{ $val['createdby']->name ?? '' }} ( {{  \Carbon\Carbon::parse($val['created_at'])->format('d-m-Y H:i:s')  }} )</td>
            </tr>
            @endforeach
       </tbody>
        <tfoot>
            <tr>
                <th colspan="4"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($total,0,',','.')}}</th>
                <th></th>
            </tr>
        </tfoot>
    </table><br>
    @endforeach


</body>
</html>
