@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <form id="formUpdate" action="{{ route('backend.invoicecustom.update', Request::segment(3)) }}" autocomplete="off">
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
                    <div class="card-body">
                        <div class="row">
                              <div class="col-md-4">
                                   <div class="mb-3">
                                      <label>Tanggal Invoice<span class="text-danger">*</span></label>
                                      <input type="text" id="tgl_invoice" value="{{ $data['invoice']['tgl_invoice'] }}" name="tgl_invoice"  class="form-control"/>
                                    </div>
                              </div>
                              <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Customer<span class="text-danger">*</span></label>
                                    <select id="select2Customer" style="width: 100% !important;" name="customer_id">
                                        <option value="{{ $data['invoice']['customer']['id'] }}"> {{$data['invoice']['customer']['name'] }}</option>
                                    </select>
                                  </div>
                            </div>
                              <div class="col-md-4">
                                  <div class="mb-3">
                                      <label>Keterangan Invoice<span class="text-danger"></span></label>
                                      <textarea type="text" id="keterangan_invoice" value="" name="keterangan_invoice"  class="form-control">{{ $data['invoice']['keterangan_invoice'] }}</textarea>
                                   </div>
                              </div>
                        </div>
                  </div>

                  <div class="card-body">
                      <div class="table-responsive">
                          <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                              <thead>
                                  <tr>
                                      <th>Keterangan</th>
                                      <th width="30%">Jumlah</th>
                                      <th></th>
                                  </tr>
                              </thead>
                             <tbody></tbody>
                             <tfoot>
                              <tr>
                                  <th colspan="3" style="text-align: end !important;">
                                      <div class="btn-group">
                                          <button id="plus_payment" type="button" class="btn btn-sm btn-outline-secondary btn-add-row"><i class="fa fa-plus"></i></button>
                                      </div>
                                  </th>
                              </tr>
                              <tr>
                                  <th  class="text-end" class="text-right"><label>Jumlah<span class="text-danger"></span></label></th>
                                      <th>
                                          <input id="sub_total" name="sub_total" style="font-size: 12px; color:black;" class="form-control text-end">
                                      </th>
                                  <th></th>
                             </tr>
                              <tr>
                                       <th class="text-end" class="text-end">
                                          <div class="d-flex justify-content-end">
                                              <select class="form-select text-end" id="select2Ppn" name="ppn" style="width: 20%">
                                                <option value="Tidak" {{ $data['invoice']['Tidak'] == 0 ? 'selected' : NULL }}>Tidak</option>
                                          <option value="Iya" {{ $data['invoice']['Iya'] == 0 ? 'selected' : NULL }}>Iya</option>
                                              </select>
                                          </div>

                                       </th>
                                          <th>
                                              <input id="nominal_ppn" name="nominal_ppn" style="font-size: 12px; color:black;" class="form-control text-end" readonly>
                                          </th>
                                      <th></th>
                              </tr>

                              <tr>
                                  <th  class="text-end"  class="text-right"><label>Total Sisa Gaji<span class="text-danger"></span></label></th>
                                      <th>
                                          <input id="total_harga" name="total_harga" style="font-size: 12px; color:black;" class="form-control text-end" readonly>
                                      </th>
                                  <th></th>
                              </tr>
                          </tfoot>
                          </table>
                      </div>


                  </div>

                  <div class="flex-shrink-0">
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
<link type="text/css" href="https://cdn.datatables.net/select/1.6.1/css/select.dataTables.min.css" rel="stylesheet" />

@endsection
@section('script')

<script type="text/javascript" src="https://cdn.datatables.net/select/1.6.1/js/dataTables.select.min.js"></script>
<script>



