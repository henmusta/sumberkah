@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        {{-- <h5 class="card-title mb-3">{{ $config['page_title'] }}</h5> --}}
        <div class="card">
            <div class="card-header text-center">
                <h2 class="">{{ $config['page_title'] }}</h2>
            </div>
            <div class="card-body">
            <!-- Nav tabs -->
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-bs-toggle="tab" href="#home-1" role="tab" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span style="font-size: 24px" class="d-none d-sm-block">IJIN DAN DOKUMEN</span>
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#profile-1" role="tab" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                            <span  style="font-size: 24px" class="d-none d-sm-block">OPERASIONAL</span>
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#messages-1" role="tab" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                            <span  style="font-size: 24px" class="d-none d-sm-block">INVOICE</span>
                        </a>
                    </li>

                </ul>

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="home-1" role="tabpanel" style="padding-top:20px">
                        <p class="mb-0">


                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            <div class="alert alert-primary alert-dismissible fade show px-3 mb-0" role="alert">
                                                <h3 class="text-black">INFORMASI DRIVER</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Berlaku Sim</h4>
                                            </div>
                                            <div class="table-responsove">
                                                <table id="Datatablesim" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>Nama Lengkap</th>
                                                            <th>No hp</th>
                                                            <th>Tanggal Expired SIM</th>
                                                            <th>Masa  Berlaku</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="card">
                                <div class="card-header text-center">

                                    <div class="alert alert-warning alert-dismissible fade show px-3 mb-0" role="alert">
                                        <h3 class="text-black">INFORMASI KENDARAAN</h3>
                                    </div>
                                </div>

                                    <div class="col-6">
                                        <div class="card-body">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Pajak 1 Tahun Kendaraan</h4>
                                            </div>
                                            <div class="table-responsove">
                                                <table id="Datatablestnk" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>No Polisi</th>
                                                            <th>Tanggal Expired</th>
                                                            <th>Masa  Berlaku</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                        <div class="card-body">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">KIR Kendaraan</h4>
                                            </div>
                                            <div class="table-responsove">
                                                <table id="Datatablekir" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>No Polisi</th>
                                                            <th>Tanggal Expired</th>
                                                            <th>Masa  Berlaku</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card-body">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Pajak 5 Tahun Kendaraan</h4>
                                            </div>
                                            <div class="table-responsove">
                                                <table id="Datatablepajak" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>No Polisi</th>
                                                            <th>Tanggal Expired</th>
                                                            <th>Masa  Berlaku</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                        <div class="card-body">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Ijin Bongkar Muat (BM) Kendaraan</h4>
                                            </div>
                                            <div class="table-responsove">
                                                <table id="Datatablebm" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>No Polisi</th>
                                                            <th>Tanggal Expired</th>
                                                            <th>Masa  Berlaku</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                        </div>
                                        <div class="col-6">
                                            <div class="card-body">
                                                <div class="text-center"  style="padding: 20px">
                                                    <h4 class="main-content-label mb-1">Ijin Usaha</h4>
                                                </div>
                                                <div class="table-responsove">
                                                    <table id="Datatableijinusaha" class="table table-bordered border-bottom w-100" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th>No Polisi</th>
                                                                <th>Tanggal Expired</th>
                                                                <th>Masa  Berlaku</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-3">
                                        </div>
                                    </div>



                            </div>
                        </div>
                        </p>
                    </div>
                    <div class="tab-pane" id="profile-1" role="tabpanel" style="padding-top:20px">
                        <p class="mb-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            <div class="alert alert-secondary alert-dismissible fade show px-3 mb-0" role="alert">
                                                <h3 class="text-black">SUPIR DAN KENDARAAN TIDAK JALAN</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Supir</h4>
                                            </div>
                                            <div class="table-responsove">
                                                <table id="Datatablesupirtj" class="table table-bordered border-bottom" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="1%">No</th>
                                                            <th>Nama Lengkap</th>
                                                            <th>No hp</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                        <div class="card-body">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Kendaraan</h4>
                                            </div>
                                            <div class="table-responsove">
                                                <table id="Datatablemobiltj" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="1%">No</th>
                                                            <th>Merek</th>
                                                            <th>Jenis</th>
                                                            <th>Dump</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                    </div>
                    <div class="tab-pane" id="messages-1" role="tabpanel" style="padding-top:20px">
                        <p class="mb-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            <div class="alert alert-danger alert-dismissible fade show px-3 mb-0" role="alert">
                                                <h3 class="text-black">JOBORDER BELUM ADA INVOICE</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center"  style="padding: 20px">
                                                {{-- <h4 class="main-content-label mb-1">Berlaku Sim</h4> --}}
                                            </div>
                                            <div class="table-responsove">
                                                <table id="Datatablejo" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>Kode Joborder</th>
                                                            <th>Driver</th>
                                                            <th>Nomor Polisi</th>
                                                            <th>Jenis Mobil</th>
                                                            <th>Customer</th>
                                                            <th>Muatan</th>
                                                            <th>Alamat Awal (Dari)</th>
                                                            <th>Alamat Akhir (Ke)</th>
                                                            <th>Tanggal Closing</th>
                                                            <th>Aksi</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            <div class="alert alert-success alert-dismissible fade show px-3 mb-0" role="alert">
                                                <h3 class="text-black">INVOICE JATUH TEMPO</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Berlaku Sim</h4>
                                            </div>
                                            <div class="table-responsove">
                                                <table id="Datatableinvoice" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>Kode Invoice</th>
                                                            <th>Customer</th>
                                                            <th>Nominal Invoice</th>
                                                            <th>Due Date</th>
                                                            <th>Status</th>
                                                          </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                    </div>

                </div>

            </div>
        </div>









    </div>
    <!-- container-fluid -->
