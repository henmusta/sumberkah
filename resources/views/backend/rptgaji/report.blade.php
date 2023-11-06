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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Payment Gaji</h3>
        <h5  style=" text-align: center; margin-top:25px;margin-bottom: 0">TANGGAL : {{\Carbon\Carbon::parse($data['tgl_awal'])->format('d-m-Y')}} S/D {{\Carbon\Carbon::parse($data['tgl_akhir'])->format('d-m-Y')}} </h5>
    </div>



    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr >
                <th>No</th>
                <th>Tanggal Payment</th>
                <th>Kode Gaji</th>
                <th>Nama Supir</th>
                <th width="100px">No polisi</th>
                <th>Periode Gaji</th>
                <th>Jenis Pembayaran</th>
                <th width="150px">Gaji</th>
                <th width="150px">Bonus</th>
                <th width="150px">Kasbon</th>
                <th width="150px">Total Gaji</th>
                <th>Kode Kasbon</th>
                <th width="150px">Operator (Waktu)</th>
            </tr>
        </thead>
        <tbody>
            @php($no=1)
            @php($bonus = $kasbon = $gaji = $total = 0)
            @foreach ($data['payment'] as $val)
                @php($gaji += $val['penggajian']->sub_total)
                @php($bonus += $val['penggajian']->bonus)
                @php($kasbon += $val['penggajian']->nominal_kasbon)
                @php($total += $val['penggajian']->total_gaji)
                <tr>
                    <td width="2%" class="text-center">{{$no++}}</td>
                    <td>{{$val->tgl_payment}}</td>
                    <td><a href="{{ route('backend.penggajian.index') }}?penggajian_id={{$val['penggajian']->id}}" target="_blank">{{$val->kode_gaji}}</a></td>
                    <td>{{ substr($val['penggajian']->driver['name'], 0, 7)}}</td>
                    <td>{{$val['penggajian']->mobil['nomor_plat']}}</td>
                    <td>{{ \Carbon\Carbon::parse($val['penggajian']['bulan_kerja'])->isoFormat('MMMM Y')}}</td>
                    <td>{{$val->jenis_payment}}</td>
                    <td  class="text-end">Rp. {{ number_format($val['penggajian']->sub_total,0,',','.')}}</td>
                    <td  class="text-end">Rp. {{ number_format($val['penggajian']->bonus,0,',','.')}}</td>
                    <td  class="text-end">Rp. {{ number_format($val['penggajian']->nominal_kasbon,0,',','.')}}</td>
                    <td  class="text-end">Rp. {{ number_format($val['penggajian']->total_gaji,0,',','.')}}</td>
                    <td  class="text-end">{{ isset($val['penggajian']->kasbon) ? $val['penggajian']->kasbon['kode_kasbon'] : '-'}}</td>
                    <td>{{$val['penggajian']->createdby['name']}} ( {{\Carbon\Carbon::parse($val['penggajian']->created_at)->format('d-m-Y H:i:s')}} )</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($gaji,0,',','.')}}</th>
                <th class="text-end" id="">Rp. {{ number_format($bonus,0,',','.')}}</th>
                <th class="text-end" id="">Rp. {{ number_format($kasbon,0,',','.')}}</th>
                <th class="text-end" id="">Rp. {{ number_format($total,0,',','.')}}</th>
                <th></th>
                <th></th>
             </tr>
        </tfoot>
    </table>





</body>
</html>
