@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <input id="role_id" type="hidden" value="{{Auth::user()->roles()->first()->level }}">
                <form id="formStore" action="{{ route('backend.kasbon.store') }}">
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
                            <div class="col-md-6">
                                 <div class="mb-3">
                                    <label>Tanggal Transaksi<span class="text-danger">*</span></label>
                                    <input type="text" id="tgl_kasbon" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="tgl_kasbon"  class="form-control" placeholder="Masukan Tanggal Transaksi Kasbon"/>
                                  </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                   <label>Supir<span class="text-danger">*</span></label>
                                    <select id="select2Driver" style="width: 100% !important;" name="driver_id">
                                    </select>
                                 </div>
                           </div>
                        </div>

                        <div class="row" >
                            <div class="col-md-4">
                                 <div class="mb-3">
                                    <label>Bon Hutang Saat Ini<span class="text-danger">*</span></label>
                                    <input type="text" id="kasbon" value="" name="kasbon"  class="form-control" placeholder="Kasbon Tersedia"  readonly/>
                                  </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                   <label>Jenis Transaksi<span class="text-danger">*</span></label>
                                      <select id="select2Jenis" style="width: 100% !important;" name="jenis">
                                        <option value="Pengajuan">Pengajuan</option>
                                        <option value="Pembayaran">Pembayaran</option>
                                      </select>
                                 </div>
                           </div>
                           <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Nominal<span class="text-danger">*</span></label>
                                    <input type="text" id="nominal" value="" name="nominal"  class="form-control" placeholder="Masukan Nominal"  autocomplete="off"/>
                                </div>
                           </div>
                        </div>

                        <div class="row" >
                            <div class="col-md-12">
                                <div class="mb-3">
                                   <label>Keterangan<span class="text-danger"></span></label>
                                   <textarea type="text" id="keterangan" value="" name="keterangan" placeholder="Masukan Keterangan"  class="form-control" autocomplete="off"></textarea>
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

textarea:disabled {
  background: #ccc !important;

}

input[type="text"][disabled] {
   color: rgb(12, 11, 11) !important;
}
</style>
@endsection
@section('script')
<script>



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
            minimumValue: 0
    };

    const 	kasbon 		    = new AutoNumeric('#kasbon',currenciesOptions),
            nominal 	= new AutoNumeric('#nominal',currenciesOptions);

    let select2Driver = $('#select2Driver');
    let select2Jenis = $('#select2Jenis');

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
            kasbon.set(data.kasbon);
            console.log(data.id);
    });

    select2Jenis.select2({
        dropdownParent:  select2Jenis.parent(),
        searchInputPlaceholder: 'Cari Jenis',
        width: '100%',
        placeholder: 'Pilih Jenis Transaksi',

      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
    });

    let role_id =  $('#role_id').val();
    // console.log(role_id);
        if(role_id == 1){
            $('#tgl_kasbon').flatpickr({
                dateFormat: "Y-m-d",
                allowInput: true
            });
        }




    $('#nominal').on('keyup', function(){
        formula_kasbon();
    });

    function formula_kasbon(){
        let 	get_kasbon 		    = kasbon.getNumber(),
                get_nominal     	= nominal.getNumber();

        let jenis = select2Jenis.val();

        if(jenis == 'Pembayaran'){
            if(get_nominal > get_kasbon){
                toastr.error('Nominal Pembayaran Melebihi Kasbon Tersedia', 'Gagal !');
                nominal.set(0);
            }
        }
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