</div>
@endsection

@section('css')

@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/jspdf@1.5.3/dist/jspdf.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.0/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script>

    $(document).ready(function () {

    let dataTablejo = $('#Datatablejo').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[1, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtjo') }}",
         data: function (d) {
           // d.status = $('#Select2Status').find(':selected').val();
         }
       },

       columns: [
         {
               data: "id", name:'id',
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'kode_joborder', name: 'kode_joborder'},
         {data: 'driver.name', name: 'driver.name'},
         {data: 'mobil.nomor_plat', name: 'mobil.nomor_plat'},
         {data: 'jenismobil.name', name: 'jenismobil.name'},
         {data: 'customer.name', name: 'customer.name'},
         {data: 'muatan.name', name: 'muatan.name'},
         {data: 'ruteawal.name', name: 'ruteawal.name'},
         {data: 'ruteakhir.name', name: 'ruteakhir.name'},
         {data: 'konfirmasijo.0.tgl_konfirmasi', name: 'konfirmasijo.0.tgl_konfirmasi'},
         {data: 'action', name: 'action', orderable: false, searchable: false},
       ],
       columnDefs: [


       ],
     });



     let dataTableinvoice = $('#Datatableinvoice').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[0, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtinvoice') }}",
         data: function (d) {
           // d.status = $('#Select2Status').find(':selected').val();
         }
       },

       columns: [
         {
               data: "id", name:'id',
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'kode_invoice', name: 'kode_invoice'},
         {data: 'customer.name', name: 'customer.name'},
         {data: 'total_harga', name: 'total_harga'},
         {data: 'tgl_jatuh_tempo', name: 'tgl_jatuh_tempo'},
         {data: 'exp_due', className:'text-center', name: 'exp_due'},

       ],
       columnDefs: [


       ],
     });



     let dataTablesim = $('#Datatablesim').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[1, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtdriver') }}",
         data: function (d) {
           // d.status = $('#Select2Status').find(':selected').val();
         }
       },

       columns: [
         {
               data: "id", name:'id',
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'name', name: 'name'},
         {data: 'telp', name: 'telp'},
         {data: 'tgl_sim', name: 'tgl_sim'},
         {data: 'exp_sim', className:'text-center', name: 'exp_sim'},

       ],
       columnDefs: [


       ],
     });

     let dataTablesupirtj = $('#Datatablesupirtj').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[1, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtdriver') }}",
         data: function (d) {
           d.status_jalan = 1;
         }
       },

       columns: [
         {
               data: "id", name:'id', className:'text-center', width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'name', name: 'name', width: "50%"},
         {data: 'telp', name: 'telp', width: "49%"}
       ],
       columnDefs: [


       ],
     });


     let dataTablemobiltj = $('#Datatablemobiltj').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[1, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
        url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
            d.status_jalan = 1;
         }
       },

       columns: [
         {
               data: "id", name:'id',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'merkmobil.name', name: 'merkmobil.name'},
         {data: 'jenismobil.name', name: 'jenismobil.name'},
         {data: 'dump', className:'text-center', name: 'dump'},
       ],
     });

     let dataTablestnk = $('#Datatablestnk').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[1, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
        url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
           // d.status = $('#Select2Status').find(':selected').val();
         }
       },

       columns: [
         {
               data: "id", name:'id',
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_stnk', name: 'berlaku_stnk'},
         {data: 'exp_stnk', className:'text-center', name: 'exp_stnk'},
       ],
     });



     let dataTablepajak = $('#Datatablepajak').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: false,
       order: [[1, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
           // d.status = $('#Select2Status').find(':selected').val();
         }
       },

       columns: [
        {
               data: "id", name:'id', className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_pajak', name: 'berlaku_pajak'},
         {data: 'exp_pajak', className:'text-center', name: 'exp_pajak'},

       ],
       columnDefs: [


       ],
     });




     let dataTablekir = $('#Datatablekir').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[1, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
           // d.status = $('#Select2Status').find(':selected').val();
         }
       },

       columns: [
        {
               data: "id", name:'id',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_kir', name: 'berlaku_kir'},
         {data: 'exp_kir', className:'text-center', name: 'exp_kir'},

       ],
       columnDefs: [


       ],
     });

     let dataTablebm = $('#Datatablebm').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: false,
       order: [[1, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
           // d.status = $('#Select2Status').find(':selected').val();
         }
       },

       columns: [
        {
               data: "id", name:'id',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_ijin_bongkar', name: 'berlaku_ijin_bongkar'},
         {data: 'exp_bm', className:'text-center', name: 'exp_bm'},

       ],
       columnDefs: [


       ],
     });


     let dataTableijinusaha = $('#Datatableijinusaha').DataTable({
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: false,
       order: [[1, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
           // d.status = $('#Select2Status').find(':selected').val();
         }
       },

       columns: [
        {
               data: "id", name:'id',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_ijin_usaha', name: 'berlaku_ui'},
         {data: 'exp_iu', className:'text-center', name: 'expiu'},

       ],
       columnDefs: [


       ],
     });


   });
 </script>
@endsection
