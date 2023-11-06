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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">LAPORAN KASBON DRIVER</h3>
        @if($data['tgl_awal'] != null && $data['tgl_akhir'] != null)
        <h5  style=" text-align: center; margin-top:25px;margin-bottom: 0">TANGGAL : {{\Carbon\Carbon::parse($data['tgl_awal'])->format('d-m-Y')}} S/D {{\Carbon\Carbon::parse($data['tgl_akhir'])->format('d-m-Y')}} </h5>
        @endif
        <p style=" text-align: center; margin-bottom: 0; font-size:8px;"> Print By : {{ Auth::user()->name ?? '' }} ( {{  \Carbon\Carbon::now()->format('d-m-Y H:i:s')  }} )</p>
    </div>



    <table id="pakettable" >
        <thead style="display: table-row-group;">
              <tr>
                <th width="2%">No</th>
                <th width="15%">Tanggal</th>
                <th>Nama Supir</th>
                <th>Kasbon</th>
                <th width="10%">Status Supir</th>
              </tr>
        </thead>
        <tbody>
            @php($no=1)
            @foreach ($data['data']->get() as $val)
            @php($status_aktif = $val['status_aktif'] == '0' ? 'Tidak Aktif' : 'Aktif')
                <tr>
                    <td class="text-center">{{$no++}}</td>
                    <td>{{$val->tgl_aktif}}</td>
                    <td>{{$val->name ?? '-'}}</td>
                    <td  class="text-end">Rp. {{ number_format($val->kasbon,0,',','.')}}</td>
                    <td>{{$status_aktif ?? '-'}}</td>
                </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Total :</th>
                <th class="text-end"  id="total_debit">Rp. {{ number_format($data['data']->sum('kasbon'),0,',','.')}}</th>
                <th></th>
            </tr>
        </tfoot>


    </table>




</body>
</html>
