<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

    @page {
        /* size: 21cm 15cm; */
        size: F4 landscape;
        margin: 0;
        margin: 10mm 10mm 10mm 10mm;
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
        padding: 5px;
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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Payment Gaji</h3>
        <h5  style=" text-align: center; margin-top:25px;margin-bottom: 0">TANGGAL : {{\Carbon\Carbon::parse($data['tgl_awal'])->format('d-m-Y')}} S/D {{\Carbon\Carbon::parse($data['tgl_akhir'])->format('d-m-Y')}} </h5>
        <p style=" text-align: center; margin-bottom: 0; font-size:8px;"> Print By : {{ Auth::user()->name ?? '' }} ( {{  \Carbon\Carbon::now()->format('d-m-Y H:i:s')  }} )</p>
    </div>



    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr >
                <th width="2%" >No</th>
                <th width="6%" >Tanggal Payment</th>
                <th width="5%" >Kode Gaji</th>
                <th width="13%" >Nama Supir</th>
                <th width="7%">No polisi</th>
                <th width="8%">Periode Gaji</th>
                <th width="5%">Jenis Pembayaran</th>
                <th width="8%">Gaji</th>
                <th width="8%">Bonus</th>
                <th width="8%">Kasbon</th>
                <th width="8%">Total Gaji</th>
                <th width="5%">Kode Kasbon</th>
                <th width="14%">Operator (Waktu)</th>
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
                    <td class="text-center">{{$no++}}</td>
                    <td>{{$val->tgl_payment}}</td>
                    <td><a href="{{ route('backend.penggajian.index') }}?penggajian_id={{$val['penggajian']->id}}" target="_blank">{{$val->kode_gaji}}</a></td>
                    <td style="font-size: 8px;">{{$val['penggajian']->driver['name'] ?? ''}}</td>
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
