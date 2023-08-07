@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <input id="role_id" type="hidden" value="{{Auth::user()->roles()->first()->level }}">
            <div class="col-md-12">
                <form id="formStore" action="{{ route('backend.joborder.store') }}" autocomplete="off">
                    @csrf
                    <div class="card-header">
                        <div>
                            <h6 class="main-content-label mb-1">{{ $config['page_title'] ?? '' }}</h6>
                        </div><br>
                        <div id="errorCreate" class="mb-3" style="display:none;">
                            <div class="alert alert-danger" role="alert">
                              <div class="alert-text">
                              </div>
                            </div>
                          </div>
                    </div>
                    <div class="card-body" style="border: 1px solid #fff; padding:20px;">
                        <div class="row" >
                            <div class="col-4">
                                 <div class="mb-3">
                                    <label>Tanggal<span class="text-danger">*</span></label>
                                    <input readonly type="text" id="tgl_joborder" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="tgl_joborder"  class="form-control" placeholder="Masukan Tanggal Joborder"/>
                                  </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                   <label>Driver<span class="text-danger">*</span></label>
                                   <select id="select2Driver" style="width: 100% !important;" name="driver_id">
                                   </select>
                                 </div>
                           </div>
                           <div class="col-4">
                            <div class="mb-3">
                               <label>Jenis Mobil<span class="text-danger">*</span></label>
                               <select id="select2Jenis" style="width: 100% !important;" name="jenismobil_id">
                               </select>
                             </div>
                            </div>
                        </div>
                        <div class="row" >

                            <div class="col-6">
                                <div class="mb-3">
                                   <label>Nomor Plat Polisi<span class="text-danger">*</span></label>
                                   <select id="select2Mobil" style="width: 100% !important;" name="mobil_id">
                                   </select>
                                 </div>
                           </div>

                            <div class="col-6">
                                    <div class="mb-3">
                                    <label>Customer<span class="text-danger">*</span></label>
                                    <select id="select2Customer" style="width: 100% !important;" name="customer_id">
                                    </select>
                                    </div>
                            </div>


                        </div>
                        {{-- <div class="row" >

                        </div> --}}


                    </div>


                    <div id="rute_muatan" class="card-body" style="border: 1px solid #fff; padding:20px;" type="hidden">
                        <div class="row" >
                            <div class="col-12" >
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Muatan<span class="text-danger">*</span></label>
                                            <select id="select2Muatan" style="width: 100% !important;" name="muatan_id">
                                            </select>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Alamat Awal (Dari)<span class="text-danger">*</span></label>
                                            <select id="select2Firstrute" style="width: 100% !important;" name="first_rute_id">
                                            </select>
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Alamat Akhir (Ke)<span class="text-danger">*</span></label>
                                            <select id="select2Lastrute" style="width: 100% !important;" name="last_rute_id">
                                            </select>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Cek Rute Tersedia<span class="text-danger">*</span></label>
                                            <input type="hidden" id="kode_rute" name="kode_rute">
                                            <select id="select2Rute" style="width: 100% !important;" name="rute_id">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div id="number_jo" class="card-body" style="border: 1px solid #fff; padding:20px;">
                        <div class="row" >
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label>Uang Jalan<span class="text-danger">*</span></label>
                                <input type="text" id="uang_jalan" name="uang_jalan"  class="form-control" placeholder="Masukan Uang Jalan" readonly/>
                              </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Tambahan/Potongan UJ<span class="text-danger">*</span></label>
                                    <select id="tambahan_potongan" style="width: 100% !important;" name="tambahan_potongan">
                                        <option value="None">Tidak Ada</option>
                                        <option value="Tambahan">Tambahan</option>
                                        <option value="Potongan">Potongan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Biaya Tambahan/Potongan UJ<span class="text-danger">*</span></label>
                                        <input required type="text" id="biaya_lain" name="biaya_lain"  class="form-control" placeholder="Biaya Tambahan/Potongan UJ" disabled/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Total Uang Jalan<span class="text-danger">*</span></label>
                                        <input type="text" id="total_uang_jalan" name="total_uang_jalan"  class="form-control" placeholder="Total Uang Jalan" readonly/>
                                      </div>
                                </div>
                              </div>

                            <div class="col-md-12">
                                  <div class="mb-3">
                                    <label>Keterangan/Catatan Joborder<span class="text-danger"></span></label>
                                    <textarea type="text" id="keterangan_joborder" name="keterangan_joborder"  class="form-control" placeholder="Keterangan Joborder"></textarea>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div id="btn_simpan" class="card-footer">
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




