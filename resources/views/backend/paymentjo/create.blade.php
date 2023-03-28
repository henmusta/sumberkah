@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <form id="formStore" action="{{ route('backend.paymentjo.store') }}">
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
                        <input id="cek_joborder_id" name="cek_joborder_id" type="hidden" value="{{$data['joborder']['id'] ?? ''}}">
                        <input id="cek_kode_joborder" name="cek_kode_joborder" type="hidden" value="{{$data['joborder']['kode_joborder'] ?? ''}}">
                        <div class="row" >
                            <div class="col-md-4">
                                 <div class="mb-3">
                                    <label>Id Joborder<span class="text-danger">*</span></label>
                                    <select id="select2Joborder" style="width: 100% !important;" name="joborder_id">

                                    </select>
                                  </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                   <label>Tanggal Joborder<span class="text-danger">*</span></label>
                                   <input type="text" id="tgl_joborder" value="" name="tgl_joborder"  class="form-control" disabled/>
                                 </div>
                           </div>
                           <div class="col-md-4">
                            <div class="mb-3">
                               <label>Driver<span class="text-danger">*</span></label>
                               <input type="text" id="driver_id" value="" name="driver_id"  class="form-control"  disabled/>
                             </div>
                           </div>

                        </div>

                        <div class="row" >
                            <div class="col-md-4">
                                 <div class="mb-3">
                                    <label>Nomor Plat Polisi<span class="text-danger">*</span></label>
                                    <input type="text" id="nomor_plat" value="" name="nomor_plat"  class="form-control"  disabled/>
                                  </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                   <label>Customer<span class="text-danger">*</span></label>
                                   <input type="text" id="customer_id" value="" name="customer_id"  class="form-control"  disabled/>
                                 </div>
                           </div>
                           <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Muatan<span class="text-danger">*</span></label>
                                    <input type="text" id="muatan_id" value="" name="muatan_id"  class="form-control"  disabled/>
                                </div>
                           </div>
                        </div>

                        <div class="row" >
                            <div class="col-md-6">
                                <div class="mb-3">
                                   <label>Alamat Dari (Awal)<span class="text-danger">*</span></label>
                                   <input type="text" id="first_rute_id" value="" name="tgl_joborder"  class="form-control" disabled/>
                                 </div>
                           </div>
                           <div class="col-md-6">
                            <div class="mb-3">
                               <label>Alamat Akhir (Ke)<span class="text-danger">*</span></label>
                               <input type="text" id="last_rute_id" value="" name="joborder_id"  class="form-control"  disabled/>
                             </div>
                           </div>

                        </div>


                        <div class="row" >
                            <div class="col-md-12">
                                <div class="mb-3">
                                   <label>Total Uang Jalan<span class="text-danger">*</span></label>
                                   <input type="text" id="total_uang_jalan" value="" name="tgl_joborder"  class="form-control" disabled/>
                                 </div>
                           </div>
                        </div>

                    </div>


                    <div id="tgl_bayar" class="card-body" style="border: 1px solid #fff; padding:20px;" type="hidden">
                        <div class="row" >

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Tanggal Pembayaran<span class="text-danger">*</span></label>
                                        <input type="text" id="tgl_pembayaran" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="tgl_pembayaran"  class="form-control" placeholder="Masukan Tanggal Joborder"/>
                                      </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Sisa Uang Jalan<span class="text-danger">*</span></label>
                                        <input type="text" id="sisa_uang_jalan" value="0" name="sisa_uang_jalan"  class="form-control text-end" style="font-size: 24px; color:black;" disabled/>
                                      </div>
                                </div>

                        </div>
                    </div>


                    <div id="table_pembayaran" class="card-body" style=" padding:20px;">
                        <div class="row" >
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Jumlah Bon Tersedia<span class="text-danger">*</span></label>
                                    <input type="text" id="kasbon" value="0" name="kasbon"  class="form-control" style="font-size: 24px; color:black;" disabled/>
                                  </div>
                            </div>
                        </div>

                        <div class="row" >
                            <div class="col-md-12">
                                 <label> Tabel Pembayaran<span class="text-danger">*</span></label>
                                 <div class="table-responsive ">
                                    <table id="Datatable" class="table " width="100%">
                                        <thead>
                                            <tr>
                                                <th>Jenis Pembayaran</th>
                                                <th>Keterangan Pembayaran</th>
                                                <th>Keterangan Kasbon</th>
                                                <th>Nominal Pembayaran</th>
                                                <th>Nominal Kasbon</th>
                                                <th width="2%"></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>
                                                    <div class="btn-group">
                                                        <button disabled id="plus_payment" type="button" class="btn btn-sm btn-outline-secondary btn-add-row"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                    <th  class="text-end" colspan="4" class="text-right"><label>Total Pembayaran<span class="text-danger"></span></label></th>
                                                        <th style="width:250px">
                                                            <input id="total_payment" name="total_payment" style="font-size: 24px; color:black;" class="form-control text-end" readonly>
                                                        </th>
                                                    <th></th>
                                            </tr>
                                            <tr>
                                                <th  class="text-end" colspan="4" class="text-right"><label>Total Potongan Bon<span class="text-danger"></span></label></th>
                                                    <th>
                                                        <input id="total_kasbon" name="total_kasbon" style="font-size: 24px; color:black;" class="form-control text-end" readonly>
                                                    </th>
                                                <th></th>
                                           </tr>
                                            <tr>
                                                <th  class="text-end" colspan="4" class="text-right"><label>Total Sisa Uang Jalan<span class="text-danger"></span></label></th>
                                                    <th>
                                                        <input id="total_sisa_uang_jalan"  name="total_sisa_uang_jalan" style="font-size: 24px; color:black;" class="form-control text-end" readonly>
                                                    </th>
                                                <th></th>
                                            </tr>
                                        </tfoot><br>
                                    </table>
                                 </div>
                            </div>
                        </div>
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


