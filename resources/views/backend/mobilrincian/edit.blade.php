@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <form id="formUpdate" action="{{ route('backend.mobilrincian.update', Request::segment(3)) }}">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    @method('PUT')
                    <div class="card-body">
                      <div>
                        <h6 class="main-content-label mb-1">{{ $config['page_title'] ?? '' }}</h6>
                      </div><br>
                      <div id="errorEdit" class="mb-3" style="display:none;">
                        <div class="alert alert-danger" role="alert">
                          <div class="alert-text">
                          </div>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="select2Merk">Merek<span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <select id="select2Merk" style="width: 100% !important;" name="merkmobil_id">
                                            <option value="{{ $data['mobil']['merkmobil']['id'] }}"> {{$data['mobil']['merkmobil']['name'] }}</option>
                                        </select>
                                        <a class="btn btn-primary hstack gap-2 align-self-center" data-bs-toggle="modal"
                                        data-bs-target="#modalCreateMerek">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="select2Tipe">Tipe<span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <select id="select2Tipe" style="width: 100% !important;" name="tipemobil_id">
                                            <option value="{{ $data['mobil']['tipemobil']['id'] }}"> {{$data['mobil']['tipemobil']['name'] }}</option>
                                        </select>
                                        <a class="btn btn-primary hstack gap-2 align-self-center" data-bs-toggle="modal"
                                        data-bs-target="#modalCreateTipe">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="select2Jenis">Jenis<span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <select id="select2Jenis" style="width: 100% !important;" name="jenismobil_id">
                                            <option value="{{ $data['mobil']['jenismobil']['id'] }}"> {{$data['mobil']['jenismobil']['name'] }}</option>
                                        </select>
                                        <a class="btn btn-primary hstack gap-2 align-self-center" data-bs-toggle="modal"
                                        data-bs-target="#modalCreateJenis">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="activeSelect">Dump <span class="text-danger">*</span></label>
                                    <select class="form-select" id="select2Dump" name="dump">
                                      <option value=""></option>
                                      <option value="Iya"  {{ $data['mobil']['dump'] == 'Iya' ? 'selected' : NULL }}>Iya</option>
                                      <option value="Tidak"  {{ $data['mobil']['dump'] == 'Tidak' ? 'selected' : NULL }}>Tidak</option>
                                    </select>
                                  </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="window.history.back();">
                          Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </div>
                  </form>
            </div>
        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="modalCreateMerek" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalResetLabel">Tambah Merek Mobil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formStoreMerek" method="POST" action="{{ route('backend.merkmobil.store') }}">
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


<div class="modal fade" id="modalCreateJenis" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalResetLabel">Tambah Jenis Mobil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formStoreJenis" method="POST" action="{{ route('backend.jenismobil.store') }}">
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


<div class="modal fade" id="modalCreateTipe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalResetLabel">Tambah Tipe Mobil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formStoreTipe" method="POST" action="{{ route('backend.tipemobil.store') }}">
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
@endsection

@section('css')
@endsection
@section('script')

<script>
$(document).ready(function () {


    //Merek
    let modalCreateMerek = document.getElementById('modalCreateMerek');
    const bsCreateMerek = new bootstrap.Modal( modalCreateMerek);

      modalCreateMerek.addEventListener('show.bs.modal', function (event) {
      });
      modalCreateMerek.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('input[name=keterangan]').value = '';
      });

      $("#formStoreMerek").submit(function (e) {
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
              bsCreateMerek.hide();
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


    //Tipe
    let modalCreateTipe = document.getElementById('modalCreateTipe');
    const bsCreateTipe = new bootstrap.Modal( modalCreateTipe);

      modalCreateTipe.addEventListener('show.bs.modal', function (event) {
      });
      modalCreateTipe.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('input[name=keterangan]').value = '';
      });


      $("#formStoreTipe").submit(function (e) {
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
              bsCreateTipe.hide();
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



    //Jenis
    let modalCreateJenis = document.getElementById('modalCreateJenis');
    const bsCreateJenis = new bootstrap.Modal( modalCreateJenis);

      modalCreateJenis.addEventListener('show.bs.modal', function (event) {
      });
      modalCreateJenis.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('input[name=keterangan]').value = '';
      });



      $("#formStoreJenis").submit(function (e) {
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
              bsCreateJenis.hide();
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



    let select2Merk = $('#select2Merk');
    let select2Tipe = $('#select2Tipe');
    let select2Jenis = $('#select2Jenis');
    let select2Dump = $('#select2Dump');
    select2Dump.select2({
        dropdownParent: select2Dump.parent(),
        searchInputPlaceholder: 'Cari Dump',
        width: '100%',
        placeholder: 'select Dump',
    });


    select2Merk.select2({
        dropdownParent: select2Merk.parent(),
        searchInputPlaceholder: 'Cari Merek Mobil',
        width: '100%',
        placeholder: 'select Merek Mobil',
        ajax: {
          url: "{{ route('backend.merkmobil.select2') }}",
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
            console.log(data.id);
      });

      select2Tipe.select2({
        dropdownParent: select2Tipe.parent(),
        searchInputPlaceholder: 'Cari Tipe Mobil',
        width: '100%',
        placeholder: 'select Tipe Mobil',
        ajax: {
          url: "{{ route('backend.tipemobil.select2') }}",
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
            console.log(data.id);
      });



      select2Jenis.select2({
        dropdownParent: select2Jenis.parent(),
        searchInputPlaceholder: 'Cari Jenis Mobil',
        width: '100%',
        placeholder: 'select Jenis Mobil',
        ajax: {
          url: "{{ route('backend.jenismobil.select2') }}",
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
            console.log(data.id);
      });

      $("#formUpdate").submit(function (e) {
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
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          success: function (response) {
            let errorEdit = $('#errorEdit');
            errorEdit.css('display', 'none');
            errorEdit.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              setTimeout(function () {
                if (!response.redirect || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
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