$(document).ready(function () {


        let select2Ppn = $('#select2Ppn');


    const currenciesOptionsDecimal = {
            caretPositionOnFocus: "start",
            currencySymbol: "",
            unformatOnSubmit: true,
            allowDecimalPadding: true,
            decimalCharacter : ',',
            digitGroupSeparator : '.',
            decimalPlaces: 0,
            modifyValueOnWheel: false,
            minimumValue: 0
    };


    const  sub_total = new AutoNumeric('#sub_total',currenciesOptionsDecimal),
        nominal_ppn = new AutoNumeric('#nominal_ppn',currenciesOptionsDecimal),
        total_harga = new AutoNumeric('#total_harga',currenciesOptionsDecimal);


        select2Ppn.select2({
        dropdownParent: select2Ppn.parent(),
        searchInputPlaceholder: 'Cari Pilihan Ppn',
        width: '20%',
        placeholder: 'Pilih Ppn',
    }).on('select2:select', function (e) {
        $('#Datatable').trigger('changeTotalItem');
    });

    var dummy = [
            // {  jenis_pembayaran: '', keterangan : '', keterangan_kasbon : '', nominal : 0, nominal_kasbon : 0, id:'' }
        ]

        $('#Datatable').on('changeTotalItem',	function(){
        let jumlah  = 0;
        //    let total_nominal_kasbon  = 0;
        //    let for_sisa_uang_jalan = 0;
            $(this).find('[id^="num_nominal"]').each(function(){
                jumlah += AutoNumeric.getNumber(this);
            });
            sub_total.set(jumlah);
            ppn =  0;
            if($('#select2Ppn').find(':selected').val() == 'Iya'){
                ppn =  jumlah * 0.11;
            }
            total = jumlah + ppn;
            nominal_ppn.set(ppn);
            total_harga.set(total);
        });

    const tablePembayaran = $('#Datatable').DataTable({
            // responsive: true,
            paging		: false,
            searching 	: false,
            ordering 	: false,
            info 		: false,
            data 		:  <?= isset($data['invoicedetail']) ? json_encode($data['invoicedetail']) : 'dummy' ;?>,
            columns : [
                {
                    data 		: 'keterangan',
                    className 	: 'text-left',
                    width 		: '70%',
                    render 		: function ( columnData, type, rowData, meta ) {
                        let cek_ket = (columnData == null) ? '' : columnData;
                        return String(`
                            <input name="detail[`+ meta.row +`][id]" type="hidden" value="`+ rowData.id +`" >
                            <input id="keterangan` + meta.row + `" class="form-control" value="`+ cek_ket +`" name="detail[`+ meta.row +`][keterangan]" data-column="keterangan" >
                        `).trim();
                    }
                },
                {
                    data 		: 'nominal',
                    className 	: 'text-right',
                    width 		: '150px',
                    render 		: function ( columnData, type, rowData, meta ) {
                        return String(`
                            <input id="num_nominal` + meta.row + `" class="form-control text-end" style="width: 250px;" value="`+ columnData +`" name="detail[`+ meta.row +`][nominal]" required data-column="nominal" >
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
                    api.row.add({ keterangan: '',  nominal : 0}).draw();
                });
            },
            createdRow : function( row, data, index ){
                new AutoNumeric.multiple($(row).find('[id^="num"]').get(),currenciesOptionsDecimal);
            },
            rowCallback : function( row, data, displayNum, displayIndex, index ){
                let api = this.api();

                $(row).find('#id_'+ index).click(function(){
                    api.row($(this).closest("tr").get(0)).remove().draw();
                });

                $(row).find('#num_nominal' + index).keyup(function(){
                    console.log('a')
                    $('#Datatable').trigger('changeTotalItem');
                });
            },
            drawCallback : function( settings ){
                $('#Datatable').trigger('changeTotalItem');
            }
    });

    $('#tgl_invoice').flatpickr({
    dateFormat: "Y-m-d",
    allowInput: true
    });

    let select2Customer = $('#select2Customer');
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
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
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



        $('#nominal_tambahan_potongan').on('keyup', function () {

            count_total();

        });

        $('#nominal_ppn').on('keyup', function () {
            count_total();
        });

        function count_total(){

            // alert('waw');
                var get_sub_total = sub_total.getNumber();
                var cek_opsi_biaya = $('#select2TambahanPotongan').val();
                var cek_opsi_ppn = $('#select2Ppn').val();
                var get_tambahan_potongan = tambahan_potongan.getNumber();
                // var get_nominal_ppn = nominal_ppn.getNumber();

                var total = 0;
                var ppn = 0;
                var count = 0;
                    if(cek_opsi_ppn ==  'Iya'){
                        if(cek_opsi_biaya == 'Tambahan'){
                            count = get_sub_total + get_tambahan_potongan;
                        }else{
                            count = get_sub_total - get_tambahan_potongan;
                        }
                        ppn =  count * 0.11;
                        total = count + ppn;
                    }else{
                        if(cek_opsi_biaya == 'Tambahan'){
                            total = get_sub_total + get_tambahan_potongan;
                        }else{
                            total = get_sub_total - get_tambahan_potongan;
                        }

                    }
                console.log(ppn);
                nominal_ppn.set(ppn);
                total_harga.set(total);
        }


        // function count_ppn(){
        //         var cek_opsi_biaya = $('#select2Ppn').val();
        //         var get_tambahan_potongan = tambahan_potongan.getNumber();
        //         var get_sub_total = sub_total.getNumber();
        //         var total = 0;
        //         if(cek_opsi_biaya == 'Tambahan'){
        //             total = get_sub_total + get_tambahan_potongan;
        //         }else{
        //             total = get_sub_total - get_tambahan_potongan;
        //         }
        //         total_harga.set(total);
        // }

// // end
   });


</script>
@endsection
