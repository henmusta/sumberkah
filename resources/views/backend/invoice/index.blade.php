@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mb-3">
                <h5 class="card-title mb-3">Table {{ $config['page_title'] }}</h5>
                <div class="col-md-xl-12">
                    <div class="mt-xl-0 mt-4">

                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Filter</button>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a class="btn btn-primary " href="{{ route('backend.invoice.create') }}">
                                    Tambah
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                    <div class="card border shadow-none card-body text-muted mb-0">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Status Payment<span class="text-danger">*</span></label>
                                                    <select id="select2StatusPayment" style="width: 100% !important;" name="status_Payment">
                                                        <option value=""></option>
                                                        <option value="0">Belum Bayar</option>
                                                        <option value="1">Cicilan</option>
                                                        <option value="2">Lunas</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                  <div class="mb-3">
                                                    <label>Tanggal Jatuh Tempo<span class="text-danger">*</span></label>
                                                    <input type="text" id="tgl_jatuh_tempo" class="form-control datePicker" name="tgl_invoice" placeholder="Tanggal Jatuh Tempo" value=""/>
                                                  </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Nomor Invoice<span class="text-danger">*</span></label>
                                                    <select id="select2Invoice" style="width: 100% !important;" name="invoice_id">
                                                        <option value="{{ $data['invoice']['id'] ?? '' }}"> {{$data['invoice']['kode_invoice'] ?? '' }}</option>
                                                    </select>
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Customer<span class="text-danger">*</span></label>
                                                    <select id="select2Customer" style="width: 100% !important;" name="customer_id">

                                                    </select>
                                                  </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>PPN<span class="text-danger">*</span></label>
                                                    <select id="select2Ppn" style="width: 100% !important;" name="ppn">
                                                        <option value=""></option>
                                                        <option value="Iya">Iya</option>
                                                        <option value="Tidak">Tidak</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Tanggal Invoice<span class="text-danger">*</span></label>
                                                    <input type="text" id="tgl_invoice" class="form-control datePicker" name="tgl_invoice" placeholder="Tanggal Invoice" value=""/>
                                                  </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label>Filter Tanggal</label>
                                                    <div class=" input-group mb-3">
                                                        <input type="text" id="tgl_awal" class="form-control datePicker"
                                                                placeholder="Tanggal Awal" value=""
                                                               />
                                                        <span class="input-group-text" id="basic-addon2">S/D</span>
                                                        <input type="text" id="tgl_akhir" class="form-control datePicker"
                                                                placeholder="Tanggal Akhir" value=""
                                                                />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-end" style="padding-top:30px;">
                                                <a id="terapkan_filter" class="btn btn-success">
                                                    Terapkan Filter
                                                    <i class="fas fa-align-justify"></i>
                                                </a>
                                            </div>
                                            <div class="col-md-2 text-end" style="padding-top:30px;">
                                                <div id="print">
                                                </div>
                                            </div>


                                        </div>






                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <div class="card-body">
                <div class="table">
                    <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">Kode Invoice</th>
                                <th>Tanggal Invoice</th>
                                <th>Customer</th>
                                <th>Total Tagihan</th>
                                <th>Sisa Tagihan</th>
                                <th>Batas Pembayaran</th>
                                <th>Status Pembayaran</th>
                                <th width="8%">Aksi</th>
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
 {{--Modal--}}



 <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDeleteLabel">Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @method('DELETE')
        <div class="modal-body">
          <a href="" class="urlDelete" type="hidden"></a>
          Apa anda yakin ingin menghapus data ini?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="formDelete" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>


@endsection

@section('css')
<style>

tr.group,
tr.group:hover {
    background-color: #22354eaa !important;
}

