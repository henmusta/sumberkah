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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Bulanan Gaji</h3>
        <p style=" text-align: center; margin-bottom: 0; font-size:8px;"> Print By : {{ Auth::user()->name ?? '' }} ( {{  \Carbon\Carbon::now()->format('d-m-Y H:i:s')  }} )</p>
    </div>

    @foreach ($data['data'] as $item)
    @php($cek = count($item['alldata']->get()) > 5 && $key != $count  ?  'page-break-after: always !important;' : '')
    <table id="pakettable" width="100%" style="{{$cek}}">
        <thead  style="background-color: #fff !important; color:black;" width="100%">
            <tr>
                <th colspan="12" class="text-left">{{$item['bulan']}}</th>
            </tr>
        </thead>
        <thead style="background-color: #fff !important; color:black; " >
           <tr>
                <th width="6%" class="text-center">Kode Gaji</th>
                <th width="8%" class="text-center">Tanggal Gaji</th>
                <th width="10%">Driver</th>
                <th width="10%">No Polisi</th>
                <th width="8%">Bulan Kerja</th>
                <th width="8%">Gaji Pokok</th>
                <th width="10%">Bonus</th>
                <th width="10%">Potong Kasbon</th>
                <th width="10%">Total Gaji</th>
                <th width="10%">Payment Gaji</th>
                <th width="10%">Status</th>
                <th width="10%">Operator Waktu</th>
           </tr>
       </thead>

       <tbody>
           @php($total = $sub_total = $bonus = $kasbon  = 0)
            @foreach ($item['alldata']->get() as $val)
            @php($total += $val->total_gaji)
            @php($sub_total += $val->sub_total)
            @php($bonus += $val->bonus)
            @php($kasbon += $val->nominal_kasbon)
            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            <tr>
                <td><a href="{{ route('backend.penggajian.index') }}?penggajian_id={{$val->id}}" target="_blank">{{$val->kode_gaji}}</a></td>
                <td>{{$val->tgl_gaji ?? ''}}</td>
                <td>{{$val->driver['name'] ?? ''}}</td>
                <td>{{$val->mobil['nomor_plat'] ?? ''}}</td>
                <td>{{$val->bulan_kerja ?? ''}}</td>
                <td class="text-end">Rp. {{ number_format($val->sub_total,0,',','.')}}</td>
                <td class="text-end">Rp. {{ number_format($val->bonus,0,',','.')}}</td>
                <td class="text-end">Rp. {{ number_format($val->nominal_kasbon,0,',','.')}}</td>
                <td class="text-end">Rp. {{ number_format($val->total_gaji,0,',','.')}}</td>
                <td>{{$val['payment'][0]->tgl_payment ?? ''}}</td>
                <td>{{$status_payment ?? '-'}}</td>
                <td>{{ $val['createdby']->name ?? '' }} ( {{  \Carbon\Carbon::parse($val['created_at'])->format('d-m-Y H:i:s')  }} )</td>
            </tr>
            @endforeach
       </tbody>
       <tr>
        <th colspan="5"style="text-align:right">Total: </th>
        <th class="text-end" id="">Rp. {{ number_format($sub_total,0,',','.')}}</th>
        <th class="text-end" id="">Rp. {{ number_format($bonus,0,',','.')}}</th>
        <th class="text-end" id="">Rp. {{ number_format($kasbon,0,',','.')}}</th>
        <th class="text-end" id="">Rp. {{ number_format($total,0,',','.')}}</th>
        <th></th>
        <th></th>
        <th></th>
     </tr>
    </table><br><br>
    @endforeach

</body>
</html>
