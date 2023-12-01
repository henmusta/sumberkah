<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

@page {
        /* size: 21cm 15cm; */
        margin: 0;
        margin: 10mm 10mm 20mm 10mm;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0 5px;
      /* height: 50% !important; */
    }

    .text-end{
        text-align: right !important;
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
        <h3  style=" text-align: center; margin-top:25px;margin-bottom: 0">Data Driver</h3>
    </div>

    <table id="pakettable">
        <thead style="background-color: #fff !important; color:black;">
            <tr>
                <th>Nama Lengkap</th>
                <th>Nama Panggilan</th>
                <th>Tempat/Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Hp/Telp</th>
                <th>Ktp</th>
                <th>Sim</th>
                <th width="60px">Tanggal Berlaku Sim</th>
                <th width="60px">Tgl Registrasi</th>
                <th width="60px">Tgl Perubahan</th>
                <th>Nama Keluarga</th>
                <th>Telp/Hp Keluarga</th>
                <th>Referensi</th>
                <th>Status aktif</th>
            </tr>
        </thead>
        <tbody>
            @php($no=1)
            @foreach ($data['driver'] as $val)
            {{-- @php($status_jalan = $val['status_jalan'] == '0' ? 'Tidak Jalan' : 'Jalan') --}}
            @php($status_aktif = $val['status_aktif'] == '0' ? 'Tidak Aktif' : 'Aktif')
                <tr>
                    <td>{{$val->name}}</td>
                    <td>{{$val->panggilan}}</td>
                    <td>{{$val->tempat_lahir ?? ''}}/{{$val->tanggal_lahir ?? ''}}</td>
                    <td>{{$val->alamat ?? ''}}</td>
                    <td>{{$val->telp ?? ''}}</td>
                    <td>{{$val->ktp ?? ''}}</td>
                    <td>{{$val->sim ?? ''}}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($val['tgl_sim'])->format('d-m-Y')}}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($val['created_at'])->format('d-m-Y')}}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($val['tgl_aktif'])->format('d-m-Y')}}</td>
                    <td>{{$val->darurat_name ?? ''}}</td>
                    <td>{{$val->darurat_telp ?? ''}}</td>
                    <td>{{$val->darurat_ref ?? ''}}</td>
                    <td>{{$status_aktif}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>




</body>
</html>
