@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                  <input type="hidden" id="cek_akses_edit" value="{{Auth::user()->roles()->first()->level }}">
                <form id="formUpdate" action="{{ route('backend.paymentinvoice.update', Request::segment(3)) }}">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    @method('PUT')
                    <div class="card-header">
                        <div>
                            <h6 class="main-content-label mb-1">{{ $config['page_title'] ?? '' }}</h6>
                        </div><br>
                        <div id="errorEdit" class="mb-3" style="display:none;">
                            <div class="alert alert-danger" role="alert">
                              <div class="alert-text">
                              </div>
                            </div>
                          </div>
                    </div>
                    <div class="card-body" style="border: 1px solid #fff; padding:20px;">
                        <input id="cek_invoice_id" name="cek_invoice_id" type="hidden" value="{{$data['invoice']['id'] ?? ''}}">
                        <input id="cek_kode_invoice" name="cek_kode_invoice" type="hidden" value="{{$data['invoice']['kode_invoice'] ?? ''}}">
                        <div class="row" >
                            <div class="col-md-6">
                                 <div class="mb-3">
                                    <label>Kode Invoice<span class="text-danger">*</span></label>
                                    <select id="select2Invoice" style="width: 100% !important;" name="invoice_id">
                                        <option value="{{ $data['invoice']['id'] }}"> {{$data['invoice']['kode_invoice'] }}</option>
                                    </select>
                                  </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                   <label>Tanggal Invoice<span class="text-danger">*</span></label>
                                   <input type="text" id="tgl_invoice" value="" name="tgl_invoice"  class="form-control" />
                                 </div>
                           </div>
                           <div class="col-md-6">
                                <div class="mb-3">
                                <label>Customer<span class="text-danger">*</span></label>
                                <input type="text" id="customer_id" value="" name="customer_id"  class="form-control"  disabled/>
                                </div>
                           </div>
                            <div class="col-md-6">
                                    <div class="mb-3">
                                    <label>TOP<span class="text-danger">*</span></label>
                                    <input type="text" id="tgl_jatuh_tempo" value="" name="tgl_jatuh_tempo"  class="form-control"  disabled/>
                                    </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                   <label>Payment Hari<span class="text-danger">*</span></label>
                                   <input type="text" id="payment_hari" value="" name="payment_hari"  class="form-control"  disabled/>
                                 </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                   <label>Total Tagihan<span class="text-danger">*</span></label>
                                   <input type="text" id="total_harga" value="" name="total_harga"  class="form-control"  disabled/>
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
                                        <label>Sisa Tagihan<span class="text-danger">*</span></label>
                                        <input type="text" id="sisa_tagihan" value="0" name="sisa_tagihan"  class="form-control text-end" style="font-size: 24px; color:black;" disabled/>
                                      </div>
                                </div>

                        </div>
                    </div>

                    <div id="table_pembayaran" class="card-body" style=" padding:20px;">
                        <div class="row" >
                            <div class="col-12">
                                 <label> Tabel Pembayaran<span class="text-danger">*</span></label>
                                 <div class="table-responsive">
                                    <table id="Datatable" class="table " width="100%">
                                        <thead>
                                            <tr>
                                                <th>Jenis Pembayaran</th>
                                                <th>Keterangan</th>
                                                <th>Nominal Pembayaran</th>
                                                <th width="2%"></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
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
                                                    <th  class="text-end" colspan="2" class="text-right"><label>Total Pembayaran<span class="text-danger"></span></label></th>
                                                        <th>
                                                            <input id="total_payment" value="{{ $data['invoice']['total_payment'] ?? 0 }}" name="total_payment" style="font-size: 24px; color:black;" class="form-control text-end" readonly>
                                                        </th>
                                                    <th></th>
                                            </tr>

                                            <tr>
                                                <th  class="text-end" colspan="2" class="text-right"><label>Total Sisa Tagihan<span class="text-danger"></span></label></th>
                                                    <th>
                                                        <input id="total_sisa_tagihan" value="{{ $data['invoice']['sisa_tagihan'] ?? 0 }}"  name="total_sisa_tagihan" style="font-size: 24px; color:black;" class="form-control text-end" readonly>
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
                        <button type="button" class="btn btn-secondary me-2" onclick="window.history.back();">
                          Cancel
                        </button>
                        <button id="btn_simpan" type="submit" class="btn btn-primary">Submit</button>
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

    get_invoice({!!$data['invoice']['id']!!});
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

    const   total_tagihan 	= new AutoNumeric('#total_harga',currenciesOptions),
            total_payment 	= new AutoNumeric('#total_payment',currenciesOptions),
            total_sisa_tagihan 	= new AutoNumeric('#total_sisa_tagihan',currenciesOptions),
            sisa_tagihan 	= new AutoNumeric('#sisa_tagihan',currenciesOptions);

    let select2Invoice = $('#select2Invoice');
    select2Invoice.select2({
        dropdownParent:  select2Invoice.parent(),
        searchInputPlaceholder: 'Cari Invoice',
        width: '100%',
        placeholder: 'Pilih Invoice'
    });


        function get_invoice(id){
         $.ajax({
                url: "{{ route('backend.invoice.findinvoice') }}",
                type: 'GET',
                data: {id:  id},
                dataType: 'json', // added data type
                success: function(res) {
                    let data = res;
                    console.log(data);
                    $('#tgl_invoice').val(data.invoice.tgl_invoice);
                    $('#customer_id').val(data.customer.name);
                    $('#tgl_jatuh_tempo').val(data.invoice.tgl_jatuh_tempo);
                    $('#payment_hari').val(data.invoice.payment_hari);
                    total_tagihan.set(data.invoice.total_harga);
                    console.log(data.invoice.sisa_tagihan);
                    sisa_tagihan.set(data.invoice.sisa_tagihan);
                    total_payment.set(data.invoice.total_payment);
                    total_sisa_tagihan.set(data.invoice.sisa_tagihan);
                    // toastr.success('Data Telah Tersedia', 'Success !');


                    if(data.invoice.sisa_tagihan > 0){
                        $('#plus_payment').prop('disabled', false);
                    }else{
                        $('#plus_payment').prop('disabled', true);
                    }
                    // alert(res);
                }
         });
     }

     let cek_edit = $('#cek_akses_edit').val();
      var cek_readonly;
      var cek_disabled;

            var dummy = [
            // {  jenis_pembayaran: '', keterangan : '', nominal : 0 }
        ]


        $('#Datatable').on('changeTotalItem',	function(){
            let total_nominal_payment   = 0;
            let for_sisa_tagihan = 0;
            $(this).find('[id^="num_nominal"]').each(function(){
                total_nominal_payment += AutoNumeric.getNumber(this);
            });

            let get_total_tagihan = total_tagihan.getNumber();
            let get_sisa_tagihan = sisa_tagihan.getNumber();
            // total_sisa_tagihan.set(for_sisa_tagihan);
            // total_sisa_tagihan.set(for_sisa_tagihan);
            if(total_nominal_payment > get_total_tagihan){
           //     $('#btn_simpan').prop('disabled', true);
             //   toastr.error('Jumlah Pembayaran Melebihi Sisa Uang Jalan', 'Gagal !');
                for_sisa_tagihan = get_total_tagihan;
               total_payment.set(0);
            }
            else{
                $('#btn_simpan').prop('disabled', false);
                total_payment.set(total_nominal_payment);
                for_sisa_tagihan = get_total_tagihan - total_nominal_payment;
            }

            console.log( for_sisa_tagihan);
             // tota sisa uang jalan

            total_sisa_tagihan.set(for_sisa_tagihan);
		});

     const tablePembayaran = $('#Datatable').DataTable({
			paging		: false,
			searching 	: false,
			ordering 	: false,
			info 		: false,
			data 		: <?= isset($data['payment']) ? json_encode($data['payment']) : 'payment' ;?> ,
			columns : [

				{
					data 		: 'jenis_payment',
					className 	: 'text-left',
					width 		: '0px',
					render 		: function ( columnData, type, rowData, meta ) {
                        cek_readonly = (rowData.id != undefined && cek_edit != 1) ? 'readonly' : ''
                        return String(`
                        <input name="payment[`+ meta.row +`][id]" type="hidden" value="`+ rowData.id +`" >
                            <select `+ cek_readonly +` class="form-control selectjenis" data-name="jenis_pembayaran" required="required" name="payment[`+ meta.row +`][jenis_pembayaran]">
                                <option value="Tunai" `+ ( columnData == 'Tunai' ? `selected="selected"` : ``) +`>Tunai</option>
                                <option value="Transfer" `+ ( columnData == 'Transfer' ? `selected="selected"` : ``) +`>Transfer</option>
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
                        cek_readonly = (rowData.id != undefined && cek_edit != 1) ? 'readonly' : ''
                        return String(`
							<input `+ cek_readonly +` style="width: 400px;" id="keterangan` + meta.row + `" class="form-control" value="`+ columnData +`" name="payment[`+ meta.row +`][keterangan]" required data-column="keterangan" >
						`).trim();
					}
				},
				{
					data 		: 'nominal',
					className 	: 'text-right',
					width 		: '150px',
					render 		: function ( columnData, type, rowData, meta ) {
                        cek_readonly = (rowData.id != undefined && cek_edit != 1) ? 'readonly' : ''
						return String(`
							<input  `+ cek_readonly +` style="width: 400px;" id="num_nominal_payment` + meta.row + `" class="form-control text-end" value="`+ columnData +`" name="payment[`+ meta.row +`][nominal]" required data-column="nominal" >
						`).trim();
					}
				},

				{
					data 		: 'id',
					width 		: '10px',
					className 	: 'text-center',
					render 		: function ( columnData, type, rowData, meta ) {
                        cek_disabled = (rowData.id != undefined && cek_edit != 1) ? 'disabled' : ''
						return String(`
							<button  `+ cek_disabled +` type="button" id="id_` + meta.row + `" class="btn btn-sm btn-outline-secondary btn-delete-row"><i class="fa fa-minus"></i></button>
						`).trim();
					}
				}
			],
			initComplete : function(settings, json){
				let api = this.api();
				$(api.table().footer()).find('.btn-add-row').click(function(){
                    let keterangan_payment = '-';
					api.row.add({ jenis_pembayaran: '', keterangan : keterangan_payment, nominal : 0 }).draw();
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


                $(row).find('#num_nominal_payment' + index).keyup(function(){
                    let get_nominal = AutoNumeric.getNumber('#num_nominal_payment' + index);
                    let get_sisa_tagihan  = sisa_tagihan.getNumber();
                    console.log(get_nominal);
                    // console.log(total_uang_jalan.getNumber());
                    // console.log(total_bon.getNumber());

                    if(get_nominal > get_sisa_tagihan){
                        $('#btn_simpan').prop('disabled', true);
                        AutoNumeric.getAutoNumericElement('#num_nominal_payment' + index).set(0);
                        toastr.error('Jumlah Pembayaran Melebihi Sisa Tagihan', 'Gagal !');

                    }else{
                        $('#btn_simpan').prop('disabled', false);

                    }
                    $('#Datatable').trigger('changeTotalItem');
                });
			},
			drawCallback : function( settings ){
                $('#Datatable').trigger('changeTotalItem');
			}
	});

    // $(".selectjenis").select2({ width: '400px' });

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
