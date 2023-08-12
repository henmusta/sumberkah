<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

@page {
        /* size: 21cm 15cm; */
        size: landscape;
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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">LAPORAN MUTASI KASBON - {{$data['driver']}}</h3>
        @if($data['tgl_awal'] != null && $data['tgl_akhir'] != null)
        <h5  style=" text-align: center; margin-top:25px;margin-bottom: 0">TANGGAL : {{\Carbon\Carbon::parse($data['tgl_awal'])->format('d-m-Y')}} S/D {{\Carbon\Carbon::parse($data['tgl_akhir'])->format('d-m-Y')}} </h5>
        @endif
    </div>



    <table id="pakettable" >
        <thead style="display: table-row-group;">
            <tr>
                <th colspan="5"style="text-align:right">Saldo Bon Awal: </th>
                <th colspan="3" class="text-end">Rp. {{ number_format($data['saldo_awal'],0,',','.')}}</th>
                <th></th>
             </tr>
              <tr>
                <th>Tanggal Transaksi</th>
                <th>Kode Kasbon</th>
                <th>Kode Gaji</th>
                <th>Kode Joborder</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Saldo Kasbon</th>
                <th>Operator (Waktu)</th>
              </tr>
        </thead>
        <tbody>
            {{-- @php($no=1) --}}
            @foreach ($data['mutasikasbon'] as $val)

                <tr>
                    <td>{{$val->tgl_kasbon}}</td>
                    <td>{{$val->kode_kasbon}}</td>
                    <td>{{$val->gaji['kode_gaji'] ?? '-'}}</td>
                    <td>{{$val->joborder['kode_joborder'] ?? '-'}}</td>
                    <td width="150px">{{$val->keterangan ?? ''}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->kredit,0,',','.')}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->debit,0,',','.')}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->new_saldo,0,',','.')}}</td>
                    <td width="150px" class="text-end">{{$val->kasbon['createdby']->name ?? ''}} ( {{\Carbon\Carbon::parse($val['kasbon']->created_at)->format('d-m-Y H:i:s') ?? ''}} )</td>
                </tr>
            @endforeach

        </tbody>


             <tr>
                 <th colspan="5" style="text-align:right">Total Debit :</th>
                 <th colspan="3" class="text-end"  id="total_debit">Rp. {{ number_format($data['total_debit'],0,',','.')}}</th>
                 <th></th>
             </tr>
             <tr>
                <th colspan="5" style="text-align:right">Total Kredit :</th>
                <th colspan="3"  class="text-end"  id="total_kredit">Rp. {{ number_format($data['total_kredit'],0,',','.')}}</th>
                <th></th>
            </tr>
             <tr>
                <th colspan="5"style="text-align:right">Saldo Bon Akhir: </th>
                <th colspan="3" class="text-end"  id="saldo_akhir">Rp. {{ number_format($data['saldo_akhir'],0,',','.')}}</th>
                <th></th>
             </tr>

    </table>


    <footer>
        Page <span class="page-number"></span>
    </footer>


</body>
</html>
