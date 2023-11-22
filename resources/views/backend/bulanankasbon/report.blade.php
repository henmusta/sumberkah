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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Bulanan Kasbon</h3>
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
            <th width="10%" class="text-center">Tanggal Transaksi</th>
            <th width="10%">Kode Kasbon</th>
            <th width="30%">Driver</th>
            <th width="10%">Kode Joborder</th>
            <th width="10%">Kode Gaji</th>
            <th width="10%">Transaksi</th>
            <th width="20%">Nominal</th>
           </tr>
       </thead>

       <tbody>
            @php($total = 0)
            @foreach ($item['alldata']->get() as $val)
            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            @php($total += $val->nominal)
            <tr>
                <td>{{$val->tgl_kasbon ?? ''}}</a></td>
                <td><a href="{{ route('backend.kasbon.index') }}?kasbon_id={{$val->id}}" target="_blank">{{$val->kode_kasbon}}</a></td>
                <td>{{$val->driver['name'] ?? ''}}</td>
                @if(isset($val->joborder['id']))
                <td><a href="{{ route('backend.joborder.index') }}?joborder_id={{$val->joborder['id']}}" target="_blank">{{$val->joborder['kode_joborder']}}</a></td>
                @else
                <td></td>
                @endif

                @if(isset($val->penggajian['id']))
                <td><a href="{{ route('backend.penggajian.index') }}?penggajian_id={{$val->penggajian['id']}}" target="_blank">{{$val->penggajian['kode_gaji']}}</a></td>
                @else
                <td></td>
                @endif

                <td>{{$val->jenis ?? ''}}</td>
                <td class="text-end">Rp. {{ number_format($val->nominal,0,',','.')}}</td>
            </tr>
            @endforeach
       </tbody>
       <tr>
            <th colspan="6"style="text-align:right">Total: </th>
            <th class="text-end" id="">Rp. {{ number_format($total,0,',','.')}}</th>
        </tr>
    </table><br><br>

    @endforeach





</body>
</html>