.horizontal-scrollable > .row {
            overflow-x: auto;
            white-space: nowrap;
        }

        .horizontal-scrollable > .row > .col-xs-4 {
            display: inline-block;
            float: none;
        }
</style>
@endsection
@section('script')
<script>

// $('#rute_muatan').hide();
// $('#number_jo').hide();
// $('#btn_simpan').hide();
// $('#id').show();
var cek_potongan_tambahan, fix_total_uang_jalan;

$(document).ready(function () {
    $('#tgl_pembayaran').flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true
         });

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

    const  sisa_uang_jalan 	= new AutoNumeric('#sisa_uang_jalan',currenciesOptions),
           total_uang_jalan = new AutoNumeric('#total_uang_jalan',currenciesOptions),
           total_sisa_uang_jalan 	= new AutoNumeric('#total_sisa_uang_jalan',currenciesOptions),
           total_payment 	= new AutoNumeric('#total_payment',currenciesOptions),
           kasbon 	= new AutoNumeric('#kasbon',currenciesOptions),
           total_bon 	= new AutoNumeric('#total_kasbon',currenciesOptions);




       var dummy = [
            // {  jenis_pembayaran: '', keterangan : '', keterangan_kasbon : '', nominal : 0, nominal_kasbon : 0, id:'' }
        ]


        $('#Datatable').on('changeTotalItem',	function(){
           let total_nominal_payment  = 0;
           let total_nominal_kasbon  = 0;
           let for_sisa_uang_jalan = 0;
            $(this).find('[id^="num_nominal_payment"]').each(function(){
                total_nominal_payment += AutoNumeric.getNumber(this);
            });

            $(this).find('[id^="num_nominal_kasbon"]').each(function(){
                total_nominal_kasbon += AutoNumeric.getNumber(this);
            });
            // total paymanet
            let get_total_uang_jalan = total_uang_jalan.getNumber();
            let get_sisa_uang_jalan = sisa_uang_jalan.getNumber();
            for_sisa_uang_jalan = get_total_uang_jalan -  total_nominal_kasbon - total_nominal_payment;
            if(for_sisa_uang_jalan < 0){
                $('#btn_simpan').prop('disabled', true);
              //  toastr.error('Nominal Pembayaran/Kasbon Melebihi Sisa Uang Jalan', 'Gagal !');
                total_payment.set(0);
                total_bon.set(0);
                total_sisa_uang_jalan.set(get_sisa_uang_jalan);
            }else{
                $('#btn_simpan').prop('disabled', false);
                total_payment.set(total_nominal_payment);
                total_bon.set(total_nominal_kasbon);
                total_sisa_uang_jalan.set(for_sisa_uang_jalan);
            }


             // tota sisa uang jalan


		});


      const tablePembayaran = $('#Datatable').DataTable({
            // responsive: true,
			paging		: false,
			searching 	: false,
			ordering 	: false,
			info 		: false,
			data 		: dummy ,
			columns : [

				{
					data 		: 'jenis_pembayaran',
					className 	: 'text-left',
					width 		: '150px',
					render 		: function ( columnData, type, rowData, meta ) {
                        return String(`
                            <select class="form-control" data-name="jenis_pembayaran" required="required" name="payment[`+ meta.row +`][jenis_pembayaran]">
                                <option value="Tunai" `+ ( columnData == '1' ? `selected="selected"` : ``) +`>Tunai</option>
                                <option value="Transfer" `+ ( columnData == '0' ? `selected="selected"` : ``) +`>Transfer</option>
                            </select>
                          `).trim();
						// return String(`
                        //  <input id="nominal` + meta.row + `" class="form-control text-right" value="`+ columnData +`" name="payment[`+ meta.row +`][keterangan]" required data-column="keterangan" >
						// `).trim();
					}
				},
                {
					data 		: 'keterangan',
					className 	: 'text-right',
					width 		: '150px',
					render 		: function ( columnData, type, rowData, meta ) {
                        return String(`
							<input id="keterangan` + meta.row + `" class="form-control" style="width: 250px;" value="`+ columnData +`" name="payment[`+ meta.row +`][keterangan]" required data-column="keterangan" >
						`).trim();
					}
				},
                {
					data 		: 'keterangan_kasbon',
					className 	: 'text-right',
					width 		: '150px',
					render 		: function ( columnData, type, rowData, meta ) {
                        return String(`
							<input id="keterangan_kasbon` + meta.row + `" class="form-control" style="width: 250px;" value="`+ columnData +`" name="payment[`+ meta.row +`][keterangan_kasbon]" data-column="keterangan" >
						`).trim();
					}
				},
				{
					data 		: 'nominal',
					className 	: 'text-right',
					width 		: '150px',
					render 		: function ( columnData, type, rowData, meta ) {
						return String(`
							<input id="num_nominal_payment` + meta.row + `" class="form-control text-end" style="width: 250px;" value="`+ columnData +`" name="payment[`+ meta.row +`][nominal]" required data-column="nominal" >
						`).trim();
					}
				},
                {
					data 		: 'nominal_kasbon',
					className 	: 'text-right',
					width 		: '150px',
					render 		: function ( columnData, type, rowData, meta ) {
						return String(`
							<input id="num_nominal_kasbon` + meta.row + `" class="form-control text-end" style="width: 250px;" value="`+ columnData +`" name="payment[`+ meta.row +`][nominal_kasbon]" required data-column="nominal" >
						`).trim();
					}
				},

				{
					data 		: 'id',
					width 		: '10px',
					className 	: 'text-center',
					render 		: function ( columnData, type, rowData, meta ) {
						return String(`
							<button type="button" id="id_` + meta.row + `" class="btn btn-sm btn-outline-secondary btn-delete-row"><i class="fa fa-minus"></i></button>
						`).trim();
					}
				}
			],
			initComplete : function(settings, json){
				let api = this.api();
				$(api.table().footer()).find('.btn-add-row').click(function(){
                    let keterangan_payment = 'Payment Joborder : '+ $('#cek_kode_joborder').val();
					api.row.add({ jenis_pembayaran: '', keterangan : keterangan_payment, keterangan_kasbon : '', nominal : 0, nominal_kasbon : 0 }).draw();
				});
			},
			createdRow : function( row, data, index ){
                new AutoNumeric.multiple($(row).find('[id^="num"]').get(),currenciesOptions);
			},
			rowCallback : function( row, data, displayNum, displayIndex, index ){
				let api = this.api();

				$(row).find('#id_'+ index).click(function(){
					api.row($(this).closest("tr").get(0)).remove().draw();
				});


                $(row).find('#num_nominal_payment' + index +',#num_nominal_kasbon' + index).keyup(function(){
                    let get_total_uang_jalan  = total_uang_jalan.getNumber();
                    let get_total_payment  = total_payment.getNumber();
                    let get_total_kasbon  = total_bon.getNumber();
                    let get_kasbon  = kasbon.getNumber();
                    let get_nominal = AutoNumeric.getNumber('#num_nominal_payment' + index);
                    let get_nominal_kasbon = AutoNumeric.getNumber('#num_nominal_kasbon' + index);


                    if(get_nominal_kasbon > get_kasbon){
                        toastr.error('Nominal Kasbon Melebihi Kasbon Tersedia', 'Gagal !');
                        AutoNumeric.getAutoNumericElement('#num_nominal_kasbon' + index).set(0);
                        $(row).find('#keterangan_kasbon' + index).val('');
                    }else{
                        let keterangan_kasbon = '-';
                        $(row).find('#keterangan_kasbon' + index).val(keterangan_kasbon);
                    }

                    // console.log(get_sisa_uang_jalan);
                    // console.log( get_nominal_kasbon);
                    if(get_nominal > (get_total_uang_jalan - get_total_kasbon)){
                        toastr.error('Nominal Pembayaran Melebihi Sisa Uang Jalan', 'Gagal !');
                        AutoNumeric.getAutoNumericElement('#num_nominal_payment' + index).set(0);
                    } else if(get_nominal_kasbon >  (get_total_uang_jalan - get_total_payment)){
                        toastr.error('Nominal Kasbon Melebihi Sisa Uang Jalan', 'Gagal !');
                        AutoNumeric.getAutoNumericElement('#num_nominal_kasbon' + index).set(0);
                    }else{
                        $('#btn_simpan').prop('disabled', false);
                    }

                    // // console.log(get_nominal);
                    // // console.log(total_uang_jalan.getNumber());
                    // // console.log(total_bon.getNumber());

                    // if(get_nominal > get_total_uang_jalan){


                    // }else{
                    //     $('#btn_simpan').prop('disabled', false);

                    // }
                    $('#Datatable').trigger('changeTotalItem');
                });



                // $(row).find('#num_nominal_kasbon' + index).keyup(function(){

                //     $('#Datatable').trigger('changeTotalItem');
                // });
			},
			drawCallback : function( settings ){
                $('#Datatable').trigger('changeTotalItem');
			}
	});


