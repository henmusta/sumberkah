@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <form id="formUpdate" action="{{ route('backend.penggajian.update', Request::segment(3)) }}">
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
                              <div class="col-md-6">
                                <input id="penggajian_id" name="penggajian_id"  value="{{ $data['gaji']['id'] }}" type="hidden">
                                    <div class="mb-3">
                                        <label>Tanggal<span class="text-danger">*</span></label>
                                        <input type="text" id="tgl_gaji"  value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="tgl_gaji"  class="form-control"/>
                                    </div>
                                      <div class="mb-3">
                                        <label>Driver<span class="text-danger">*</span></label>
                                        <select id="select2Driver" style="width: 100% !important;" name="driver_id">
                                            <option value="{{ $data['gaji']['driver']['id'] }}"> {{$data['gaji']['driver']['name'] }}</option>
                                        </select>
                                      </div>
                                      <div class="mb-3">
                                        <label>Nomor Plat Polisi<span class="text-danger">*</span></label>
                                        <select id="select2Mobil" style="width: 100% !important;" name="mobil_id">
                                            <option value="{{ $data['gaji']['mobil']['id'] }}"> {{$data['gaji']['mobil']['nomor_plat'] }}</option>
                                        </select>
                                      </div>
                                      <div class="mb-3">
                                        <label>Bulan Kerja<span class="text-danger">*</span></label>
                                        <input type="text" id="bulan_kerja"  value="{{ \Carbon\Carbon::parse($data['gaji']['bulan_kerja'])->format('Y-m') }}" name="bulan_kerja"  class="form-control"/>
                                       </div>
                                       <div class="mb-3">
                                        <label>Keterangan<span class="text-danger"></span></label>
                                        <textarea type="text" id="keterangan_gaji"  name="keterangan_gaji"  class="form-control">{{ $data['gaji']['keterangan_gaji'] ?? ''}}</textarea>
                                       </div>
                              </div>

                              <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Sub_total<span class="text-danger">*</span></label>
                                    <input type="text" id="sub_total" value="{{ $data['gaji']['sub_total'] ?? '' }}" name="sub_total"  class="form-control" readonly/>
                                </div>
                                <div class="mb-3">
                                    <label>Kasbon Tersedia<span class="text-danger"></span></label>
                                    <input type="text" id="total_kasbon"  value="{{ $data['gaji']['driver']['kasbon'] + $data['gaji']['nominal_kasbon'] ?? '' }}" name="total_kasbon"  class="form-control" disabled/>
                                </div>
                                <div class="mb-3">
                                    <label>Bayar Kasbon<span class="text-danger"></span></label>
                                    <input type="text" id="nominal_kasbon" value="{{ $data['gaji']['nominal_kasbon'] ?? '' }}" name="nominal_kasbon"  class="form-control" disabled/>
                                </div>
                                <div class="mb-3">
                                    <label>bonus<span class="text-danger"></span></label>
                                    <input type="text" id="bonus" value="{{ $data['gaji']['bonus'] ?? '' }}" name="bonus"  class="form-control" disabled/>
                                </div>

                                <div class="mb-3">
                                    <label>Grand Total<span class="text-danger">*</span></label>
                                    <input type="text" id="total_gaji" value="{{ $data['gaji']['total_gaji'] ?? '' }}" name="total_gaji"  class="form-control" readonly/>
                                </div>
                              </div>

                          </div>


                          <div class="row">
                            <div class="mb-3">
                                <label>Kode Joborder<span class="text-danger">*</span></label>
                                <textarea type="text" id="kode_joborder" value="" name="kode_joborder"  class="form-control" disabled></textarea>
                              </div>
                          </div>
                    </div>




                    <div  class="card-footer">
                      <div class="d-flex justify-content-end">
                        <button id="get_jo" type="button" class="btn btn-success me-2">
                            Konfirmasi JO
                        </button>
                        <button id="btn_simpan" type="button" class="btn btn-secondary me-2" onclick="window.history.back();">
                          Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsove">
                            <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Pilih</th>
                                        <th class="text-center">Kode Joborder</th>
                                        <th>Tgl Muat</th>
                                        <th>Tgl Bongkar</th>
                                        <th>Plat Nomor</th>
                                        <th>Muatan</th>
                                        <th>Alamat Awal (Dari)</th>
                                        <th>Alamat Akhir (Dari)</th>
                                        <th>Tonase</th>
                                        <th>Gaji</th>

                                        {{-- <th width="8%">Aksi</th> --}}
                                      </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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

    const   total_kasbon =new AutoNumeric('#total_kasbon',currenciesOptions),
            bonus =new AutoNumeric('#bonus',currenciesOptions),
            sub_total =new AutoNumeric('#sub_total',currenciesOptions),
            total_gaji =new AutoNumeric('#total_gaji',currenciesOptions),
            nominal_kasbon = new AutoNumeric('#nominal_kasbon',currenciesOptions);


    $('#tgl_gaji').flatpickr({
       dateFormat: "Y-m-d"
    });

    $('#bulan_kerja').flatpickr({
            disableMobile: "true",
            plugins: [
                new monthSelectPlugin({
                shorthand: true,
                dateFormat: "Y-m",
                // altFormat: "Y",
                theme: "dark"
                })
            ]
    });

    let select2Driver = $('#select2Driver');
    let select2Mobil = $('#select2Mobil');

    select2Driver.select2({
        dropdownParent: select2Driver.parent(),
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
            console.log(data);
            total_kasbon.set(data.kasbon)
      });


      select2Mobil.select2({
        dropdownParent: select2Mobil.parent(),
        searchInputPlaceholder: 'Cari Mobil',
        width: '100%',
        placeholder: 'Pilih Mobil',
        ajax: {
          url: "{{ route('backend.mobil.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              driver_id: $('#select2Driver').find(':selected').val(),
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;

      });


       $('#select2Mobil, #select2Driver, #bulan_kerja').on('change', function (e) {
             dataTable.draw();
        });


    let dataTable = $('#Datatable').DataTable({
        select: {
            style: 'os',
            selector: 'td:not(:last-child)' // no row selection on last column
        },
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.joborder.datatablecekjoborder') }}",
          data: function (d) {
            d.driver_id = $('#select2Driver').find(':selected').val();
            d.mobil_id = $('#select2Mobil').find(':selected').val();
            d.bulan_kerja = $('#bulan_kerja').val();
            d.penggajian_id = $('#penggajian_id').val();
          }
        },

        columns: [
            {
                data:   "id",
                render: function ( data, type, row ) {
                    if ( type === 'display' ) {
                        return '<input type="checkbox" value="'+ data +'" class="editor-active">';
                    }
                    return data;
                },
                className: "dt-body-center"
          },
          {data: 'kode_joborder', name: 'kode_joborder'},
          {data: 'konfirmasijo.0.tgl_muat', name: 'konfirmasijo.0.tgl_muat'},
          {data: 'konfirmasijo.0.tgl_bongkar', name: 'konfirmasijo.0.tgl_bongkar'},
          {data: 'mobil.nomor_plat', name: 'mobil.nomor_plat'},
          {data: 'muatan.name', name: 'muatan.name'},
          {data: 'ruteawal.name', name: 'ruteawal.name'},
          {data: 'ruteakhir.name', name: 'ruteakhir.name'},
          {data: 'konfirmasijo.0.berat_muatan', name: 'konfirmasijo.0.berat_muatan'},
          {data: 'rute.gaji', name: 'rute.gaji'},

        ],

        columnDefs: [
            // return '<input type="checkbox"' + (data == "true" ? ' checked="checked"' : '') + '>';
          {
            targets: [8, 9],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          },
        //   {
        //     targets: 0,
        //     orderable: false, searchable: false,
        //         data: 0,
        //         checkboxes: {
        //             selectRow: false
        //     }
        //   }

        ],
        rowCallback: function ( row, data ) {
            console.log(data);
            $('input.editor-active', row).prop( 'checked', data.penggajian_id != null );
        }
      });


      $('#get_jo').on('click', function(e) {
        var oTable = $('#Datatable').dataTable();
        var rowcollection =  oTable.$(".editor-active:checked", {"page": "all"});
        var row_id = [];
        var form = this;
        rowcollection.each(function(index, elem){

            console.log(elem);
            var checkbox_value = $(elem).val();

            $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'konfirmasijoid[]')
                .val(checkbox_value)
            );
            row_id.push(checkbox_value);
        });



        // var id = rows_selected_id.join(",");
        // console.log(id);

        $.ajax({
                url: "{{ route('backend.joborder.findkonfirmasijoborder') }}",
                type: 'GET',
                data: {konfirmasijo_id:  row_id},
                dataType: 'json', // added data type
                success: function(res) {
                    let data = res;
                    console.log(data);
                    const kode_joborder =  data.kode_joborder;
                    const JoinedKode = kode_joborder.join();
                    console.log(JoinedKode);
                    toastr.success('Data Telah Tersedia', 'Success !');
                    $('#kode_joborder').val(JoinedKode);
                    sub_total.set(data.sum_gaji);
                    total_gaji.set(data.sum_gaji);
                    $('#nominal_kasbon').prop('disabled', false);
                    $('#bonus').prop('disabled', false);
                    count_total();
                    // console.log(joinedCities);
                    // $('#tgl_joborder').val(data.joborder.tgl_joborder);
                    // $('#driver_id').val(data.driver.name);
                    // $('#nomor_plat').val(data.mobil.nomor_plat);
                    // biaya_harga.set(data.rute.harga);
                    // $('#customer_id').val(data.customer.name);
                    // $('#muatan_id').val(data.muatan.name);
                    // $('#first_rute_id').val(data.firstrute.name);
                    // $('#last_rute_id').val(data.firstrute.name);
                    // total_uang_jalan.set(data.joborder.total_uang_jalan);

                }
         });

    });

