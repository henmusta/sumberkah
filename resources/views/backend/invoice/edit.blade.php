@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <form id="formUpdate" action="{{ route('backend.invoice.update', Request::segment(3)) }}" autocomplete="off">
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
                                <input id="invoice_id" name="invoice_id" type="hidden" value="{{$data['invoice']['id']}}">
                                    <div class="mb-3">
                                       <label>Customer<span class="text-danger">*</span></label>
                                       <select id="select2Customer" style="width: 100% !important;" name="customer_id">
                                           <option value="{{ $data['invoice']['customer']['id'] }}"> {{$data['invoice']['customer']['name'] }}</option>
                                       </select>
                                     </div>
                              </div>

                                <div class="col-md-6">
                                     <div class="mb-3">
                                        <label>Tanggal Invoice<span class="text-danger">*</span></label>
                                        <input type="text" id="tgl_invoice" value="{{ $data['invoice']['tgl_invoice'] }}" name="tgl_invoice"   class="form-control"/>
                                      </div>
                                </div>
                          </div>


                          <div class="row">
                              <div class="col-md-6">
                                  <div class="mb-3">
                                    <label>Payment (Hari)<span class="text-danger">*</span></label>
                                    <input type="text" id="payment_hari" value="{{ $data['invoice']['payment_hari'] }}" name="payment_hari"  class="form-control" readonly/>
                                  </div>
                                  <div class="mb-3">
                                    <label for="activeSelect">Pilih Tambahan / Potongan<span class="text-danger">*</span></label>
                                    <select class="form-select" id="select2TambahanPotongan" name="tambahan_potongan">
                                      <option value="None" {{ $data['invoice']['tambahan_potongan'] == 'None' ? 'selected' : NULL }}>None</option>
                                      <option value="Tambahan" {{ $data['invoice']['tambahan_potongan'] == 'Tambahan' ? 'selected' : NULL }}>Tambahan</option>
                                      <option value="Potongan" {{ $data['invoice']['tambahan_potongan'] == 'Potongan' ? 'selected' : NULL }}>Potongan</option>
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label>Nominal Potongan Tambahan<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ $data['invoice']['nominal_tambahan_potongan'] }}" id="nominal_tambahan_potongan"  name="nominal_tambahan_potongan"  class="form-control" disabled/>
                                  </div>
                                  <div class="mb-3">
                                    <label>Total Tonase<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ $data['invoice']['total_tonase'] }}" id="total_tonase"  name="total_tonase"  class="form-control" readonly/>
                                  </div>

                                  <div class="mb-3">
                                    <label>Keterangan<span class="text-danger"></span></label>
                                    <textarea type="text" id="keterangan_invoice" value="" name="keterangan_invoice"  class="form-control">{{ $data['invoice']['keterangan_invoice'] }}</textarea>
                                  </div>
                              </div>

                                <div class="col-md-6">
                                      <div class="mb-3">
                                        <label>Sub Total<span class="text-danger">*</span></label>
                                        <input type="text" id="sub_total" value="{{ $data['invoice']['sub_total'] }}" name="sub_total" readonly  class="form-control"/>
                                      </div>
                                      <div class="mb-3">
                                        <label for="activeSelect">Pilih PPN<span class="text-danger">*</span></label>
                                        <select class="form-select" id="select2Ppn" name="ppn">
                                            <option value="Tidak" {{ $data['invoice']['Tidak'] == 0 ? 'selected' : NULL }}>Tidak</option>
                                          <option value="Iya" {{ $data['invoice']['Iya'] == 0 ? 'selected' : NULL }}>Iya</option>

                                        </select>
                                      </div>
                                      <div class="mb-3">
                                        <label>PPN 11%<span class="text-danger">*</span></label>
                                        <input type="text" id="nominal_ppn" value="{{ $data['invoice']['nominal_ppn'] }}" name="nominal_ppn"  class="form-control" readonly/>
                                      </div>
                                      <div class="mb-3">
                                        <label>Grand Total<span class="text-danger">*</span></label>
                                        <input type="text" id="total_harga" value="{{ $data['invoice']['total_harga'] }}" name="total_harga"  class="form-control" readonly/>
                                      </div>

                                      {{-- <div class="mb-3">
                                        <label>Kode Joborder<span class="text-danger">*</span></label>
                                        <textarea type="text" id="kode_joborder" value="" name="kode_joborder"  class="form-control" readonly></textarea>
                                      </div> --}}
                                </div>
                          </div>
                    </div>




                    <div  class="card-footer">
                        <div class="col-md-xl-12">
                            <div class="mt-xl-0 mt-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Filter</button>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
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
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="multi-collapse collapse" id="multiCollapseExample2" style="">
                                            <div class="card border shadow-none card-body text-muted mb-0">

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label>Id Joborder<span class="text-danger">*</span></label>
                                                            <select id="select2Joborder" style="width: 100% !important;" name="joborder_id" name="joborder_id[]" multiple="multiple">

                                                            </select>
                                                          </div>

                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label for="activeSelect">Ritase/Tonase/Kilogram <span class="text-danger">*</span></label>
                                                            <select class="form-select" id="select2Tipe" name="tipe">
                                                              <option value=""></option>
                                                              <option value="Ritase">Ritase</option>
                                                              <option value="Tonase">Tonase</option>
                                                              <option value="Kilogram">Kilogram</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label>Nomor Plat Polisi<span class="text-danger">*</span></label>
                                                            <select id="select2Mobil" style="width: 100% !important;" name="mobil_id">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label>Muatan<span class="text-danger">*</span></label>
                                                            <select id="select2Muatan" style="width: 100% !important;" name="muatan_id">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label>Tanggal Muat</label>
                                                            <div class=" input-group mb-3">
                                                                <input type="text" id="tgl_awal_muat" class="form-control datePicker"
                                                                        placeholder="Tanggal Awal" value=""
                                                                       />
                                                                <span class="input-group-text" id="basic-addon2">S/D</span>
                                                                <input type="text" id="tgl_akhir_muat" class="form-control datePicker"
                                                                        placeholder="Tanggal Akhir" value=""
                                                                        />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label>Tanggal Bongkar</label>
                                                            <div class=" input-group mb-3">
                                                                <input type="text" id="tgl_awal_bongkar" class="form-control datePicker"
                                                                        placeholder="Tanggal Awal" value=""
                                                                       />
                                                                <span class="input-group-text" id="basic-addon2">S/D</span>
                                                                <input type="text" id="tgl_akhir_bongkar" class="form-control datePicker"
                                                                        placeholder="Tanggal Akhir" value=""
                                                                        />
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label>Rute Awal<span class="text-danger">*</span></label>
                                                            <select id="select2Firstrute" style="width: 100% !important;" name="first_rute_id">
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label>Rute Akhir<span class="text-danger">*</span></label>
                                                            <select id="select2Lastrute" style="width: 100% !important;" name="last_rute_id">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 text-end" style="padding-top:30px;">
                                                        <div class="d-flex justify-content-start">
                                                            {{-- <a id="terapkan_filter" class="btn btn-success">
                                                                Terapkan Filter
                                                                <i class="fas fa-align-justify"></i>
                                                            </a> --}}
                                                            <a  class="btn btn-danger" id="reset">Refresh</a>
                                                        </div>

                                                    </div>

                                                </div>








                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-xl-12">
                            <div class="mb-3">
                                <label>Kode Joborder<span class="text-danger">*</span></label>
                                <textarea type="text" id="kode_joborder" value="" name="kode_joborder"  class="form-control" readonly></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
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
                                        <th>Harga</th>
                                        <th>Total Harga</th>
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

    $('#tgl_awal_muat, #tgl_akhir_muat, #tgl_awal_bongkar, #tgl_akhir_bongkar').flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true
         });

        let select2Mobil = $('#select2Mobil');
        let select2Joborder = $('#select2Joborder');

        let select2Firstrute = $('#select2Firstrute');
        let select2Lastrute = $('#select2Lastrute');
        let select2Muatan = $('#select2Muatan');
        let select2Tipe = $('#select2Tipe');

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





    select2Tipe.select2({
        dropdownParent: select2Tipe.parent(),
        searchInputPlaceholder: 'Cari Tipe',
        width: '100%',
        allowClear: true,
        placeholder: 'select Tipe',

      }).on('select2:select', function (e) {
            let data = e.params.data;
       //     select2Mobil.empty().trigger('change');
    });


    select2Mobil.select2({
        dropdownParent:   select2Mobil.parent(),
        searchInputPlaceholder: 'Cari Mobil',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Mobil',
        ajax: {
          url: "{{ route('backend.mobil.select2') }}",
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
            // select2Customer.empty().trigger('change');
    });

    select2Joborder.select2({
        dropdownParent:  select2Joborder.parent(),
        searchInputPlaceholder: 'Cari Job Order',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Job Order',
        ajax: {
          url: "{{ route('backend.joborder.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
                // konfirmasi_joborder: 2,
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data);
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

    const total_tonase = $('#total_tonase').val();

    const   nominal_ppn =new AutoNumeric('#nominal_ppn',currenciesOptions),
            tambahan_potongan =new AutoNumeric('#nominal_tambahan_potongan',currenciesOptions),
            // total_tonase =new AutoNumeric('#total_tonase',currenciesOptions),
            sub_total =new AutoNumeric('#sub_total',currenciesOptions),
            total_harga = new AutoNumeric('#total_harga',currenciesOptions);



    $('#tgl_invoice').flatpickr({
       dateFormat: "Y-m-d"
    });

    let select2Customer = $('#select2Customer');
    let select2Ppn = $('#select2Ppn');
    let select2TambahanPotongan = $('#select2TambahanPotongan');

//     select2TambahanPotongan.on('change', function (e) {
//     // var optionSelected = $("option:selected", this);

//     tambahan_potongan.set(0);
//     count_total();
// });

    select2TambahanPotongan.select2({
        dropdownParent: select2TambahanPotongan.parent(),
        searchInputPlaceholder: 'Cari Potongan Tambahan',
        width: '100%',
        placeholder: 'Pilih Potongan Tambahan',
      }).on('select2:select', function (e) {
            let data = e.params.data;
      });

    var cekvalueSelected = select2TambahanPotongan.val();
    if(cekvalueSelected != 'None'){
        console.log(cekvalueSelected);
        $('#nominal_tambahan_potongan').prop("disabled", false);
    }else{
        console.log(cekvalueSelected);
        $('#nominal_tambahan_potongan').prop('disabled', true);
    }

      select2Ppn.select2({
        dropdownParent: select2Ppn.parent(),
        searchInputPlaceholder: 'Cari Pilihan Ppn',
        width: '100%',
        placeholder: 'Pilih Ppn',
      }).on('select2:select', function (e) {
            // let data = e.params.data;
            // console.log(data.id);
            // if(data)
      });



    select2Customer.select2({
        dropdownParent: select2Customer.parent(),
        searchInputPlaceholder: 'Cari Customer',
        width: '100%',
        placeholder: 'Pilih Customer'
      });


     function get_jo(id){
         $.ajax({
                url: "{{ route('backend.joborder.findjoborder') }}",
                type: 'GET',
                data: {customer_id:  id},
                dataType: 'json', // added data type
                success: function(res) {
                    let data = res;
                    console.log(data);
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
     }


    let dataTable = $('#Datatable').DataTable({
        select: {
            style: 'os',
            selector: 'td:not(:last-child)' // no row selection on last column
        },
        responsive: true,
        scrollX: false,
        processing: true,
        searching: false,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.konfirmasijo.datatablecekjo') }}",
          data: function (d) {
            d.customer_id = $('#select2Customer').find(':selected').val();
            d.invoice_id = $('#invoice_id').val();
            d.tipe = $('#select2Tipe').find(':selected').val();
            d.mobil_id = $('#select2Mobil').find(':selected').val();
            d.muatan_id = $('#select2Muatan').find(':selected').val();
            d.id = $('#select2Joborder').val();
            d.rute_awal = $('#select2Firstrute').find(':selected').val();
            d.rute_akhir = $('#select2Lastrute').find(':selected').val();
            d.tgl_awal_muat = $('#tgl_awal_muat').val();
            d.tgl_akhir_muat = $('#tgl_akhir_muat').val();
            d.tgl_awal_bongkar = $('#tgl_awal_bongkar').val();
            d.tgl_akhir_bongkar = $('#tgl_akhir_bongkar').val();
            d.kode_joborder = $('#kode_joborder').val();
          }
        },

        columns: [
        //   {data: 'id', className: 'text-center', name: 'id',orderable: false, searchable: false,},
          {
                data:   "konfirmasi_id",
                orderable: false, searchable: false,
                render: function ( data, type, row ) {
                    if ( type === 'display' ) {
                        return '<input type="checkbox" value="'+ data +'" class="editor-active">';
                    }
                    return data;
                },
                className: "dt-body-center"
          },
          {data: 'kode_joborder', name: 'kode_joborder'},
          {data: 'tgl_muat', name: 'tgl_muat'},
          {data: 'tgl_bongkar', name: 'tgl_bongkar'},
          {data: 'mobil.nomor_plat', name: 'mobil.nomor_plat'},
          {data: 'muatan.name', name: 'muatan.name'},
          {data: 'ruteawal.name', name: 'ruteawal.name'},
          {data: 'ruteakhir.name', name: 'ruteakhir.name'},
          {data: 'berat_muatan', name: 'berat_muatan'},
          {data: 'rute.harga', name: 'rute.harga'},
          {data: 'total_harga', name: 'total_harga'},

        //   {data: 'tgl_bongkar', name: 'jenis_payment'},
        //   {data: 'konfirmasi_biaya_lain', name: 'konfirmasi_biaya_lain'},
        //   {data: 'keterangan_konfirmasi', name: 'keterangan_konfirmasi'},
        ],

        columnDefs: [
          {
            targets: [8, 9, 10],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          },
        //   {
        //     targets: 0,
        //     orderable: false, searchable: false,
        //     render: function(data, type, row, meta){
        //         var checkbox = $("<input/>",{
        //             "type": "checkbox"
        //         });
        //         // console.log(row.status);
        //         if(row.status === "1"){
        //             checkbox.attr("checked", "checked");
        //             checkbox.addClass("dt-checkboxes");
        //         }else{
        //             checkbox.addClass("dt-checkboxes");
        //         }
        //         return checkbox.prop("outerHTML")
        //     },
        //     data: 0,
        //     checkboxes: {
        //         selectRow: true
        //     },



        //   }
        ],
        rowCallback: function ( row, data ) {
            $('input.editor-active', row).prop( 'checked', data.status_konfirmasi == 1 );
            let joborder = JSON.parse("[" + $('#kode_joborder').val() + "]");
            if( joborder != undefined || joborder.length > 0){
                joborder.forEach(function(joborder) {
                    if(joborder == data.kode_joborder){
                        console.log(data.kode_joborder);
                        console.log(joborder);
                        $('input.editor-active', row).prop( 'checked', data.kode_joborder == joborder);
                    }
                });
            }
        }
      });


      $("#reset").click(function() {
        $("#select2Mobil, #select2Joborder, #select2Firstrute, #select2Lastrute #select2Muatan").empty().trigger('change');
        $("#select2Tipe, #tgl_awal_muat, #tgl_akhir_muat, #tgl_awal_bongkar, #tgl_akhir_bongkar").val('');

      });



        $("#select2Mobil, #select2Joborder, #select2Firstrute, #select2Lastrute #select2Muatan, #select2Tipe, #tgl_awal_muat, #tgl_akhir_muat, #tgl_awal_bongkar, #tgl_akhir_bongkar").on('change', function (e) {
            dataTable.draw();
        });

      $('#get_jo').on('click', function(e) {

        var oTable = $('#Datatable').dataTable();
        var rowcollection =  oTable.$(".editor-active:checked", {"page": "all"});
        var row_id = [];
        var form = this;
        $("input[name^=konfirmasijoid]").remove();
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
        $.ajax({
                url: "{{ route('backend.konfirmasijo.findkonfirmasijo') }}",
                type: 'GET',
                data: {konfirmasijo_id:  row_id},
                dataType: 'json', // added data type
                success: function(res) {
                    let data = res;
                    console.log(data);
                    const kode_joborder =  data.kode_joborder;
                    const JoinedKode = kode_joborder.join();
                    console.log(JoinedKode);

                    $('#kode_joborder').val(JoinedKode);
                    sub_total.set(data.sum_total_harga);
                    $('#total_tonase').val(data.sum_beratmuatan);
                    total_harga.set(data.sum_total_harga);
                    // $('#payment_hari').prop('disabled', false);
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

select2TambahanPotongan.on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    if(valueSelected != 'None'){
        console.log(valueSelected);
        $('#nominal_tambahan_potongan').prop("disabled", false);
    }else{
        console.log(valueSelected);
        $('#nominal_tambahan_potongan').prop('disabled', true);
    }
    tambahan_potongan.set(0);
    count_total();
});


select2Ppn.on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    if(valueSelected != 'None'){
        console.log(valueSelected);
        $('#nominal_ppn').prop("disabled", false);
    }else{
        console.log(valueSelected);
        $('#nominal_ppn').prop('disabled', true);

    }
    nominal_ppn.set(0);
    count_total();
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

        $('#select2Customer').on('change', function (e) {
             dataTable.draw();
             $('#payment_hari').val('');
             $('#select2TambahanPotongan').val('None').trigger('change');
             $('#nominal_tambahan_potongan').val('');
             $('#total_tonase').val('');
             $('#keterangan_invoice').val('');
             $('#sub_total').val('');
             $('#select2Ppn').val('None').trigger('change');
             $('#nominal_ppn').val('');
             $('#total_harga').val('');
             $('#kode_joborder').val('');
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
