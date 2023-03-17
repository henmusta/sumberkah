@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <form id="formStore" action="{{ route('backend.driver.store') }}" autocomplete="off">
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
                      <div class="d-flex flex-column">

                        <div class="row">
                            <div class="col-6">
                                <h6 class="main-content-label mb-1">Data Supir</h6>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Nama Driver<span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name"  class="form-control" placeholder="Masukan Nama Lengkap"/>
                                      </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Nama Panggilan<span class="text-danger"></span></label>
                                        <input type="text" id="panggilan" name="panggilan"  class="form-control" placeholder="Masukan Panggilan"/>
                                      </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Tempat Lahir<span class="text-danger"></span></label>
                                            <input type="text" id="tempat_lahir" name="tempat_lahir"  class="form-control" placeholder="Masukan Tempat Lahir"/>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Tanggal Lahir<span class="text-danger"></span></label>
                                            <input type="text" id="tgl_lahir" name="tgl_lahir"  class="form-control" placeholder="Masukan Taanggal Lahir"/>
                                          </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Alamat<span class="text-danger"></span></label>
                                        <textarea id="alamat" name="alamat"  class="form-control" placeholder="Masukan Alamat"></textarea>
                                      </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Telp/Hp<span class="text-danger"></span></label>
                                        <input type="text" id="telp" name="telp"  class="form-control" placeholder="Masukan Nomor Hp atau Telepon"/>
                                      </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>No Ktp<span class="text-danger"></span></label>
                                        <input type="text" id="ktp" name="ktp"  class="form-control" placeholder="Masukan Nomor Ktp"/>
                                      </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>No Sim<span class="text-danger"></span></label>
                                        <input type="text" id="sim" name="sim"  class="form-control" placeholder="Masukan Nomor Sim"/>
                                      </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Tanggal Berlaku Sim<span class="text-danger">*</span></label>
                                            <input type="text" id="tgl_sim" name="tgl_sim"  class="form-control" placeholder="Masukan Tanggal Berlaku Sim"/>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Tanggal Aktif Supir<span class="text-danger">*</span></label>
                                            <input type="text" id="tgl_aktif" name="tgl_aktif"  class="form-control" placeholder="Masukan Taanggal Aktif"/>
                                          </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Keterangan<span class="text-danger"></span></label>
                                        <textarea id="keterangan" name="keterangan"  class="form-control" placeholder="Masukan Keterangan"></textarea>
                                      </div>
                                </div>

                            </div>
                            <div class="col-6">
                                <h6 class="main-content-label mb-1">Data Keluarga yang Dapat Dihubungi</h6>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Nama Keluarga<span class="text-danger"></span></label>
                                        <input type="text" id="darurat_name" name="darurat_name"  class="form-control" placeholder="Masukan Nama Keluarga"/>
                                      </div>
                                </div>
                                <div class="col-md-12">
                                      <div class="mb-3">
                                        <label>Telp/Hp darurat<span class="text-danger"></span></label>
                                        <input type="text" id="darurat_telp" name="darurat_telp"  class="form-control" placeholder="Masukan Hp/Telepon Darurat"/>
                                      </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                      <label>Referensi<span class="text-danger"></span></label>
                                      <input type="text" id="darurat_ref" name="darurat_ref"  class="form-control" placeholder="Masukan Referensi"/>
                                    </div>
                                </div>

                                <br>
                                <hr>
                                <h6 class="main-content-label mb-1">Lampiran Data Driver</h6>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Upload Foto Driver<span class="text-danger"></span></label>
                                        <div class="text-center">
                                            <img id="avatar" src="{{ asset('assets/backend/images/noimg.png') }}"
                                            style="object-fit: cover; border: 1px solid #d9d9d9"
                                            height="70px"
                                            width="70px" alt="">
                                            <input type="file" id="image_foto" name="image_foto"  class="form-control image" placeholder="Masukan Foto Driver"/>
                                        </div>
                                      </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Upload Foto Sim<span class="text-danger"></span></label>
                                        <div class="text-center">
                                            <img id="avatar" src="{{ asset('assets/backend/images/noimg.png') }}"
                                            style="object-fit: cover; border: 1px solid #d9d9d9"
                                            height="70px"
                                            width="70px" alt="">
                                            <input type="file" id="image_sim" name="image_sim"  class="form-control image" placeholder="Masukan Foto Sim"/>
                                        </div>
                                      </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Upload Foto Ktp<span class="text-danger"></span></label>
                                        <div class="text-center">
                                            <img id="avatar" src="{{ asset('assets/backend/images/noimg.png') }}"
                                            style="object-fit: cover; border: 1px solid #d9d9d9"
                                            height="70px"
                                            width="70px" alt="">
                                            <input type="file" id="image_ktp" name="image_ktp"  class="form-control image" placeholder="Masukan Foto Ktp"/>
                                        </div>
                                      </div>
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
@endsection

@section('css')
@endsection
@section('script')

<script>
$(document).ready(function () {

     $(".image").change(function () {
        let thumb = $(this).parent().find('img');
        if (this.files && this.files[0]) {
          let reader = new FileReader();
          reader.onload = function (e) {
            thumb.attr('src', e.target.result);
          }
          reader.readAsDataURL(this.files[0]);
        }
      });

         $('#tgl_lahir, #tgl_sim, #tgl_aktif').flatpickr({
            dateFormat: "Y-m-d"
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
});

</script>
@endsection
