@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <form id="formStore" action="{{ route('backend.konfirmasijo.store') }}" autocomplete="off">
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6"  style="border: 1px solid #fff; padding:20px;" >
                                <input id="cek_joborder_id" name="cek_joborder_id" type="hidden" value="{{$data['joborder']['id'] ?? ''}}">
                               <input id="cek_kode_joborder" name="cek_kode_joborder" type="hidden" value="{{$data['joborder']['kode_joborder'] ?? ''}}">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                       <label>Id Joborder<span class="text-danger">*</span></label>
                                       <select id="select2Joborder" style="width: 100% !important;" name="joborder_id">

                                       </select>
                                     </div>
                                </div>
                               <div class="col-md-12">
                                   <div class="mb-3">
                                      <label>Tanggal Joborder<span class="text-danger">*</span></label>
                                      <input type="text" id="tgl_joborder" value="" name="tgl_joborder"  class="form-control" disabled/>
                                    </div>
                              </div>
                              <div class="col-md-12">
                               <div class="mb-3">
                                  <label>Driver<span class="text-danger">*</span></label>
                                  <input type="text" id="driver_id" value="" name="driver_id"  class="form-control"  disabled/>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="mb-3">
                                   <label>Nomor Plat Polisi<span class="text-danger">*</span></label>
                                   <input type="text" id="nomor_plat" value="" name="nomor_plat"  class="form-control"  disabled/>
                                 </div>
                              </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Customer<span class="text-danger">*</span></label>
                                        <input type="text" id="customer_id" value="" name="customer_id"  class="form-control"  disabled/>
                                        </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Muatan<span class="text-danger">*</span></label>
                                        <input type="text" id="muatan_id" value="" name="muatan_id"  class="form-control"  disabled/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                       <label>Alamat Dari (Awal)<span class="text-danger">*</span></label>
                                       <input type="text" id="first_rute_id" value="" name="tgl_joborder"  class="form-control" disabled/>
                                     </div>
                               </div>
                               <div class="col-md-12">
                                <div class="mb-3">
                                   <label>Alamat Akhir (Ke)<span class="text-danger">*</span></label>
                                   <input type="text" id="last_rute_id" value="" name="joborder_id"  class="form-control"  disabled/>
                                 </div>
                               </div>
                               <div class="col-md-12">
                                 <div class="mb-3">
                                   <label>Total Uang Jalan<span class="text-danger">*</span></label>
                                   <input type="text" id="total_uang_jalan" value="" name="total_uang_jalan"  class="form-control" disabled/>
                                 </div>
                               </div>
                              {{-- end --}}
                            </div>
                            <div class="col-6"  style="border: 1px solid #fff; padding:20px;" >
                                <div class="col-md-12">
                                    <div class="mb-3">
                                      <label>Harga<span class="text-danger">*</span></label>
                                      <input type="text" id="harga" value="" name="harga"  class="form-control" disabled/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                       <label>Status<span class="text-danger">*</span></label>
                                       <select id="select2Ekspedisi" style="width: 100% !important;" name="status_ekspedisi">
                                            <option value="Sampai Tujuan">Sampai Tujuan</option>
                                       </select>
                                     </div>
                                </div>
                               <div class="col-md-12">
                                   <div class="mb-3">
                                      <label>Tanggal Muat<span class="text-danger">*</span></label>
                                      <input type="text" id="tgl_muat" value="" name="tgl_muat"  class="form-control" utocomplete="off"/>
                                    </div>
                              </div>
                              <div class="col-md-12">
                                <div class="mb-3">
                                  <label>Tanggal Bongkar<span class="text-danger">*</span></label>
                                  <input type="text" id="tgl_bongkar" value="" name="tgl_bongkar"  class="form-control"  autocomplete="off"/>
                                </div>
                              </div>
                              <div class="col-md-12">
                                  <div class="mb-3">
                                    <label>Berat Muatan<span class="text-danger">*</span></label>
                                    <input type="text" id="berat_muatan" value="" name="berat_muatan"  class="form-control"  autocomplete="off"/>
                                  </div>
                              </div>
                              <div class="col-md-12">
                                <div class="mb-3">
                                   <label>Biaya Lain<span class="text-danger">*</span></label>
                                   <input type="text" id="konfirmasi_biaya_lain" value="0" name="konfirmasi_biaya_lain"  class="form-control"  autocomplete="off"/>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                <div class="mb-3">
                                   <label>Total Harga<span class="text-danger">*</span></label>
                                   <input id="total_harga" name="total_harga" style="font-size: 24px; color:black;" class="form-control text-end" readonly>
                                 </div>
                              </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Keterangan<span class="text-danger"></span></label>
                                        <textarea type="text" id="keterangan_konfirmasi" value="" name="keterangan_konfirmasi"  class="form-control"></textarea>
                                    </div>
                                </div>

                              {{-- end --}}
                            </div>

                        </div>

                        {{-- <div class="row" >

                        </div>

                        <div class="row" >


                        </div>


                        <div class="row" >

                        </div> --}}

                    </div>




                    <div  class="card-footer">
                      <div class="d-flex justify-content-end">
                        <button id="btn_simpan" type="button" class="btn btn-secondary me-2" onclick="window.history.back();">
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

    const   biaya_lain = new AutoNumeric('#konfirmasi_biaya_lain',currenciesOptions),
            total_harga = new AutoNumeric('#total_harga',currenciesOptions),
            total_uang_jalan = new AutoNumeric('#total_uang_jalan',currenciesOptions);

    const biaya_harga = new AutoNumeric('#harga',currenciesOptionsDecimal);


    $('#tgl_muat, #tgl_bongkar').flatpickr({
       dateFormat: "Y-m-d",
       allowInput: true
    });

    let select2Joborder = $('#select2Joborder');
    let select2Ekspedisi = $('#select2Ekspedisi');
    let cek_joborder_id = $('#cek_joborder_id').val();
    let cek_kode_joborder = $('#cek_kode_joborder').val();
    select2Ekspedisi.select2({
        dropdownParent:  select2Ekspedisi.parent(),
        searchInputPlaceholder: 'Cari Status Ekspedisi',
        width: '100%',
        placeholder: 'Pilih Ekspedisi'
      }).on('select2:select', function (e) {
            let data = e.params.data;
    });

    if(cek_joborder_id != ''){
    select2Joborder.select2({
        dropdownParent:  select2Joborder.parent(),
        searchInputPlaceholder: 'Cari Job Order',
        width: '100%',
        placeholder: 'Pilih Job Order'
      });
    let optionListJoborder = new Option(cek_kode_joborder, cek_joborder_id, false, false);
    select2Joborder.append(optionListJoborder).trigger('change');
    get_jo(cek_joborder_id);
}else{
    select2Joborder.select2({
        dropdownParent:  select2Joborder.parent(),
        searchInputPlaceholder: 'Cari Job Order',
        width: '100%',
        placeholder: 'Pilih Job Order',
        ajax: {
          url: "{{ route('backend.joborder.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              status_joborder : 1,
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            get_jo(data.id);
            console.log(data);
        });
}



     function get_jo(id){
         $.ajax({
                url: "{{ route('backend.joborder.findjoborder') }}",
                type: 'GET',
                data: {id:  id},
                dataType: 'json', // added data type
                success: function(res) {
                    let data = res;
                    console.log(data);
                    $('#tgl_joborder').val(data.joborder.tgl_joborder);
                    $('#driver_id').val(data.driver.name);
                    $('#nomor_plat').val(data.mobil.nomor_plat);
                    biaya_harga.set(data.rute.harga);
                    $('#customer_id').val(data.customer.name);
                    $('#muatan_id').val(data.muatan.name);
                    $('#first_rute_id').val(data.firstrute.name);
                    $('#last_rute_id').val(data.firstrute.name);
                    total_uang_jalan.set(data.joborder.total_uang_jalan);

                }
         });
     }

$('#berat_muatan').on('keyup', function () {
        let muatan = $(this).val();
        let get_harga =  biaya_harga.getNumber();
        let get_biaya_lain = biaya_lain.getNumber();
        let hasil_total_harga = (get_harga + get_biaya_lain) * muatan;
        total_harga.set(hasil_total_harga);

});

$('#select2Customer').on('change', function (e) {
        dataTable.draw();
 });

$('#konfirmasi_biaya_lain').on('keyup', function () {
        let get_muatan = $('#beban_muatan').val();
        let muatan = (get_muatan > 0) ?  get_muatan : 1;
        let get_harga =  biaya_harga.getNumber();
        let get_biaya_lain = biaya_lain.getNumber();

        let hasil_total_harga = (get_harga + get_biaya_lain) * muatan;
        total_harga.set(hasil_total_harga);

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
// // end
   });


</script>
@endsection
