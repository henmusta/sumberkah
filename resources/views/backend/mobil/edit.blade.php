@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
      <div class="card">
        <div class="row row-sm">
            <div class="col-12">
                <form id="formUpdate" action="{{ route('backend.mobil.update', Request::segment(3)) }}" autocomplete="off">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    @method('PUT')
                    <div id="errorEdit" class="mb-3" style="display:none;">
                        <div class="alert alert-danger" role="alert">
                          <div class="alert-text">
                          </div>
                        </div>
                      </div>
                    <div class="card-body">
                        <div class="col-xl-12">
                            <div class="mt-xl-0 mt-4">
                                <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Tipe Kendaraan</button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                            <div class="card border shadow-none card-body text-muted mb-0">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label for="select2Merk">Merek<span class="text-danger">*</span></label>
                                                            <select id="select2Merk" style="width: 100% !important;" name="merkmobil_id">
                                                                <option value="{{ $data['mobil']['merkmobil']['id'] }}"> {{$data['mobil']['merkmobil']['name'] }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label for="select2Tipe">Tipe<span class="text-danger">*</span></label>
                                                            <select id="select2Tipe" style="width: 100% !important;" name="tipemobil_id">
                                                                <option value="{{ $data['mobil']['tipemobil']['id'] }}"> {{$data['mobil']['tipemobil']['name'] }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label for="select2Jenis">Jenis<span class="text-danger">*</span></label>
                                                            <select id="select2Jenis" style="width: 100% !important;" name="jenismobil_id">
                                                                <option value="{{ $data['mobil']['jenismobil']['id'] }}"> {{$data['mobil']['jenismobil']['name'] }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label for="activeSelect">Dump <span class="text-danger">*</span></label>
                                                            <select class="form-select" id="select2Dump" name="dump">
                                                                <option value=""></option>
                                                                <option value="Iya"  {{ $data['mobil']['dump'] == 'Iya' ? 'selected' : NULL }}>Iya</option>
                                                                <option value="Tidak"  {{ $data['mobil']['dump'] == 'Tidak' ? 'selected' : NULL }}>Tidak</option>
                                                              </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12" style="padding-top:30px;">
                                                        <a style="margin:10px;" id="filter" class="btn btn-warning">
                                                            Filter Table
                                                            <i class="fas fa-align-justify"></i>
                                                        </a>
                                                        <a style="margin:10px;" id="refresh" class="btn btn-info">
                                                            Refresh Table
                                                            <i class="fas fa-align-justify"></i>
                                                        </a>
                                                        <a style="margin:10px;" id="get_tipe" class="btn btn-success">
                                                            Terapkan Tipe
                                                            <i class="fas fa-align-justify"></i>
                                                        </a>

                                                    </div>
                                                    <hr><hr>
                                                    <div class="col-12">
                                                        <label for="activeSelect">Tabel Tipe Kendaraan<span class="text-danger">*</span></label>
                                                        <div class="table-responsive">
                                                            <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                                                                <thead>
                                                                      <tr>
                                                                        <th width="5%">No</th>
                                                                        <th>Merek</th>
                                                                        <th>Tipe</th>
                                                                        <th>Jenis</th>
                                                                        <th>Dump</th>
                                                                      </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                      <div>
                        <h6 class="main-content-label mb-1">{{ $config['page_title'] ?? '' }}</h6>
                      </div><br>

                      <div class="d-flex flex-column">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Tipe Kendaraan Terpilih<span class="text-danger">*</span></label>
                                    <select id="mobilrincian_id" style="width: 100% !important;" name="mobilrincian_id" readonly>
                                        <option value="{{ $data['mobil']['mobilrincian_id'] }}"> {{$data['mobil']['merkmobil']['name']}}-{{$data['mobil']['tipemobil']['name']}}-{{$data['mobil']['jenismobil']['name']}}-{{$data['mobil']['dump']}}</option>
                                    </select>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Plat No Mobil<span class="text-danger">*</span></label>
                                    <input value="{{ $data['mobil']['nomor_plat'] ?? '' }}" type="text" id="nomor_plat" name="nomor_plat"  class="form-control" placeholder="Masukan Nomor Plat"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>No Rangka<span class="text-danger"></span></label>
                                    <input value="{{ $data['mobil']['nomor_rangka'] ?? '' }}" type="text" id="nomor_rangka" name="nomor_rangka"  class="form-control" placeholder="Masukan Nomor Rangka"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>No mesin<span class="text-danger"></span></label>
                                    <input value="{{ $data['mobil']['nomor_mesin'] ?? '' }}" type="text" id="nomor_mesin" name="nomor_mesin"  class="form-control" placeholder="Masukan Nomor Mesin"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Tahun<span class="text-danger">*</span></label>
                                    <input value="{{ $data['mobil']['tahun'] ?? '' }}" type="text" id="tahun" name="tahun"  class="form-control" placeholder="Masukan Tahun"/>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>No BPKB<span class="text-danger"></span></label>
                                    <input value="{{ $data['mobil']['nomor_bpkb'] ?? '' }}" type="text" id="nomor_bpkb" name="nomor_bpkb"  class="form-control" placeholder="Masukan Nomor Bpkb"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>No Stnk<span class="text-danger"></span></label>
                                    <input value="{{ $data['mobil']['nomor_stnk'] ?? '' }}" type="text" id="nomor_stnk" name="nomor_stnk"  class="form-control" placeholder="Masukan Nomor stnk"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Masa Berlaku Stnk<span class="text-danger">(1Th)*</span></label>
                                    <input value="{{ $data['mobil']['berlaku_stnk'] ?? '' }}" type="text" id="berlaku_stnk" name="berlaku_stnk"  class="form-control" placeholder="Masukan Masa Berlaku Stnk (1th)"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Masa Berlaku Stnk<span class="text-danger">(5Th)</span></label>
                                    <input value="{{ $data['mobil']['berlaku_pajak'] ?? '' }}" type="text" id="berlaku_pajak" name="berlaku_pajak"  class="form-control" placeholder="Masukan Masa Berlaku Stnk (5th)"/>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>No Ijin Usaha<span class="text-danger"></span></label>
                                    <input value="{{ $data['mobil']['nomor_ijin_usaha'] ?? '' }}" type="text" id="nomor_ijin_usaha" name="nomor_ijin_usaha"  class="form-control" placeholder="Masukan Nomor Ijin Usaha"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Masa Berlaku Ijin Usaha
                                        <span class="text-danger">*</span></label>
                                    <input value="{{ $data['mobil']['berlaku_ijin_usaha'] ?? '' }}" type="text" id="berlaku_ijin_usaha" name="berlaku_ijin_usaha"  class="form-control" placeholder="Masukan Tanggal Berlaku Ijin Usaha"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>No Kir<span class="text-danger"></span></label>
                                    <input value="{{ $data['mobil']['kir'] ?? '' }}" type="text" id="kir" name="kir"  class="form-control" placeholder="Masukan Nomor Kir"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Masa Berlaku Kir<span class="text-danger">*</span></label>
                                    <input value="{{ $data['mobil']['berlaku_kir'] ?? '' }}" type="text" id="berlaku_kir" name="berlaku_kir"  class="form-control" placeholder="Masukan Masa Berlaku Kir"/>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>No Ijin Bongkar Muat<span class="text-danger"></span></label>
                                    <input value="{{ $data['mobil']['nomor_ijin_bongkar'] ?? '' }}" type="text" id="nomor_ijin_bongkar" name="nomor_ijin_bongkar"  class="form-control" placeholder="Masukan Nomor Ijin bongkar"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Masa Berlaku Ijin Bongkar
                                    <span class="text-danger">*</span></label>
                                    <input value="{{ $data['mobil']['berlaku_ijin_bongkar'] ?? '' }}" type="text" id="berlaku_ijin_bongkar" name="berlaku_ijin_bongkar"  class="form-control" placeholder="Masukan Tanggal Berlaku Ijin bongkar"/>
                     </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Image Mobil<span class="text-danger"></span></label>
                                    <input  type="file" id="image_foto" name="image_mobil"  class="form-control" placeholder="Image Mobil"/>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Image Stnk<span class="text-danger"></span></label>
                                    <input type="file" id="image_stnk" name="image_stnk"  class="form-control" placeholder="Image Stnk"/>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Keterangan
                                        <span class="text-danger"></span></label>
                                    <textarea  id="keterangan" name="keterangan"  class="form-control" placeholder="Keterangan" rows="5">{{ $data['mobil']['keterangan_mobil'] ?? '' }}</textarea>
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
<link type="text/css" href="https://cdn.datatables.net/select/1.6.1/css/select.dataTables.min.css" rel="stylesheet" />

@endsection
@section('script')


<script type="text/javascript" src="https://cdn.datatables.net/select/1.6.1/js/dataTables.select.min.js"></script>

<script>
$(document).ready(function () {
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


    let select2Merk = $('#select2Merk');
    let select2Tipe = $('#select2Tipe');
    let select2Jenis = $('#select2Jenis');
    let select2Dump = $('#select2Dump');
    let mobilrincian = $('#mobilrincian_id');

    select2Dump.select2({
        dropdownParent: select2Dump.parent(),
        searchInputPlaceholder: 'Cari Dump',
        width: '100%',
        placeholder: 'select Dump',
    });


    mobilrincian.select2({
        dropdownParent: mobilrincian.parent(),
        searchInputPlaceholder: 'Cari Tipe Kendaraan',
        width: '100%',
        placeholder: 'select Tipe Kendaraan',
    });

    select2Merk.select2({
        dropdownParent: select2Merk.parent(),
        searchInputPlaceholder: 'Cari Merek Mobil',
        allowClear: true,
        width: '100%',
        placeholder: 'select Merek Mobil',
        ajax: {
          url: "{{ route('backend.merkmobil.select2') }}",
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
        searchInputPlaceholder: 'Cari Tipe Mobil',
        allowClear: true,
        width: '100%',
        placeholder: 'select Tipe Mobil',
        ajax: {
          url: "{{ route('backend.tipemobil.select2') }}",
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

      let dataTable = $('#Datatable').DataTable({
        select: {
            style: 'single'
        },
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        pageLength: 5,
        ajax: {
          url: "{{ route('backend.mobilrincian.datatablecekmobilrincian') }}",
          data: function (d) {
            // d.customer_id = $('#select2Customer').find(':selected').val();
            // d.create = true;
            d.merkmobil_id =select2Merk.find(':selected').val();
            d.tipemobil_id =select2Tipe.find(':selected').val();
            d.jenismobil_id =select2Jenis.find(':selected').val();
            d.dump = select2Dump.find(':selected').val();
          }
        },

        columns: [
          {
                data: "id", name:'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
          },
          {data: 'merkmobil.name', name: 'merkmobil.name'},
          {data: 'tipemobil.name', name: 'tipemobil.name'},
          {data: 'jenismobil.name', name: 'jenismobil.name'},
          {data: 'dump', name: 'dump'},

        //   {data: 'tgl_bongkar', name: 'jenis_payment'},
        //   {data: 'konfirmasi_biaya_lain', name: 'konfirmasi_biaya_lain'},
        //   {data: 'keterangan_konfirmasi', name: 'keterangan_konfirmasi'},
        ],

        columnDefs: [


        ],
      });


      $('#filter').on('click', function(e) {
         dataTable.draw();
      });

    $('#refresh').on('click', function(e) {
        select2Merk.empty().trigger('change');
        select2Tipe.empty().trigger('change');
        select2Jenis.empty().trigger('change');
        select2Dump.val('').trigger('change');
        mobilrincian.empty().trigger('change');
        $('#filter').click();
    });
    $('#get_tipe').on('click', function(e) {
        let gtdataTable = $('#Datatable').DataTable();
        var data =   gtdataTable.rows( { selected: true } ).data()[0];


        $.ajax({
                url: "{{ route('backend.mobilrincian.findmobilrincian') }}",
                type: 'GET',
                data: {id:  data.id},
                dataType: 'json', // added data type
                success: function(res) {
                    let data = res;
                    toastr.success('Data Telah Tersedia', 'Success !');
                    let optionListMerk = new Option(data.mobil.merkmobil.name, data.mobil.merkmobil.id, false, false);
                        select2Merk.empty().trigger('change');
                        select2Merk.append(optionListMerk).trigger('change');

                    let optionListTipe = new Option(data.mobil.tipemobil.name, data.mobil.tipemobil.id, false, false);
                        select2Tipe.empty().trigger('change');
                        select2Tipe.append(optionListTipe).trigger('change');

                    let optionListJenis = new Option(data.mobil.jenismobil.name, data.mobil.jenismobil.id, false, false);
                        select2Jenis.empty().trigger('change');
                        select2Jenis.append(optionListJenis).trigger('change');

                    select2Dump.val(data.mobil.dump).trigger('change');


                    $("input").prop('disabled', false);
                    $("textarea").prop('disabled', false);


                    let name_tipe_mobil = data.mobil.merkmobil.name +'-'+data.mobil.tipemobil.name +'-'+data.mobil.jenismobil.name+'-'+ select2Dump.val();
                    let optionListMobilRincian = new Option(name_tipe_mobil, data.mobil.id, false, false);
                        mobilrincian.empty().trigger('change');
                        mobilrincian.append(optionListMobilRincian).trigger('change');

                    // $("#mobilrincian_id").val(data.mobil.id);

                    // console.log(data);
                    // const kode_joborder =  data.kode_joborder;
                    // const JoinedKode = kode_joborder.join();
                    // console.log(JoinedKode);

                    // $('#kode_joborder').val(JoinedKode);
                    // sub_total.set(data.sum_total_harga);
                    // total_tonase.set(data.sum_harga);
                    // total_harga.set(data.sum_total_harga);
                    // $('#payment_hari').prop('disabled', false);
                    // toastr.success('Data Telah Tersedia', 'Success !');
                }
         });

        //  count_total();

    });


});

</script>
@endsection