@endsection

@section('css')
<style>
input:disabled {
  background: #ccc !important;
}
</style>
@endsection
@section('script')
<script>

$('#rute_muatan').hide();
$('#number_jo').hide();
$('#btn_simpan').hide();
// $('#id').show();
var cek_potongan_tambahan, fix_total_uang_jalan;
$(document).ready(function () {
    const currenciesOptions = {
            caretPositionOnFocus: "start",
            currencySymbol: "Rp. ",
            unformatOnSubmit: true,
            allowDecimalPadding: true,
            decimalCharacter : ',',
            digitGroupSeparator : '.',
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

    const 	uang_jalan 		    = new AutoNumeric('#uang_jalan',currenciesOptions),
            biaya_lain          = new AutoNumeric('#biaya_lain',currenciesOptions),
            total_uang_jalan 	= new AutoNumeric('#total_uang_jalan',currenciesOptions);




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

    let role_id =  $('#role_id').val();
    // console.log(role_id);
        if(role_id == 1){
            $('#tgl_joborder').flatpickr({
                dateFormat: "Y-m-d"
            });
        }



    let select2Firstrute = $('#select2Firstrute');
    let select2Lastrute = $('#select2Lastrute');
    let select2Muatan = $('#select2Muatan');
    let select2Jenis = $('#select2Jenis');
    let select2Customer = $('#select2Customer');
    let select2TambahanPotongan = $('#tambahan_potongan');
    let select2Driver = $('#select2Driver');
    let select2Mobil = $('#select2Mobil');
    let select2Rute = $('#select2Rute');



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
              validasi: 1,
              status_aktif: 1,
            //   status_jalan: 1,
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
            select2Mobil.empty().trigger('change');
    });



    select2Mobil.select2({
        dropdownParent:   select2Mobil.parent(),
        searchInputPlaceholder: 'Cari Mobil',
        width: '100%',
        placeholder: 'Pilih Mobil',
        ajax: {
          url: "{{ route('backend.mobil.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
            //   status_jalan: 1,
              jenismobil_id:  select2Jenis.find(":selected").val() || '00',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            select2Customer.empty().trigger('change');
    });


    select2Customer.select2({
        dropdownParent: select2Customer.parent(),
        searchInputPlaceholder: 'Cari Customer',
        width: '100%',
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
            select2Muatan.empty().trigger('change');
            // $('#rute_muatan').hide();zZ
    });
    select2Customer.on('change', function(){
        var value = $(this).val();
        if(value != '' && value != null){
            $('#rute_muatan').show();
        }else{
            $('#rute_muatan').hide();
        }
    });


    select2Muatan.select2({
        dropdownParent: select2Muatan.parent(),
        searchInputPlaceholder: 'Cari Muatan',
        width: '100%',
        placeholder: 'Pilih Muatan',
        ajax: {
          url: "{{ route('backend.muatan.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              jenismobil_id:  select2Jenis.find(":selected").val() || '00',
              customer_id:  select2Customer.find(":selected").val() || '00',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            select2Firstrute.empty().trigger('change');

    });

    select2Firstrute.select2({
        dropdownParent: select2Firstrute.parent(),
        searchInputPlaceholder: 'Cari Alamat Rute',
        width: '100%',
        placeholder: 'Pilih Alamat Awal (Dari)',
        ajax: {
          url: "{{ route('backend.alamatrute.select2first') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              jenismobil_id:  select2Jenis.find(":selected").val() || '00',
              customer_id:  select2Customer.find(":selected").val() || '00',
              muatan_id:  select2Muatan.find(":selected").val() || '00',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            select2Lastrute.empty().trigger('change');
    });

    select2Lastrute.select2({
        dropdownParent: select2Lastrute.parent(),
        searchInputPlaceholder: 'Cari Alamat Rute',
        width: '100%',
        placeholder: 'Pilih Alamat Akhir (Ke)',
        ajax: {
          url: "{{ route('backend.alamatrute.select2last') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              jenismobil_id:  select2Jenis.find(":selected").val() || '00',
              customer_id:  select2Customer.find(":selected").val() || '00',
              muatan_id:  select2Muatan.find(":selected").val() || '00',
              first_rute_id:  select2Firstrute.find(":selected").val() || '00',
              last_rute_id:  select2Lastrute.find(":selected").val() || '00',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            select2Rute.empty().trigger('change');
    });

    select2Rute.select2({
        dropdownParent: select2Rute.parent(),
        searchInputPlaceholder: 'Cari Rute',
        width: '100%',
        placeholder: 'Pilih Rute',
        ajax: {
          url: "{{ route('backend.rute.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              jenismobil_id:  select2Jenis.find(":selected").val() || '00',
              customer_id:  select2Customer.find(":selected").val() || '00',
              muatan_id:  select2Muatan.find(":selected").val() || '00',
              first_rute_id:  select2Firstrute.find(":selected").val() || '00',
              last_rute_id:  select2Lastrute.find(":selected").val() || '00',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            $('#kode_rute').val(data.kode_rute);
            uang_jalan.set(data.uang_jalan);
            total_uang_jalan.set(data.uang_jalan);
     });

     select2Rute.on('change', function(){
        var value = $(this).val();
        if(value != '' && value != null){
            $('#number_jo').show();
            $('#btn_simpan').show();
        }else{
            $('#number_jo').hide();
            $('#btn_simpan').hide();
        }
    });

    select2TambahanPotongan.select2({
        dropdownParent:  select2TambahanPotongan.parent(),
        searchInputPlaceholder: 'Cari',
        width: '100%',
        placeholder: 'Pilih Tambahan Atau Potongan'
      }).on('select2:select', function (e) {
       // var value_potongan_tambahan = $(this).val();
        // cek_potongan_tambahan = value_potongan_tambahan;
      });



    select2TambahanPotongan.on('change', function(){
        var value = $(this).val();
        cek_potongan_tambahan = value;
        const cek_tambahan_biaya = (value != 'None') ? false : true;
        $("#biaya_lain").prop( "disabled", cek_tambahan_biaya );
        if(value === 'None'){
            let get_biaya_lain  = biaya_lain.getNumber();
            biaya_lain.set(0);
        }
        formula_total_uang_jalan(cek_potongan_tambahan);
    });

    $('#biaya_lain').on('keyup', function(){
        formula_total_uang_jalan(cek_potongan_tambahan);
    });

    function formula_total_uang_jalan(cek_potongan_tambahan){
        let 	get_uang_jalan 		    = uang_jalan.getNumber(),
                get_biaya_lain          = biaya_lain.getNumber(),
                get_total_uang_jalan 	= total_uang_jalan.getNumber();

                switch (cek_potongan_tambahan) {
                    case 'Tambahan':
                           fix_total_uang_jalan =  get_uang_jalan + get_biaya_lain;
                        break;
                    case 'Potongan':
                          if(get_biaya_lain >  get_uang_jalan){
                            toastr.error('Potongan Biaya Melebihi Total Uang Jalan', 'Gagal !');
                            biaya_lain.set(get_uang_jalan);
                            fix_total_uang_jalan =  0;
                          }else{
                            fix_total_uang_jalan =  get_uang_jalan - get_biaya_lain;
                          }
                        break;
                    case 'None':
                          fix_total_uang_jalan =  get_uang_jalan;
                }

        console.log(get_uang_jalan);
        console.log(get_biaya_lain);
        total_uang_jalan.set(fix_total_uang_jalan);
    }




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
