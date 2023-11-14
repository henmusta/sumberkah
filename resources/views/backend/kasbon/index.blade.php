@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div id="fluid" class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mb-3">
                <div class="card-header text-center">
                    <h2 class="">{{ $config['page_title'] }}</h2>
                </div>
                <button id="fullscreen-button" hidden="true"><i class="fas fa-expand"></i></button>
                <button id="compress-button"><i class="fas fa-compress"></i></button>
                <div class="col-md-xl-12">
                    <div class="mt-xl-0 mt-4">

                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Filter</button>
                            </div>
                        </div>
                        @if(Auth::user()->can('backend-kasbon-create') == 'true')
                        <div class="flex-shrink-0">
                            <a class="btn btn-primary " href="{{ route('backend.kasbon.create') }}">
                                Tambah
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                        @endif
                    </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                    <div class="card border shadow-none card-body text-muted mb-0">


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Driver<span class="text-danger">*</span></label>
                                                    <select id="select2Driver" style="width: 100% !important;" name="driver_id">
                                                    </select>
                                                  </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Status Transaksi<span class="text-danger">*</span></label>
                                                    <select id="select2Jenis" style="width: 100% !important;" name="Jenis">
                                                        <option value=""></option>
                                                        <option value="Pembayaran">Pembayaran</option>
                                                        <option value="Pengajuan">Pengajuan</option>
                                                        <option value="Potong Gaji">Potong Gaji</option>
                                                        <option value="Potong Joborder">Potong Joborder</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3"  hidden>
                                                <div class="mb-3">
                                                    <label>Status Validasi<span class="text-danger">*</span></label>
                                                    <select id="select2ValidasiFilter" style="width: 100% !important;" name="validasi">
                                                        <option value=""></option>
                                                        <option value="1">Acc</option>
                                                        <option value="0">Pending</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Nomor Bon<span class="text-danger">*</span></label>
                                                    <select id="select2Kasbon" style="width: 100% !important;" name="kasbon_id">
                                                        <option value="{{ $data['kasbon']['id'] ?? '' }}"> {{$data['kasbon']['kode_kasbon'] ?? '' }}</option>
                                                    </select>
                                                  </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label>Filter Tanggal</label>
                                                    <div class=" input-group mb-3">
                                                        <input type="text" id="tgl_awal" class="form-control datePicker"
                                                                placeholder="Tanggal Awal" value=""
                                                               />
                                                        <span class="input-group-text" id="basic-addon2">S/D</span>
                                                        <input type="text" id="tgl_akhir" class="form-control datePicker"
                                                                placeholder="Tanggal Akhir" value=""
                                                                />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-end" style="padding-top:30px;">
                                                <div class="d-flex justify-content-start">
                                                    <a id="terapkan_filter" class="btn btn-success">
                                                        Terapkan Filter
                                                        <i class="fas fa-align-justify"></i>
                                                    </a>
                                                    <button  class="btn btn-danger" onClick="window.location.reload();">Refresh</button>

                                                </div>
                                            </div>
                                            <div class="col-md-2 text-end" style="padding-top:30px;">
                                                <div id="print">
                                                    <div class="dt-buttons btn-group flex-wrap">
                                                        <button id="excel" class="btn btn-secondary buttons-excel buttons-html5"  tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                        <button class="btn btn-secondary buttons-pdf buttons-html5"  tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
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



            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">Tanggal Transaksi</th>
                                <th>Kode Kasbon</th>
                                <th>Driver</th>
                                <th>Kode Joborder</th>
                                <th>Kode Gaji</th>
                                <th>Transaksi</th>
                                <th>Nominal</th>
                                {{-- <th>Status</th> --}}
                                <th width="8%">Aksi</th>
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
 {{--Modal--}}



 <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDeleteLabel">Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @method('DELETE')
        <div class="modal-body">
          <a href="" class="urlDelete" type="hidden"></a>
          Apa anda yakin ingin menghapus data ini?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="formDelete" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>
{{--
  <div class="modal fade" id="modalValidasi" tabindex="-1" aria-labelledby="modalmodalValidasi" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit {{ $config['page_title'] }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formUpdateValidasi" action="#">
          @method('PUT')
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <div class="modal-body">
            <div id="errorEdit" class="mb-3" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="mb-3">
                <label>Validasi<span class="text-danger">*</span></label>
                <input type="hidden" name="id">
                <select class="form-select" id="select2Validasi" name="validasi">
                  <option value="1">Acc</option>
                  <option value="0">Pending</option>
                </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div> --}}

  <div class="modal fade" id="modalValidasi" tabindex="-1" aria-labelledby="modalmodalValidasi" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit {{ $config['page_title'] }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formUpdateValidasi" action="#">
          @method('PUT')
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <div class="modal-body">
            <div id="errorEdit" class="mb-3" style="display:none;">
              <div class="alert alert-danger" role="alert">
                <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                <div class="alert-text">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label>Validasi<span class="text-danger">*</span></label>
              <input type="hidden" name="id">
              <input type="hidden" name="nominal">
              <select class="form-select" id="select2Validasi" name="validasi">
                <option value="1">Acc</option>
                <option value="0">Pending</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@section('css')
<style>


</style>
@endsection
@section('script')


  <script>

     $(document).ready(function () {
        $('#tgl_awal, #tgl_akhir').flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true
         });
         let select2Validasi = $('#select2Validasi');
      let select2Driver = $('#select2Driver');
      let select2Jenis = $('#select2Jenis');
      let select2ValidasiFilter = $('#select2ValidasiFilter');
      let select2Kasbon = $('#select2Kasbon');


      select2ValidasiFilter.select2({
        dropdownParent:select2ValidasiFilter.parent(),
        searchInputPlaceholder: 'Cari Status Validasi',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Status Validasi',

      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });

      select2Jenis.select2({
        dropdownParent:select2Jenis.parent(),
        searchInputPlaceholder: 'Cari Jenis Transaksi',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Jenis Transaksi',

      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });

      select2Kasbon.select2({
        dropdownParent:  select2Kasbon.parent(),
        searchInputPlaceholder: 'Cari Kasbon',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Kasbon',
        ajax: {
          url: "{{ route('backend.kasbon.select2') }}",
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


    select2Driver.select2({
        dropdownParent:  select2Driver.parent(),
        searchInputPlaceholder: 'Cari Driver',
        width: '100%',
        allowClear: true,
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
            console.log(data.id);
    });



      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);
      let modalValidasi = document.getElementById('modalValidasi');
      const bsValidasi = new bootstrap.Modal(modalValidasi);
      let dataTable = $('#Datatable').DataTable({
        // responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.kasbon.index') }}",
          data: function (d) {
            d.jenis = $('#select2Jenis').find(':selected').val();
            d.driver_id = $('#select2Driver').find(':selected').val();
            d.validasi = $('#select2ValidasiFilter').val();
            d.id = $('#select2Kasbon').find(':selected').val();
            d.tgl_awal = $('#tgl_awal').val();
            d.tgl_akhir = $('#tgl_akhir').val();
          }
        },

        columns: [
        //   {
        //         data: "id", name:'id',
        //         render: function (data, type, row, meta) {
        //             return meta.row + meta.settings._iDisplayStart + 1;
        //         }
        //   },
          {data: 'tgl_kasbon', name: 'tgl_kasbon'},
          {data: 'kode_kasbon', name: 'kode_kasbon'},
          {data: 'driver.name', name: 'driver.name'},
        //   {data: 'joborder.kode_joborder', name: 'joborder.kode_joborder'},
          {
               data: "joborder.kode_joborder", name:'joborder.kode_joborder',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                let kode = '-';
                if(row.joborder !== null){
                    kode = '<a target="_blank" href="{{ route('backend.joborder.index') }}?joborder_id='+row.joborder.id+'">'+data+'</a>';
                }
                   return kode;
               }
          },
          {
               data: "penggajian.kode_gaji", name:'penggajian.kode_gaji',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                let kode = '-';
                if(row.penggajian !== null){
                    kode = '<a target="_blank" href="{{ route('backend.penggajian.index') }}?penggajian_id='+row.penggajian.id+'">'+data+'</a>';
                }
                   return kode;
               }
          },
          {data: 'jenis', name: 'jenis'},
          {data: 'nominal', name: 'nominal'},
        //   {data: 'validasi', name: 'validasi'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
        //     {
        //     className: 'dt-center',
        //     targets: 7,
        //     render: function (data, type, full, meta) {
        //       let status = {
        //         0: {'title': 'Pending', 'class': ' bg-warning'},
        //         1: {'title': 'Acc', 'class': ' bg-success'},
        //       };
        //       if (typeof status[data] === 'undefined') {
        //         return data;
        //       }
        //       return '<span class="badge bg-pill' + status[data].class + '">' + status[data].title +
        //         '</span>';
        //     },
        //   },
          {
            className: 'dt-center',
            targets: 5,
            render: function (data, type, full, meta) {
              let status = {
                'Pengajuan': {'title': 'Pengajuan', 'class': ' bg-warning'},
                'Potong Gaji': {'title': 'Potong Gaji', 'class': ' bg-info'},
                'Potong Joborder': {'title': 'Potong Joborder', 'class': ' bg-primary'},
                'Pembayaran': {'title': 'Pembayaran', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            },
          },
          {
            targets: [6],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          },
          {
            targets:'_all',
            defaultContent: "-",
          },
        ],
      });
      $("#terapkan_filter").click(function() {
        dataTable.draw();
      });

        select2Validasi.select2({
            dropdownParent: select2Validasi.parent(),
            searchInputPlaceholder: 'Cari Validasi',
            width: '100%',
            placeholder: 'Pilih Validasi',
        });

        dataTable.buttons().container().appendTo($('#print'));
      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.kasbon.index") }}/' + id);
      });
      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });

      modalValidasi.addEventListener('show.bs.modal', function (event) {
        let id = event.relatedTarget.getAttribute('data-bs-id');
        let nominal = event.relatedTarget.getAttribute('data-bs-nominal');
        let validasi = event.relatedTarget.getAttribute('data-bs-validasi');
        $(this).find('#select2Validasi').val(validasi).trigger('change');
        this.querySelector('input[name=id]').value = id;
        this.querySelector('input[name=nominal]').value = nominal;
        this.querySelector('#formUpdateValidasi').setAttribute('action', '{{ route("backend.kasbon.validasi") }}');
      });
      modalValidasi.addEventListener('hidden.bs.modal', function (event) {
        $(this).find('#select2Validasi').val('').trigger('change');
        this.querySelector('input[name=id]').value = '';
        this.querySelector('input[name=nominal]').value = '';
        this.querySelector('#formUpdateValidasi').setAttribute('href', '');
      });


      $("#formDelete").click(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = modalDelete.querySelector('.urlDelete').getAttribute('href');
        let btnHtml = form.html();
        let spinner = $("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span>");
        $.ajax({
          beforeSend: function () {
            form.text(' Loading. . .').prepend(spinner).prop("disabled", "disabled");
          },
          type: 'DELETE',
          url: url,
          dataType: 'json',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success: function (response) {
            toastr.success(response.message, 'Success !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            dataTable.draw();
            bsDelete.hide();
          },
          error: function (response) {
            toastr.error(response.responseJSON.message, 'Failed !');
            form.text('Submit').html(btnHtml).removeAttr('disabled');
            bsDelete.hide();
          }
        });
      });

      $("#excel").click(function() {

            let params = new URLSearchParams({
                jenis : $('#select2Jenis').find(':selected').val() || '',
                driver_id : $('#select2Driver').find(':selected').val() || '',
                validasi : $('#select2ValidasiFilter').find(':selected').val() || '',
                id : $('#select2Kasbon').find(':selected').val() || '',
                tgl_awal : $('#tgl_awal').val() || '',
                tgl_akhir : $('#tgl_akhir').val() || '',
            });

            window.location.href = "{{ route('backend.kasbon.excel') }}?" +params.toString()
     });

     $("#pdf").click(function() {

        let params = new URLSearchParams({
            jenis : $('#select2Jenis').find(':selected').val() || '',
            driver_id : $('#select2Driver').find(':selected').val() || '',
            validasi : $('#select2ValidasiFilter').find(':selected').val() || '',
            id : $('#select2Kasbon').find(':selected').val() || '',
            tgl_awal : $('#tgl_awal').val() || '',
            tgl_akhir : $('#tgl_akhir').val() || '',
        });

        let url = "{{ route('backend.kasbon.pdf') }}?" +params.toString()
        window.open(url, '_blank');
     });


      $("#formUpdateValidasi").submit(function(e){
        e.preventDefault();
        let form 	= $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url 	= form.attr("action");
        let data 	= new FormData(this);
        $.ajax({
          beforeSend:function() {
            btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url : url,
          data : data,
          success: function (response) {
            let errorEdit = $('#errorEdit');
            errorEdit.css('display', 'none');
            errorEdit.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              dataTable.draw();
              bsValidasi.hide();
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