// select2TambahanPotongan.on('change', function (e) {
//     var optionSelected = $("option:selected", this);
//     var valueSelected = this.value;
//     if(valueSelected != 'None'){
//         console.log(valueSelected);
//         $('#nominal_tambahan_potongan').prop("disabled", false);
//     }else{
//         console.log(valueSelected);
//         $('#nominal_tambahan_potongan').prop('disabled', true);
//     }
//     tambahan_potongan.set(0);
//     count_total();
// });


// select2Ppn.on('change', function (e) {
//     var optionSelected = $("option:selected", this);
//     var valueSelected = this.value;
//     if(valueSelected != 'None'){
//         console.log(valueSelected);
//         $('#nominal_ppn').prop("disabled", false);
//     }else{
//         console.log(valueSelected);
//         $('#nominal_ppn').prop('disabled', true);

//     }
//     nominal_ppn.set(0);
//     count_total();
// });




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
        $('#select2Customer').on('change', function (e) {
             dataTable.draw();
            //  $('#payment_hari').val('');
            //  $('#select2TambahanPotongan').val('None').trigger('change');
            //  $('#nominal_tambahan_potongan').val('');
            //  $('#total_tonase').val('');
            //  $('#keterangan_invoice').val('');
            //  $('#sub_total').val('');
            //  $('#select2Ppn').val('None').trigger('change');
            //  $('#nominal_ppn').val('');
            //  $('#total_harga').val('');
            //  $('#kode_joborder').val('');
        });




        $('#nominal_kasbon').on('keyup', function () {
            count_total();
        });

        $('#bonus').on('keyup', function () {
            count_total();
        });

        function count_total(){
                var get_sub_total = sub_total.getNumber();
                var get_total_kasbon = total_kasbon.getNumber();
                var get_bonus = bonus.getNumber();
                var get_nominal_kasbon = nominal_kasbon.getNumber();
                let total = 0;
                let count_sub_bonus =  get_sub_total + get_bonus;
                let count_sub_kasbon =  get_sub_total - get_nominal_kasbon;

                console.log(get_nominal_kasbon);
                // console.log(get_total_kasbon);

                let edit_nominal_kasbon = get_nominal_kasbon + get_total_kasbon;

                console.log(edit_nominal_kasbon);

                if(get_nominal_kasbon > count_sub_bonus){
                    toastr.error('Nominal kasbon Melebihi Grand Total', 'Gagal !');
                    total =  count_sub_bonus;
                    nominal_kasbon.set(0);
                }else if( get_total_kasbon > edit_nominal_kasbon ){
                    toastr.error('Nominal Kasbon Melebihi Kasbon Tersedia', 'Gagal !');
                    total =  count_sub_bonus;
                    nominal_kasbon.set(0);
                }else{
                    total = count_sub_bonus - get_nominal_kasbon;
                }
                total_gaji.set(total);
                // total = get_sub_total + bonus -
                // var total = 0;
                // var ppn = 0;
                // var count = 0;
                //     if(cek_opsi_ppn ==  'Iya'){
                //         if(cek_opsi_biaya == 'Tambahan'){
                //             count = get_sub_total + get_tambahan_potongan;
                //         }else{
                //             count = get_sub_total - get_tambahan_potongan;
                //         }
                //         ppn =  count * 0.11;
                //         total = count + ppn;
                //     }else{
                //         if(cek_opsi_biaya == 'Tambahan'){
                //             total = get_sub_total + get_tambahan_potongan;
                //         }else{
                //             total = get_sub_total - get_tambahan_potongan;
                //         }

                //     }
                // console.log(ppn);
                // nominal_ppn.set(ppn);
                // total_harga.set(total);
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
