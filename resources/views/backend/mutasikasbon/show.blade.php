@extends('backend.layouts.master')

@section('content')

<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">

                <div class="col-xl-12">
                    <div class="mt-xl-0 mt-4">
                        <div class="d-flex align-items-start">


                            <div class="flex-grow-1">
                                <div class="col-xl-12">
                                    <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Filter</button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="float-end">
                                    {{-- <a onclick="printDiv('printableArea')" class="btn btn-success me-1"><i class="fa fa-print"></i></a> --}}
                                    <a onclick="window.history.back();" class="btn btn-primary w-md">Kembali</a>
                                    <div class="float-end" id="print">
                                    </div>
                                    <div class="dt-buttons btn-group flex-wrap">
                                         <button id="excel" class="btn btn-secondary buttons-excel buttons-html5"  tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                        <button class="btn btn-secondary buttons-pdf buttons-html5"  tabindex="0" aria-controls="Datatable" id="pdf" type="button"><span>PDF</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <input id="cek_driver_id" name="cek_gaji_id" type="hidden" value="{{$data['gaji']['id'] ?? ''}}"> --}}
                        <input id="cek_name" name="cek_name" type="hidden" value="{{$data['driver']['name'] ?? ''}}">
                        {{-- {{dd($data['mutasi'])}} --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                    <div class="card border shadow-none card-body text-muted mb-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Filter Tanggal</label>
                                                    <div class=" input-group mb-3">
                                                        <input type="text" id="tgl_awal" class="form-control datePicker"
                                                                placeholder="Tanggal Awal"  value="{{ \Carbon\Carbon::now()->startOfYear()->format('Y-m-d') }}"
                                                               />
                                                        <span class="input-group-text" id="basic-addon2">S/D</span>
                                                        <input type="text" id="tgl_akhir" class="form-control datePicker"
                                                                placeholder="Tanggal Akhir"  value="{{ \Carbon\Carbon::now()->lastOfYear()->format('Y-m-d') }}"
                                                                />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Driver<span class="text-danger">*</span></label>
                                                    <select id="select2Driver" style="width: 100% !important;" name="driver_id">
                                                        <option value="{{ $data['driver']['id'] ?? '' }}"> {{$data['driver']['name'] ?? '' }}</option>
                                                    </select>
                                                  </div>
                                            </div>
                                            <div class="col-md-2 text-end" style="padding-top:30px;">
                                                <a id="terapkan_filter" class="btn btn-success">
                                                    Terapkan Filter
                                                    <i class="fas fa-align-justify"></i>
                                                </a>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <div class="card-body" id="printableArea">
                <div class="invoice-title">
                    <h6 class="main-content-label mb-1">{{ $config['page_title'] ?? '' }}</h6>
                    <div class="mb-4">
                           {{-- <img  src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="logo" height="50"> --}}
                    </div>
                    <div class="text-muted">
                        <h5 class="text-center" id="driver_name">Nama Driver : {{$data['driver']['name'] ?? '' }}</h5>
                    </div>
                </div>



                <div class="row" style="padding-top:10px;">
                    <div class="table-responsive">
                        <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                            <thead style="display: table-row-group;">
                                <tr>
                                    <th colspan="5"style="text-align:right">Saldo Bon Awal: </th>
                                    <th colspan="3" class="text-end" id="saldo_awal"></th>
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
                                  </tr>
                            </thead>
                            <tbody>

                            </tbody>


                                 <tr>
                                     <th colspan="5" style="text-align:right">Total Debit :</th>
                                     <th colspan="3" class="text-end"  id="total_debit"></th>
                                 </tr>
                                 <tr>
                                    <th colspan="5" style="text-align:right">Total Kredit :</th>
                                    <th colspan="3"  class="text-end"  id="total_kredit"></th>
                                </tr>
                                 <tr>
                                    <th colspan="5"style="text-align:right">Saldo Bon Akhir: </th>
                                    <th colspan="3" class="text-end"  id="saldo_akhir"></th>
                                 </tr>

                        </table>
                    </div>
                </div>




            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
@media print
{
    @page {
      size: A4; /* DIN A4 standard, Europe */
      margin: 27mm 16mm 27mm 16mm;
    }
    html, body {
        width: 210mm;
        /* height: 297mm; */
        height: 282mm;
        font-size: 16px;
        color: #000;
        background: #FFF;
        overflow:visible;
    }
    body {
        padding-top:15mm;
    }
    table {
        border: solid #000 !important;
        border-width: 1px 0 0 1px !important;
    }
    th, td {
        border: solid #000 !important;
        border-width: 0 1px 1px 0 !important;
    }
}
</style>
@endsection
@section('script')
  <script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}



    $(document).ready(function () {

        $('#tgl_awal, #tgl_akhir').flatpickr({
            dateFormat: "Y-m-d"
         });
         let select2Driver = $('#select2Driver');
    select2Driver.select2({
        dropdownParent:  select2Driver.parent(),
        searchInputPlaceholder: 'Cari Driver',
        width: '100%',
        placeholder: 'Pilih Driver',
        ajax: {
          url: "{{ route('backend.driver.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            $('#cek_name').val(data.text)
            console.log(data.id);
    });
        $("#excel").click(function() {
                    let params = new URLSearchParams({
                        driver_id :  select2Driver.find(':selected').val(),
                        tgl_awal : $('#tgl_awal').val(),
                        tgl_akhir : $('#tgl_akhir').val()
                    });

                    let url = "{{ route('backend.mutasikasbon.excel') }}?" +params.toString();
                    window.open(url, '_blank');
        });

        $("#pdf").click(function() {
                    let params = new URLSearchParams({
                        driver_id :  select2Driver.find(':selected').val(),
                        tgl_awal : $('#tgl_awal').val(),
                        tgl_akhir : $('#tgl_akhir').val()
                    });

                    let url = "{{ route('backend.mutasikasbon.pdf') }}?" +params.toString();
                    window.open(url, '_blank');
        });
    let dataTable = $('#Datatable').DataTable({
        // dom: 'lfBrtip',
        // buttons: [
        //     {
        //         extend: 'excel',
        //         footer: true,
        //         text: 'Excel',
        //         title: 'Laporan Kasbon',
        //         exportOptions: {
        //             columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
        //         }
        //     },
        //     {
        //         extend: 'pdfHtml5',
        //         footer: true,
        //         text: 'PDF',
        //         title: 'Laporan Kasbon',
        //         pageSize: 'A4',
        //         exportOptions: {
        //             columns: [ 0, 1, 2, 3, 4, 5, 6 , 7]
        //         },
        //         // customize : function(doc) {
        //         //     doc.styles['td:nth-child(2)'] = {
        //         //     width: '200px',
        //         //     'max-width': '200px'
        //         //     }
        //         // }
        //     },


        // ],
        searching: false, paging: false, info: false,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.mutasikasbon.index') }}",
          data: function (d) {
            d.driver_id =  select2Driver.find(':selected').val();
            d.tgl_awal = $('#tgl_awal').val();
            d.tgl_akhir = $('#tgl_akhir').val();
          }
        },

        columns: [
        //   {
        //         data: "id", name:'id',
        //         render: function (data, type, row, meta) {
        //             return meta.row + meta.settings._iDisplayStart + 1;
        //         }
        //   },
          {data: 'tgl_kasbon', name: 'tgl_kasbon'},
             {
               data: "kode_kasbon", name:'kode_kasbon',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                let kode = '-';
                if(data !== null){
                    kode = '<a target="_blank" href="{{ route('backend.kasbon.index') }}?kasbon_id='+row.kasbon_id+'">'+data+'</a>';
                }
                   return kode;
               }
          },
          {
               data: "gaji.kode_gaji", name:'gaji.kode_gaji',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                let kode = '-';
                if(row.gaji !== null){
                    kode = '<a target="_blank" href="{{ route('backend.penggajian.index') }}?penggajian_id='+row.gaji.id+'">'+data+'</a>';
                }
                   return kode;
               }
          },
          {
               data: "joborder.kode_joborder", name:'joborder.kode_joborder',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                let kode = '-';
                if(row.joborder !== null){
                    kode = '<a target="_blank" href="{{ route('backend.joborder.index') }}?joborder_id='+row.joborder.id+'">'+data+'</a>';
                }
                   return kode;
               }
          },
          {data: 'keterangan', name: 'keterangan'},
          {data: 'debit', name: 'debit'},
          {data: 'kredit', name: 'kredit'},
          {data: 'new_saldo', name: 'new_saldo'},
        //   {data: 'action', name: 'action',  className: 'text-center', orderable: false, searchable: false},
        ],
        columnDefs: [

          {
            orderable: false, searchable: false,
            defaultContent: "-",
            targets: [0, 1, 2, 3 ,4 ,5 ,6 , 7]
         },
         {
            className: 'text-end',
            targets: [5, 6, 7],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          },

        ],
        footerCallback: function (row, data, start, end, display) {


            // console.log();
            // DataSet1.Tables(0).Rows(4).Item(0) = "Updated Company Name";
            // var api = this.api();

            // // Remove the formatting to get integer data for summation
            // var intVal = function (i) {
            //     return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            // };

            // // Total over all pages
            // total_debit = api
            //     .column(5)
            //     .data()
            //     .reduce(function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0);

            // total_kredit = api
            //     .column(6)
            //     .data()
            //     .reduce(function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0);

            // // Total over this page
            // // pageTotal = api
            // //     .column(5, { page: 'current' })
            // //     .data()
            // //     .reduce(function (a, b) {
            // //         return intVal(a) + intVal(b);
            // //     }, 0);

            // console.log( total_debit);

            // // Update footer
            // $(api.column(7).footer()).html('$' + pageTotal + ' ( $' + total + ' total)');
        },
      });
     // dataTable.buttons().container().appendTo($('#print'));
      $("#terapkan_filter").click(function() {
        dataTable.draw();
        $('#driver_name').text($('#cek_name').val());
        get_saldo();
      });
      get_saldo();
      function get_saldo(){
            let driver_id = select2Driver.find(':selected').val();
            console.log(driver_id);
            let tgl_awal = $('#tgl_awal').val();
            let tgl_akhir = $('#tgl_akhir').val();
         $.ajax({
                url: "{{ route('backend.mutasikasbon.ceksaldo') }}",
                type: 'GET',
                data: {driver_id:   driver_id, tgl_awal: tgl_awal, tgl_akhir: tgl_akhir},
                dataType: 'json', // added data type
                success: function(res) {

                    $("#saldo_awal").html($.fn.dataTable.render.number('.', ',', 0, '').display(res.saldo_awal));
                    $("#total_debit").html($.fn.dataTable.render.number('.', ',', 0, '').display(res.total_debit));
                    $("#total_kredit").html($.fn.dataTable.render.number('.', ',', 0, '').display(res.total_kredit));
                    $("#saldo_akhir").html($.fn.dataTable.render.number('.', ',', 0, '').display(res.saldo_akhir));
                }
         });
     }

     //Will take you to Google.
    });
  </script>
@endsection
