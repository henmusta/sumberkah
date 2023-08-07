<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

    @page {
        /* size: 21cm 15cm; */
        /* size: landscape; */
        margin: 0;
        margin: 10mm 10mm 20mm 10mm;
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

    .text-center{
        text-align: center !important;
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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Laporan Penggajian</h3>
        @if($data['tgl_awal'] != null && $data['tgl_akhir'] != null)
        <h5  style=" text-align: center; margin-top:25px;margin-bottom: 0">TANGGAL : {{\Carbon\Carbon::parse($data['tgl_awal'])->format('d-m-Y')}} S/D {{\Carbon\Carbon::parse($data['tgl_akhir'])->format('d-m-Y')}} </h5>
        @endif
    </div>



    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr >
                <th>No</th>
                <th class="text-center">Kode Gaji</th>
                <th>Tanggal Gaji</th>
                <th>Driver</th>
                <th>No Polisi</th>
                <th>Bulan Kerja</th>
                <th>Total Gaji</th>
                <th>Sisa Gaji</th>
                <th>Status</th>
                <th width="150px">Operator (Waktu)</th>
            </tr>
        </thead>
        <tbody>
            @php($no=1)
            @foreach ($data['gaji'] as $val)
            @php($status_payment = $val['status_payment'] == '0' ? 'Belum Bayar' : ($val['status_payment'] == '1' ? 'Progress Payment' : 'Lunas'))
            {{-- {data: 'kode_gaji',   className: 'text-center', name: 'kode_gaji'},
            {data: 'tgl_gaji', name: 'tgl_gaji'},
            {data: 'driver.name', name: 'driver.name'},
            {data: 'mobil.nomor_plat', name: 'mobil.nomor_plat'},
            {data: 'bulan_kerja', name: 'bulan_kerja'},
            {data: 'total_gaji', name: 'total_gaji'},
            {data: 'sisa_gaji', name: 'sisa_gaji'},
            {data: 'status_payment', name: 'status_payment'}, --}}
                <tr>
                    <td width="2%" class="text-center">{{$no++}}</td>
                    <td>{{$val->kode_gaji}}</td>
                    <td>{{$val->tgl_gaji}}</td>
                    <td>{{$val['driver']->name}}</td>
                    <td>{{$val['mobil']->nomor_plat}}</td>
                    <td>{{ \Carbon\Carbon::parse($val->bulan_kerja)->isoFormat('MMMM Y')}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->total_gaji,0,',','.')}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->sisa_gaji,0,',','.')}}</td>
                    <td  class="text-center">{{$status_payment}}</td>

                    <td>{{$val['createdby']->name}} ( {{\Carbon\Carbon::parse($val->created_at)->format('d-m-Y H:i:s')}} )</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6"style="text-align:right">Total: </th>
                <th class="text-end" id="">Rp. {{ number_format($data['gaji']->sum('total_gaji'),0,',','.')}}</th>
                <th class="text-end" id="">Rp. {{ number_format($data['gaji']->sum('sisa_gaji'),0,',','.')}}</th>
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
