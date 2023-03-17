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
                        @if(Auth::user()->can('backend-customer-create') == 'true')
                        <button class="btn btn-primary hstack gap-2 align-self-center" data-bs-toggle="modal"
                        data-bs-target="#modalCreate">
                            Tambah
                            <i class="fas fa-plus"></i>
                        </button>
                        @endif
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Kontak</th>
                                <th>Telepon</th>
                                <th>Keterangan</th>
                                <th>Validasi</th>
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

 <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalResetLabel">Tambah</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formStore" method="POST" action="{{ route('backend.customer.store') }}" autocomplete="off">
          @csrf
          <div class="modal-body">
            <div id="errorCreate" class="mb-3" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label>Nama<span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Masukan nama"/>
            </div>
            <div class="mb-3">
                <label>Alamat<span class="text-danger"></span></label>
                <input type="text" name="alamat" class="form-control" placeholder="Masukan Alamat"/>
            </div>
            <div class="mb-3">
                <label>Kontak<span class="text-danger"></span></label>
                <input type="text" name="kontak" class="form-control" placeholder="Masukan Kontak"/>
            </div>
            <div class="mb-3">
                <label>Telepon<span class="text-danger"></span></label>
                <input type="text" name="telp" class="form-control" placeholder="Masukan Telp"/>
            </div>
            <div class="mb-3">
                <label>Keterangan<span class="text-danger"></span></label>
                <input type="text" name="keterangan" class="form-control" placeholder="Masukan Keterangan"/>
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
  <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalmodalEdit" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit {{ $config['page_title'] }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formUpdate" action="#" autocomplete="off">
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
                <label>Nama<span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Masukan nama"/>
              </div>
              <div class="mb-3">
                  <label>Alamat<span class="text-danger"></span></label>
                  <input type="text" name="alamat" class="form-control" placeholder="Masukan Alamat"/>
              </div>
              <div class="mb-3">
                  <label>Kontak<span class="text-danger"></span></label>
                  <input type="text" name="kontak" class="form-control" placeholder="Masukan Kontak"/>
              </div>
              <div class="mb-3">
                  <label>Telepon<span class="text-danger"></span></label>
                  <input type="text" name="telp" class="form-control" placeholder="Masukan Telp"/>
              </div>
              <div class="mb-3">
                  <label>Keterangan<span class="text-danger"></span></label>
                  <input type="text" name="keterangan" class="form-control" placeholder="Masukan Keterangan"/>
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


@endsection

@section('css')
<style>


</style>
@endsection
@section('script')


  <script>

     $(document).ready(function () {

      let modalCreate = document.getElementById('modalCreate');
      const bsCreate = new bootstrap.Modal(modalCreate);
      let modalEdit = document.getElementById('modalEdit');
      const bsEdit = new bootstrap.Modal(modalEdit);
      let select2Validasi = $('#select2Validasi');
      let modalDelete = document.getElementById('modalDelete');
      let modalValidasi = document.getElementById('modalValidasi');
      const bsValidasi = new bootstrap.Modal(modalValidasi);
      const bsDelete = new bootstrap.Modal(modalDelete);
      let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.customer.index') }}",
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
          {data: 'alamat', name: 'alamat'},
          {data: 'kontak', name: 'kontak'},
          {data: 'telp', name: 'telp'},
          {data: 'keterangan_customer', name: 'keterangan_customer'},
          {data: 'validasi', name: 'validasi'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
            {
            className: 'dt-center',
            targets: 6,
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': '  NonActive', 'class': ' bg-danger'},
                1: {'title': 'Active', 'class': ' bg-success'},
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

      modalCreate.addEventListener('show.bs.modal', function (event) {
      });
      modalCreate.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('input[name=alamat]').value = '';
        this.querySelector('input[name=kontak]').value = '';
        this.querySelector('input[name=telp]').value = '';
        this.querySelector('input[name=keterangan]').value = '';
      });
      modalEdit.addEventListener('show.bs.modal', function (event) {
        let name = event.relatedTarget.getAttribute('data-bs-name');
        let alamat = event.relatedTarget.getAttribute('data-bs-alamat');
        let kontak = event.relatedTarget.getAttribute('data-bs-kontak');
        let telp = event.relatedTarget.getAttribute('data-bs-telp');
        let keterangan = event.relatedTarget.getAttribute('data-bs-keterangan');
        this.querySelector('input[name=name]').value = name;
        this.querySelector('input[name=alamat]').value = alamat;
        this.querySelector('input[name=kontak]').value = kontak;
        this.querySelector('input[name=telp]').value = telp;
        this.querySelector('input[name=keterangan]').value = keterangan;
        this.querySelector('#formUpdate').setAttribute('action', '{{ route("backend.customer.index") }}/' + event.relatedTarget.getAttribute('data-bs-id'));
      });
      modalEdit.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('input[name=alamat]').value = '';
        this.querySelector('input[name=kontak]').value = '';
        this.querySelector('input[name=telp]').value = '';
        this.querySelector('input[name=keterangan]').value = '';
        this.querySelector('#formUpdate').setAttribute('href', '');
      });
      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.customer.index") }}/' + id);
      });
      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });

      select2Validasi.select2({
        dropdownParent: select2Validasi.parent(),
        searchInputPlaceholder: 'Cari Validasi',
        width: '100%',
        placeholder: 'Pilih Validasi',
    });


      modalValidasi.addEventListener('show.bs.modal', function (event) {
        let id = event.relatedTarget.getAttribute('data-bs-id');
        let validasi = event.relatedTarget.getAttribute('data-bs-validasi');
        $(this).find('#select2Validasi').val(validasi).trigger('change');
        this.querySelector('input[name=id]').value = id;
        this.querySelector('#formUpdateValidasi').setAttribute('action', '{{ route("backend.customer.validasi") }}');
      });
      modalValidasi.addEventListener('hidden.bs.modal', function (event) {
        $(this).find('#select2Validasi').val('').trigger('change');
        this.querySelector('input[name=id]').value = '';
        this.querySelector('#formUpdateValidasi').setAttribute('href', '');
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


      $("#formStore").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url = form.attr("action");
        let data = new FormData(this);
        $.ajax({
          beforeSend: function () {
            btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          success: function (response) {
            let errorCreate = $('#errorCreate');
            errorCreate.css('display', 'none');
            errorCreate.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              dataTable.draw();
              bsCreate.hide();
            } else {
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
              if (response.error !== undefined) {
                errorCreate.removeAttr('style');
                $.each(response.error, function (key, value) {
                  errorCreate.find('.alert-text').append('<span style="display: block">' + value + '</span>');
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
      $("#formUpdate").submit(function(e){
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
              bsEdit.hide();
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
