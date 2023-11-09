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
                <th colspan="8" class="text-left">{{$item['driver']}}</th>
            </tr>
        </thead>
        <thead style="background-color: #fff !important; color:black; " >
           <tr>
                <th class="text-center">Kode Gaji</th>
                <th>Tanggal Gaji</th>
                <th>Driver</th>
                <th>No Polisi</th>
                <th>Bulan Kerja</th>
                <th>Total Gaji</th>
                <th>Sisa Gaji</th>
                <th>Status</th>
           </tr>
       </thead>

       <tbody>
            @foreach ($item['alldata']->get() as $val)
            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            <tr>
                <td><a href="{{ route('backend.penggajian.index') }}?penggajian_id={{$val->id}}" target="_blank">{{$val->kode_gaji}}</a></td>
                <td>{{$val->tgl_gaji ?? ''}}</td>
                <td>{{$val->driver['name'] ?? ''}}</td>
                <td>{{$val->mobil['nomor_plat'] ?? ''}}</td>
                <td>{{$val->bulan_kerja ?? ''}}</td>
                <td class="text-end">Rp. {{ number_format($val->total_gaji,0,',','.')}}</td>
                <td class="text-end">Rp. {{ number_format($val->sisa_gaji,0,',','.')}}</td>
                <td>{{$status_payment ?? '-'}}</td>
            </tr>
            @endforeach
       </tbody>

    </table><br><br>
    @endforeach






</body>
</html>
