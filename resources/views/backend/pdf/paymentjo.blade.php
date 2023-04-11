<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

    @page {
        /* size: 21cm 15cm; */
        margin: 0;
        margin: 10mm 10mm 150mm 10mm;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0 5px;
      /* height: 50% !important; */
    }

    .header {
      position: fixed;
      top: 0;
    }

    .header table, tr, td {
      margin: 0;
      padding: 0;
    }

    h2, h6 {
      text-align: center;
    }

    small {
      text-align: center;
    }

    .dataPayment {
      width: 100%;
      border:none;
      margin-top: 10px;
    }

    .dataPayment td {
        border:none;
      font-size: 10px;
      /* padding-top: 8px;
      padding-bottom: 8px; */
    }

    .dataPayment td:nth-child(1) {
        border:none;
      width: 325px;
    }

    #dataPayment small {
      font-size: 10px;
      font-style: italic;
    }

    #keterangan {
      margin-top: 15px;
    }

    #keterangan, tr {
        border:none;
      width: 100%;
      font-weight: bold;
      empty-cells: show;
    }

    #keterangan td {
      /* padding: 4px; */
    }

    #keterangan td {
      font-size: 10px;
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
<div class="header">


</div>
<h5 style=" text-align: center; margin-top:25px;margin-bottom: 0">BUKTI TITIPAN UANG JALAN</h5>

<div style="  height: 50%; !important">

<hr>
<hr>

<table class="dataPayment">

  <tr>
    <td>JO ID</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td>{{ $payment['kode_joborder'] }}</td>
  </tr>
  <tr>
    <td>Tanggal Joborder</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal"> {{ \Carbon\Carbon::parse( $payment['joborder']['tgl_joborder'])->format('d-m-Y')}} </td>
  </tr>
  <tr>
    <td>Tanggal Penyerahan Uang Jalan</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal"> {{ \Carbon\Carbon::parse( $payment['tgl_payment'])->format('d-m-Y') }}</td>
  </tr>
  <tr>
    <td>Supir</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal; text-transform: uppercase">
        {{ $joborder['driver']['name'] }}
    </td>
  </tr>
  <tr>
    <td>No Pol</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal; text-transform: uppercase">
        {{ $joborder['mobil']['nomor_plat'] }}
    </td>
  </tr>

  <tr>
    <td>Customer</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal; text-transform: uppercase">
        {{ $joborder['customer']['name'] }}
    </td>
  </tr>

  <tr>
    <td>Muatan</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal; text-transform: uppercase">
        {{ $joborder['muatan']['name'] }}
    </td>
  </tr>

  <tr>
    <td>Alamat Awal (Dari)</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal; text-transform: uppercase">
        {{ $joborder['ruteawal']['name'] }}
    </td>
  </tr>


  <tr>
    <td>Alamat Akhir (Ke)</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal; text-transform: uppercase">
        {{ $joborder['ruteakhir']['name'] }}
    </td>
  </tr>



  <tr>
    <td>Total Uang Jalan</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal;">
        Rp. {{ number_format($joborder['total_uang_jalan'],0,',','.') }}
    </td>
  </tr>
  <tr>
    <td>Total Potongan Bon Sebelumnya</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal;">
        @php($kasbon_sebelumnya = $joborder['total_kasbon'] - $payment['nominal_kasbon'])
        Rp. {{ number_format($kasbon_sebelumnya,0,',','.') }}
    </td>
  </tr>

  <tr>
    <td>Total Pembayaran  Sebelumnya</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal;">
        @php($pembayaran_sebelumnya = $joborder['total_payment'] - $payment['nominal'])
        Rp. {{ number_format($pembayaran_sebelumnya,0,',','.') }}
    </td>
  </tr>


  <tr>
    <td>Potongan Bon  (Saat Ini)</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal;">
        Rp. {{  number_format($payment['nominal_kasbon'],0,',','.') }}
    </td>
  </tr>






  <tr>
    <td>Grand Total (Saat Ini)</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal;">
        @php($grandtotal = $joborder['total_uang_jalan'] - $kasbon_sebelumnya - $pembayaran_sebelumnya)
        Rp. {{  number_format($grandtotal,0,',','.') ?? '' }}
    </td>
  </tr>
  <tr>
    <td>Uang Jalan yang diserahkan</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal;">Rp. {{ number_format($payment['nominal'] ,0,',','.')}}</td>
  </tr>

  <tr>
    <td>Terbilang Uang Jalan yang diserahkan</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal;  text-transform: uppercase">
        {{Riskihajar\Terbilang\Facades\Terbilang::make($payment['nominal'], ' rupiah')  ?? '' }}
    </td>
  </tr>

  <tr>
    <td>Sisa Uang Jalan</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal;">

        Rp. {{  number_format($joborder['sisa_uang_jalan'],0,',','.') ?? '' }}
    </td>
  </tr>








  <tr>
    <td> Jenis Payment</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal; text-transform: uppercase">{{$payment['jenis_payment']}}</td>
  </tr>

  <tr>
    <td>Keterangan</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal; text-transform: uppercase"></td>
  </tr>
  @if(isset($payment['kasbon_id']))
  <tr>
    <td>Keterangan Potongan Bon (Kode Kasbon)</td>
    <td style="width: 2px; padding-right: 10px">:</td>
    <td style="font-weight: normal; text-transform: uppercase">
         {{ isset($payment['kasbon']['kode_kasbon']) ? $payment['keterangan_kasbon']. ' ('. $payment['kasbon']['kode_kasbon'].')' : '' }}
    </td>
  </tr>

  @endif


</table>

<hr>
<hr>

<table id="ttd" style="margin-top: 20px;">
  <tr>
    <td
      style="min-width: 33%; font-weight: normal; text-align: left">
    </td>
  </tr>
  <tr>
    <td style="min-width: 33%; font-weight: normal; text-align: center">Yang Membuat</td>
    <td style="min-width: 33%; font-weight: normal; text-align: center">Yang Menyerahkan</td>
    <td style="min-width: 33%; font-weight: normal; text-align: center">Yang Menerima</td>
  </tr>
  <tr>
    <td style="padding-top: 50px; max-width: 33%; text-align: center; text-transform: uppercase">( _ _ _ _ _ _ _ _ _ _ _ _ _ _ _)</td>
    <td style="padding-top: 50px; max-width: 33%; text-align: center">( _ _ _ _ _ _ _ _ _ _ _ _ _ _ _)</td>
    <td style="padding-top: 50px; max-width: 33%; text-align: center">( _ _ _ _ _ _ _ _ _ _ _ _ _ _ _)</td>
  </tr>
</table>

</div>



</body>
</html>