</style>
@endsection
@section('script')
<script src="https://cdn.datatables.net/rowgroup/1.0.2/js/dataTables.rowGroup.min.js"></script>

  <script>

     $(document).ready(function () {
        let select2Customer = $('#select2Customer');
        let select2Invoice = $('#select2Invoice');
        let select2StatusPayment = $('#select2StatusPayment');
        let select2Ppn = $('#select2Ppn');
        $('#tgl_awal, #tgl_akhir, #tgl_jatuh_tempo, #tgl_invoice').flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true
         });
    select2StatusPayment.select2({
        dropdownParent:select2StatusPayment.parent(),
        searchInputPlaceholder: 'Cari Status Payment',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Status Payment',

      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });

      select2Invoice.select2({
        dropdownParent:  select2Invoice.parent(),
        searchInputPlaceholder: 'Cari Invoice',
        allowClear: true,
        width: '100%',
        placeholder: 'Pilih Invoice',
        ajax: {
          url: "{{ route('backend.invoice.select2') }}",
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
      });


      select2Customer.select2({
        dropdownParent: select2Customer.parent(),
        searchInputPlaceholder: 'Cari Customer',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Customer',
        ajax: {
          url: "{{ route('backend.customer.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              jenismobil_id:  select2Jenis.find(":selected").val() || '00',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;

    });


       select2Ppn.select2({
        dropdownParent: select2Ppn.parent(),
        searchInputPlaceholder: 'Cari Status Ppn',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Status Ppn',

      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });


        let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);
      let dataTable = $('#Datatable').DataTable({
        dom: 'lfBrtip',
        buttons: [
            {
                extend: 'excel',
                footer: true,
                text: 'Excel',
                title: 'Laporan Invoice',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'pdfHtml5',
                footer: true,
                text: 'PDF',
                title: 'Laporan Invoice',
                pageSize: 'A4',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ,6]
                },
                // customize : function(doc) {
                //     doc.styles['td:nth-child(2)'] = {
                //     width: '200px',
                //     'max-width': '200px'
                //     }
                // }
            },


        ],
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.invoice.index') }}",
          data: function (d) {
            d.status_payment = $('#select2StatusPayment').find(':selected').val();
            d.ppn = $('#select2Ppn').find(':selected').val();
            d.id = $('#select2Invoice').find(':selected').val();
            d.tgl_invoice = $('#tgl_invoice').val();
            d.tgl_jatuh_tempo = $('#tgl_jatuh_tempo').val();
            d.tgl_awal = $('#tgl_awal').val();
            d.tgl_alhir = $('#tgl_akhir').val();
          }
        },

        columns: [
          {data: 'kode_invoice', className:'text-center', name: 'kode_invoice'},
          {data: 'tgl_invoice', name: 'tgl_invoice'},
          {data: 'customer.name', className: 'text-left', name: 'customer.name'},
          {data: 'total_harga', name: 'total_harga'},
          {data: 'sisa_tagihan', name: 'sisa_tagihan'},
          {data: 'tgl_jatuh_tempo', name: 'tgl_tempo_tempo'},
          {data: 'status_payment', name: 'status_payment'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],

        columnDefs: [
          {
            targets: [3, 4],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          },
          {
            className: 'dt-center',
            targets: 6,
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'Belum Bayar', 'class': ' bg-danger'},
                1: {'title': 'Belum Lunas', 'class': ' bg-warning'},
                2: {'title': 'Lunas', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
            //   let url;
            //   if(data > 0){
            //     url = '';
            //   }else{
            //     url = '';
            //   }


              return '<a><span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span></a>';
            },
          },
        ],
      });
      dataTable.buttons().container().appendTo($('#print'));
      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.invoice.index") }}/' + id);
      });
      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });

      $("#terapkan_filter").click(function() {
        dataTable.draw();
      });

      $("#formDelete").click(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = modalDelete.querySelector('.urlDelete').getAttribute('href');
        let btnHtml = form.html();
        let spinner = $("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span>");
        $.ajax({
          beforeSend: function () {
            form.text(' Loading. . .').prepend(spinner).prop("disabled", "disabled");
          },
          type: 'DELETE',
          url: url,
          dataType: 'json',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success: function (response) {
            toastr.success(response.message, 'Success !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            dataTable.draw();
            bsDelete.hide();
          },
          error: function (response) {
            toastr.error(response.responseJSON.message, 'Failed !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            bsDelete.hide();
          }
        });
      });
    });
  </script>
@endsection
