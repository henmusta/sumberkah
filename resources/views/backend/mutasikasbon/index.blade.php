@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header mb-3">
                <h5 class="card-title mb-3">Table {{ $config['page_title'] }}</h5>
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
                                                                placeholder="Tanggal Awal"  value=""
                                                               />
                                                        <span class="input-group-text" id="basic-addon2">S/D</span>
                                                        <input type="text" id="tgl_akhir" class="form-control datePicker"
                                                                placeholder="Tanggal Akhir"  value=""
                                                                />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Status<span class="text-danger">*</span></label>
                                                    <select id="select2Status" style="width: 100% !important;" name="status_aktif">
                                                        <option></option>
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Tidak Aktif</option>
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
            <div class="card-body">
                <div class="table-responsive">
                    <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Tanggal</th>
                                <th>Nama Supir</th>
                                <th>Kasbon</th>
                                <th width="10%">Status Supir</th>
                                <th width="10%">Aksi</th>
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


</style>
@endsection
@section('script')


  <script>

     $(document).ready(function () {
        $('#tgl_awal, #tgl_akhir').flatpickr({
            dateFormat: "Y-m-d"
         });


         $("#excel").click(function() {
                    let params = new URLSearchParams({
                        jenis : 'excel_driver',
                        status :  select2Status.find(':selected').val(),
                        tgl_awal : $('#tgl_awal').val(),
                        tgl_akhir : $('#tgl_akhir').val()
                    });

                    let url = "{{ route('backend.mutasikasbon.excel') }}?" +params.toString();
                    window.open(url, '_blank');
        });

        $("#pdf").click(function() {
                    let params = new URLSearchParams({
                        jenis : 'pdf_driver',
                        status :  select2Status.find(':selected').val(),
                        tgl_awal : $('#tgl_awal').val(),
                        tgl_akhir : $('#tgl_akhir').val()
                    });

                    let url = "{{ route('backend.mutasikasbon.pdf') }}?" +params.toString();
                    window.open(url, '_blank');
        });

    let select2Status = $('#select2Status');
    select2Status.select2({
        dropdownParent:  select2Status.parent(),
        searchInputPlaceholder: 'Cari Status',
        width: '100%',
        placeholder: 'Pilih Status',

      }).on('select2:select', function (e) {
            let data = e.params.data;
      });

      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);
      let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.mutasikasbon.datatablecekdriver') }}",
          data: function (d) {
            d.status =  select2Status.find(':selected').val();
            d.tgl_awal = $('#tgl_awal').val();
            d.tgl_akhir = $('#tgl_akhir').val();
          }
        },

        columns: [
          {
                data: "id", name:'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
          },
          {data: 'tgl_aktif', name: 'tgl_aktif'},
          {data: 'name', name: 'name'},
          {data: 'kasbon', name: 'kasbon'},
          {data: 'status_aktif', name: 'status_aktif'},
          {data: 'action', name: 'action',  className: 'text-center', orderable: false, searchable: false},
        ],
        columnDefs: [
         {
            className: 'text-end',
            targets: [3],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          },

          {
            className: 'dt-center',
            targets: [4],
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'Tidak Aktif', 'class': ' bg-danger'},
                1: {'title': 'Aktif', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            },
          },

        ],
      });
      $("#terapkan_filter").click(function() {
        dataTable.draw();
      });

      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.driver.index") }}/' + id);
      });
      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
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
