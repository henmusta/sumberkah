@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <form id="formStore" action="{{ route('backend.rute.store') }}" autocomplete="off">
                    @csrf
                    <div class="card-body">
                      <div>
                        <h6 class="main-content-label mb-1">{{ $config['page_title'] ?? '' }}</h6>
                      </div><br>
                      <div id="errorCreate" class="mb-3" style="display:none;">
                        <div class="alert alert-danger" role="alert">
                          <div class="alert-text">
                          </div>
                        </div>
                      </div>

                        <div class="row" >
                            <div class="col-md-6" style="border: 1px solid #fff; padding:20px;">
                                 <div class="mb-3">
                                    <label>Customer<span class="text-danger">*</span></label>
                                    <select id="select2Customer" style="width: 100% !important;" name="customer_id">
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label>Alamat Awal (Dari)<span class="text-danger">*</span></label>
                                        <div class="d-flex">
                                            <select id="select2Firstrute" style="width: 100% !important;" name="first_rute_id">
                                            </select>
                                            <a class="btn btn-primary hstack gap-2 align-self-center" data-bs-toggle="modal"
                                            data-bs-target="#modalCreateRute">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                  </div>

                                  <div class="mb-3">
                                    <label>Alamat Akhir (Ke)<span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <select id="select2Lastrute" style="width: 100% !important;" name="last_rute_id">
                                        </select>
                                        <a class="btn btn-primary hstack gap-2 align-self-center" data-bs-toggle="modal"
                                        data-bs-target="#modalCreateRute">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                  </div>

                                  <div class="mb-3">
                                    <label>Muatan<span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <select id="select2Muatan" style="width: 100% !important;" name="muatan_id">
                                        </select>
                                        <a class="btn btn-primary hstack gap-2 align-self-center" data-bs-toggle="modal"
                                        data-bs-target="#modalCreateMuatan">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                  </div>

                                  <div class="mb-3">
                                    <label>Jenis Mobil<span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <select id="select2Jenis" style="width: 100% !important;" name="jenismobil_id">
                                        </select>
                                        <a class="btn btn-primary hstack gap-2 align-self-center" data-bs-toggle="modal"
                                        data-bs-target="#modalCreateJenis">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-md-6" style="border: 1px solid #fff; padding:20px;">
                                <div class="mb-3">
                                    <label>Uang Jalan<span class="text-danger">*</span></label>
                                    <input type="text" id="uang_jalan" name="uang_jalan"  class="form-control" placeholder="Masukan Uang Jalan"/>
                                </div>
                                <div class="mb-3">
                                    <label for="activeSelect">Ritase/Tonase <span class="text-danger">*</span></label>
                                    <select class="form-select" id="select2RitaseTonase" name="ritase_tonase">
                                      <option value=""></option>
                                      <option value="Ritase">Ritase</option>
                                      <option value="Tonase">Tonase</option>
                                      <option value="Kilogram">Kilogram</option>
                                    </select>
                                  </div>
                                <div class="mb-3">
                                    <label>Harga/Satuan<span class="text-danger">*</span></label>
                                    <input type="text" id="harga" name="harga"  class="form-control" placeholder="Masukan Harga"/>
                                </div>
                                <div class="mb-3">
                                    <label>Gaji<span class="text-danger">*</span></label>
                                    <input type="text" id="gaji" name="gaji"  class="form-control" placeholder="Masukan Gaji"/>
                                </div>
                                <div class="mb-3">
                                    <label>Keterangan<span class="text-danger"></span></label>
                                    <textarea type="text" id="keterangan" name="keterangan"  class="form-control" placeholder="Masukan Keterangan"></textarea>
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
  {{-- modal --}}
  <div class="modal fade" id="modalCreateRute" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalResetLabel">Tambah Alamat Rute</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formStoreAlamatrute" method="POST" action="{{ route('backend.alamatrute.store') }}" autocomplete="off">
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

  <div class="modal fade" id="modalCreateMuatan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalResetLabel">Tambah Muatan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formStoreMuatan" method="POST" action="{{ route('backend.muatan.store') }}" autocomplete="off">
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
          <h5 class="modal-title" id="modalResetLabel">Tambah Jenis Mobl</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formStoreJenis" method="POST" action="{{ route('backend.jenismobil.store') }}" autocomplete="off">
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
    const currenciesOptions = {
            caretPositionOnFocus: "start",
            currencySymbol: "Rp. ",
            unformatOnSubmit: true,
            decimalCharacter : ',',
            digitGroupSeparator : '.',
            allowDecimalPadding: true,
            decimalPlaces: 0,
            modifyValueOnWheel: false,
            // minimumValue: 0
    };

    const currenciesOptionsDecimal = {
            caretPositionOnFocus: "start",
            currencySymbol: "Rp. ",
            unformatOnSubmit: true,
            allowDecimalPadding: true,
            decimalCharacter : ',',
            digitGroupSeparator : '.',
            decimalPlaces: 3,
            modifyValueOnWheel: false,
            // minimumValue: 0
    };

    const 	uang_jalan 		= new AutoNumeric('#uang_jalan',currenciesOptions),
          //  harga           = new AutoNumeric('#harga',currenciesOptions),
            gaji 		    = new AutoNumeric('#gaji',currenciesOptions);

    const   harga           = new AutoNumeric('#harga',currenciesOptionsDecimal);



       $('#tahun').flatpickr({
            disableMobile: "true",
            plugins: [
                new monthSelectPlugin({
                shorthand: true,
                dateFormat: "Y",
                // altFormat: "Y",
                theme: "dark"
                })
            ]
         });

         $('#berlaku_stnk, #berlaku_ijin_usaha, #berlaku_pajak, #berlaku_ijin_bongkar, #berlaku_kir').flatpickr({
            dateFormat: "Y-m-d"
         });
    let modalCreateRute = document.getElementById('modalCreateRute');
    const bsCreateRute = new bootstrap.Modal( modalCreateRute);

      modalCreateRute.addEventListener('show.bs.modal', function (event) {
      });
      modalCreateRute.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('input[name=keterangan]').value = '';
      });

    let modalCreateJenis = document.getElementById('modalCreateJenis');
    const bsCreateJenis = new bootstrap.Modal(modalCreateJenis);
      modalCreateJenis.addEventListener('show.bs.modal', function (event) {
      });
      modalCreateJenis.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('input[name=keterangan]').value = '';
      });

    let modalCreateMuatan = document.getElementById('modalCreateMuatan');
    const bsCreateMuatan = new bootstrap.Modal(modalCreateMuatan);

      modalCreateMuatan.addEventListener('show.bs.modal', function (event) {
      });
      modalCreateMuatan.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('input[name=keterangan]').value = '';
      });

    let select2Firstrute = $('#select2Firstrute');
    let select2Lastrute = $('#select2Lastrute');
    let select2Muatan = $('#select2Muatan');
    let select2Jenis = $('#select2Jenis');
    let select2Customer = $('#select2Customer');
    let select2RitaseTonase = $('#select2RitaseTonase');
    select2RitaseTonase.select2({
        dropdownParent: select2RitaseTonase.parent(),
        searchInputPlaceholder: 'Cari Ritase - Tonase',
        allowClear: true,
        width: '100%',
        placeholder: 'Pilih Ritase Tonase',
    });

    select2Customer.select2({
        dropdownParent: select2Customer.parent(),
        searchInputPlaceholder: 'Cari Customer',
        allowClear: true,
        width: '100%',
        placeholder: 'Pilih Customer',
        ajax: {
          url: "{{ route('backend.customer.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              validasi_id: '1',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });


    select2Firstrute.select2({
        dropdownParent: select2Firstrute.parent(),
        searchInputPlaceholder: 'Cari Alamat Rute',
        allowClear: true,
        width: '100%',
        placeholder: 'Pilih Alamat Awal (Dari)',
        ajax: {
          url: "{{ route('backend.alamatrute.select2') }}",
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

      select2Lastrute.select2({
        dropdownParent: select2Lastrute.parent(),
        searchInputPlaceholder: 'Cari Alamat Rute',
        allowClear: true,
        width: '100%',
        placeholder: 'Pilih Alamat Akhir (Ke)',
        ajax: {
          url: "{{ route('backend.alamatrute.select2') }}",
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

      select2Muatan.select2({
        dropdownParent: select2Muatan.parent(),
        searchInputPlaceholder: 'Cari Muatan',
        allowClear: true,
        width: '100%',
        placeholder: 'Pilih Muatan',
        ajax: {
          url: "{{ route('backend.muatan.select2') }}",
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
        allowClear: true,
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
              setTimeout(function () {
                if (response.redirect === "" || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
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

      $("#formStoreAlamatrute").submit(function (e) {
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
              bsCreateRute.hide();
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

      $("#formStoreMuatan").submit(function (e) {
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
              bsCreateMuatan.hide();
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
});

</script>
@endsection