//        $('#tahun').flatpickr({
//             disableMobile: "true",
//             plugins: [
//                 new monthSelectPlugin({
//                 shorthand: true,
//                 dateFormat: "Y",
//                 // altFormat: "Y",
//                 theme: "dark"
//                 })
//             ]
//          });

//          $('#tgl_joborder').flatpickr({
//             dateFormat: "Y-m-d"
//          });


//     let select2Firstrute = $('#select2Firstrute');
//     let select2Lastrute = $('#select2Lastrute');
//     let select2Muatan = $('#select2Muatan');
//     let select2Jenis = $('#select2Jenis');
//     let select2Customer = $('#select2Customer');
//     let select2TambahanPotongan = $('#tambahan_potongan');
//     let select2Driver = $('#select2Driver');
//     let select2Mobil = $('#select2Mobil');
let select2Joborder = $('#select2Joborder');
let select2jenisbayar = $('.select2paymetode');
let cek_joborder_id = $('#cek_joborder_id').val();
let cek_kode_joborder = $('#cek_kode_joborder').val();
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
              konfirmasi_joborder: 2,
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



    select2jenisbayar.select2({});



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
                    $('#cek_kode_joborder').val(data.joborder.kode_joborder);
                    $('#driver_id').val(data.driver.name);
                    $('#nomor_plat').val(data.mobil.nomor_plat);
                    $('#customer_id').val(data.customer.name);
                    $('#muatan_id').val(data.muatan.name);
                    $('#first_rute_id').val(data.firstrute.name);
                    $('#last_rute_id').val(data.firstrute.name);
                    total_uang_jalan.set(data.joborder.total_uang_jalan);
                    sisa_uang_jalan.set(data.joborder.sisa_uang_jalan);
                    total_sisa_uang_jalan.set(data.joborder.sisa_uang_jalan);
                    kasbon.set(data.driver.kasbon);
                    if(data.driver.kasbon > 0 && data.joborder.sisa_uang_jalan> 0){
                        $('#plus_kasbon').prop('disabled', false);
                    }else{
                        $('#plus_kasbon').prop('disabled', true);
                    }


                    if(data.joborder.sisa_uang_jalan > 0){
                        $('#plus_payment').prop('disabled', false);
                    }else{
                        $('#plus_payment').prop('disabled', true);
                    }
                    // alert(res);
                }
         });
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
