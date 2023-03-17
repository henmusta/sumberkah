@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">



        <div class="card">
            <div class="card-header mb-3">
                <h5 class="card-title mb-3">Table {{ $config['page_title'] }}</h5>
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        {{-- <h5 class="card-title mb-3">Transaction</h5> --}}
                    </div>
                    <div class="flex-shrink-0">
                        @if(Auth::user()->can('backend-rute-create') == 'true')
                        <a class="btn btn-primary " href="{{ route('backend.rute.create') }}">
                            Tambah
                            <i class="fas fa-plus"></i>
                        </a>
                        @endif
                    </div>
                </div>

            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-bs-toggle="tab" href="#home-1" role="tab" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span class="d-none d-sm-block">List Rute Show</span>
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#profile-1" role="tab" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                            <span class="d-none d-sm-block">List Rute Hide</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="home-1" role="tabpanel">
                        <p class="mb-0">
                            <div class="table-responsive">
                                <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="12%">Kode Rute</th>
                                            <th>Customer</th>
                                            <th>Alamat Awal (Dari)</th>
                                            <th>Alamat Akhir (Ke)</th>
                                            <th>Muatan</th>
                                            <th>Jenis Mobil</th>
                                            <th>Uang Jalan</th>
                                            <th>Validasi</th>
                                            <th>Validasi Delete</th>
                                            <th width="8%">Aksi</th>
                                          </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </p>
                    </div>
                    <div class="tab-pane" id="profile-1" role="tabpanel">
                        <p class="mb-0">
                            <div class="table-responsive">
                                <table id="DatatableTemp" class="table table-bordered border-bottom w-100" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="12%">Kode Rute</th>
                                            <th>Customer</th>
                                            <th>Alamat Awal (Dari)</th>
                                            <th>Alamat Akhir (Ke)</th>
                                            <th>Muatan</th>
                                            <th>Jenis Mobil</th>
                                            <th>Uang Jalan</th>
                                            <th>Validasi</th>
                                            <th>Validasi Delete</th>
                                            {{-- <th>Jumlah Jo</th> --}}
                                            <th width="8%">Aksi</th>
                                          </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </p>
                    </div>
                </div>

            </div>


            {{-- <div class="card-body">



            </div> --}}
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




  <div class="modal fade" id="modalValidasi" tabindex="-1" aria-labelledby="modalmodalValidasi" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit {{ $config['page_title'] }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formUpdateValidasi" action="#">
          @method('PUT')
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <div class="modal-body">
            <div id="errorEdit" class="mb-3" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label>Validasi<span class="text-danger">*</span></label>
              <input type="hidden" name="id">
              <select class="form-select" id="select2Validasi" name="validasi">
                <option value="1">Aktif</option>
                <option value="0">NonAktif</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalValidasiDelete" tabindex="-1" aria-labelledby="modalmodalValidasiDelete" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit {{ $config['page_title'] }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formUpdateValidasiDelete" action="#">
          @method('PUT')
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <div class="modal-body">
            <div id="errorEdit" class="mb-3" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label>Validasi<span class="text-danger">*</span></label>
              <input type="hidden" name="id">
              <select class="form-select" id="select2ValidasiDelete" name="validasi_delete">
                <option value="1">Show</option>
                <option value="0">Hide</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
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
      let select2Validasi = $('#select2Validasi');
      let select2ValidasiDelete = $('#select2ValidasiDelete');
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);
      let modalValidasi = document.getElementById('modalValidasi');
      const bsValidasi = new bootstrap.Modal(modalValidasi);
      let modalValidasiDelete = document.getElementById('modalValidasiDelete');
      const bsValidasiDelete = new bootstrap.Modal(modalValidasiDelete);
      let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.rute.index') }}",
          data: function (d) {
            d.val_del = '1';
          }
        },

        columns: [
          {
                data: "id", name:'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
          },
          {data: 'kode_rute', name: 'kode_rute'},
          {data: 'customer.name', name: 'customer.name'},
          {data: 'ruteawal.name', name: 'ruteawal.name'},
          {data: 'ruteakhir.name', name: 'ruteakhir.name'},
          {data: 'muatan.name', name: 'muatan.name'},
          {data: 'jenismobil.name', name: 'jenismobil.name'},
          {data: 'uang_jalan', name: 'uang_jalan'},
          {data: 'validasi', name: 'validasi'},
          {data: 'validasi_delete', name: 'validasi_delete'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
            {
            className: 'dt-center',
            targets: 8,
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'NonAktif', 'class': ' bg-danger'},
                1: {'title': 'Aktif', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            },
          },
          {
            className: 'dt-center',
            targets: 9,
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'Hide', 'class': ' bg-danger'},
                1: {'title': 'Show', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            },
          },
          {
            targets: 7,
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          }

        ],
      });

      let dataTabletemp = $('#DatatableTemp').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.rute.index') }}",
          data: function (d) {
            d.val_del = '0';
          }
        },

        columns: [
          {
                data: "id", name:'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
          },
          {data: 'kode_rute', name: 'kode_rute'},
          {data: 'customer.name', name: 'customer.name'},
          {data: 'ruteawal.name', name: 'ruteawal.name'},
          {data: 'ruteakhir.name', name: 'ruteakhir.name'},
          {data: 'muatan.name', name: 'muatan.name'},
          {data: 'jenismobil.name', name: 'jenismobil.name'},
          {data: 'uang_jalan', name: 'uang_jalan'},
          {data: 'validasi', name: 'validasi'},
          {data: 'validasi_delete', name: 'validasi_delete'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
            {
            className: 'dt-center',
            targets: 8,
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'NonAktif', 'class': ' bg-danger'},
                1: {'title': 'Aktif', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            },
          },
          {
            className: 'dt-center',
            targets: 9,
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'Hide', 'class': ' bg-danger'},
                1: {'title': 'Show', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            },
          },
          {
            targets: 7,
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          }

        ],
      });

    select2Validasi.select2({
        dropdownParent: select2Validasi.parent(),
        searchInputPlaceholder: 'Cari Validasi',
        width: '100%',
        placeholder: 'Pilih Validasi',
    });

    select2ValidasiDelete.select2({
        dropdownParent: select2ValidasiDelete.parent(),
        searchInputPlaceholder: 'Cari Validasi',
        width: '100%',
        placeholder: 'Pilih Validasi',
    });


      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.rute.index") }}/' + id);
      });
      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });


      modalValidasi.addEventListener('show.bs.modal', function (event) {
        let id = event.relatedTarget.getAttribute('data-bs-id');
        let validasi = event.relatedTarget.getAttribute('data-bs-validasi');
        $(this).find('#select2Validasi').val(validasi).trigger('change');
        this.querySelector('input[name=id]').value = id;
        this.querySelector('#formUpdateValidasi').setAttribute('action', '{{ route("backend.rute.validasi") }}');
      });
      modalValidasi.addEventListener('hidden.bs.modal', function (event) {
        $(this).find('#select2Validasi').val('').trigger('change');
        this.querySelector('input[name=id]').value = '';
        this.querySelector('#formUpdateValidasi').setAttribute('href', '');
      });


      modalValidasiDelete.addEventListener('show.bs.modal', function (event) {
        let id = event.relatedTarget.getAttribute('data-bs-id');
        let validasi = event.relatedTarget.getAttribute('data-bs-validasi-delete');
        $(this).find('#select2ValidasiDelete').val(validasi).trigger('change');
        this.querySelector('input[name=id]').value = id;
        this.querySelector('#formUpdateValidasiDelete').setAttribute('action', '{{ route("backend.rute.validasidelete") }}');
      });
      modalValidasiDelete.addEventListener('hidden.bs.modal', function (event) {
        $(this).find('#select2ValidasiDelete').val('').trigger('change');
        this.querySelector('input[name=id]').value = '';
        this.querySelector('#formUpdateValidasiDelete').setAttribute('href', '');
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
            dataTabletemp.draw();
            bsDelete.hide();
          },
          error: function (response) {
            toastr.error(response.responseJSON.message, 'Failed !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            bsDelete.hide();
          }
        });
      });

      $("#formUpdateValidasi").submit(function(e){
        e.preventDefault();
        let form 	= $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url 	= form.attr("action");
        let data 	= new FormData(this);
        $.ajax({
          beforeSend:function() {
            btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url : url,
          data : data,
          success: function (response) {
            let errorEdit = $('#errorEdit');
            errorEdit.css('display', 'none');
            errorEdit.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              dataTable.draw();
              dataTabletemp.draw();
              bsValidasi.hide();
            } else {
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
              if (response.error !== undefined) {
                errorEdit.removeAttr('style');
                $.each(response.error, function (key, value) {
                  errorEdit.find('.alert-text').append('<span style="display: block">' + value + '</span>');
                });
              }
            }
          },
          error: function (response) {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            toastr.error(response.responseJSON.message, 'Failed !');
          }
        });
      });


      $("#formUpdateValidasiDelete").submit(function(e){
        e.preventDefault();
        let form 	= $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url 	= form.attr("action");
        let data 	= new FormData(this);
        $.ajax({
          beforeSend:function() {
            btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url : url,
          data : data,
          success: function (response) {
            let errorEdit = $('#errorEdit');
            errorEdit.css('display', 'none');
            errorEdit.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              dataTable.draw();
              dataTabletemp.draw();
              bsValidasiDelete.hide();
            } else {
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
              if (response.error !== undefined) {
                errorEdit.removeAttr('style');
                $.each(response.error, function (key, value) {
                  errorEdit.find('.alert-text').append('<span style="display: block">' + value + '</span>');
                });
              }
            }
          },
          error: function (response) {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            toastr.error(response.responseJSON.message, 'Failed !');
          }
        });
      });

    });
  </script>
@endsection
