@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mb-3">
                <h5 class="card-title mb-3">Table {{ $config['page_title'] }}</h5>

                <div class="col-md-xl-12">
                    <div class="mt-xl-0 mt-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Filter</button>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if(Auth::user()->can('backend-joborder-create') == 'true')
                                <a class="btn btn-primary " href="{{ route('backend.joborder.create') }}">
                                    Tambah
                                    <i class="fas fa-plus"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                    <div class="card border shadow-none card-body text-muted mb-0">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Status Joborder<span class="text-danger">*</span></label>
                                                    <select id="select2StatusJo" style="width: 100% !important;" name="status_joborder">
                                                        <option value=""></option>
                                                        <option value="0">ONGOING</option>
                                                        <option value="1">DONE</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Nomor Plat Polisi<span class="text-danger">*</span></label>
                                                    <select id="select2Mobil" style="width: 100% !important;" name="mobil_id">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Driver<span class="text-danger">*</span></label>
                                                    <select id="select2Driver" style="width: 100% !important;" name="driver_id">

                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Customer<span class="text-danger">*</span></label>
                                                    <select id="select2Customer" style="width: 100% !important;" name="customer_id">
                                                    </select>
                                                  </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Jenis Mobil<span class="text-danger">*</span></label>
                                                    <select id="select2Jenis" style="width: 100% !important;" name="jenismobil_id">

                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Id Joborder<span class="text-danger">*</span></label>
                                                    <select id="select2Joborder" style="width: 100% !important;" name="joborder_id">
                                                        <option value="{{ $data['joborder']['id'] ?? '' }}"> {{$data['joborder']['kode_joborder'] ?? '' }}</option>
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
                                                <a id="terapkan_filter" class="btn btn-success">
                                                    Terapkan Filter
                                                    <i class="fas fa-align-justify"></i>
                                                </a>
                                            </div>
                                            <div class="col-md-2 text-end" style="padding-top:30px;">
                                                <div id="print">

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
                <div class="table">
                    <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th width="10%" class="text-center">Id JO</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Driver</th>
                                <th width="8%">No Plat Polisi</th>
                                <th>Jenis Mobil</th>
                                <th>Customer</th>
                                <th>Muatan</th>
                                <th>Alamat Awal (Dari)</th>
                                <th>Alamat Akhir (Ke)</th>
                                <th width="8%">Total Uj</th>
                                <th>Pembayaran</th>
                                <th width="8%">Sisa Uj</th>
                                <th>Keterangan</th>
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

 <div class="modal fade" id="modalValidasi" tabindex="-1" aria-labelledby="modalmodalValidasi" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pembatalan Konfirmasi {{ $config['page_title'] }}</h5>
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
                <div class="modal-body">
                    <input type="hidden" name="id">
                   <p id="text_jo"></p>
                  </div>
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

      let modalValidasi = document.getElementById('modalValidasi');
      const bsValidasi = new bootstrap.Modal(modalValidasi);

      let select2StatusJo = $('#select2StatusJo');
      let select2Driver = $('#select2Driver');
      let select2Jenis = $('#select2Jenis');
      let select2Mobil = $('#select2Mobil');
      let select2Customer = $('#select2Customer');
      let select2Joborder = $('#select2Joborder');
     select2StatusJo.select2({
        dropdownParent: select2StatusJo.parent(),
        searchInputPlaceholder: 'Cari Status',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Status',

      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });



    select2Jenis.select2({
        dropdownParent: select2Jenis.parent(),
        searchInputPlaceholder: 'Cari Jenis Mobil',
        width: '100%',
        allowClear: true,
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
              jenismobil_id:  select2Jenis.find(":selected").val() || '',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            // select2Customer.empty().trigger('change');
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


    select2Customer.select2({
        dropdownParent: select2Customer.parent(),
        searchInputPlaceholder: 'Cari Customer',
        width: '100%',
        allowClear: true,
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
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);

      let dataTable = $('#Datatable').DataTable({
        dom: 'lfBrtip',
        buttons: [
            {
                extend: 'excel',
                footer: true,
                text: 'Excel',
                title: 'Laporan Joborder',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ,6, 7, 8, 9, 10 , 11, 12, 13]
                }
            },
            {
                extend: 'pdfHtml5',
                footer: true,
                text: 'PDF',
                title: 'Laporan Joborder',
                pageSize: 'A4',
                orientation : 'landscape',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ,6, 7, 8, 9, 10 , 11, 12, 13]
                },
                // customize : function(doc) {
                //     doc.styles['td:nth-child(2)'] = {
                //     width: '200px',
                //     'max-width': '200px'
                //     }
                // }
            }

        ],
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.joborder.index') }}",
          data: function (d) {
            // d.status = $('#Select2Status').find(':selected').val();
            d.status_joborder = $('#select2StatusJo').find(':selected').val();
            d.driver_id = $('#select2Driver').find(':selected').val();
            d.jenismobil_id = $('#select2Jenis').find(':selected').val();
            d.mobil_id = $('#select2Mobil').find(':selected').val();
            d.customer_id = $('#select2Customer').find(':selected').val();
            d.id = $('#select2Joborder').find(':selected').val();
            d.tgl_awal = $('#tgl_awal').val();
            d.tgl_alhir = $('#tgl_akhir').val();
          }
        },

        columns: [
        //   {
        //         data: "id", name:'id',
        //         render: function (data, type, row, meta) {
        //             return meta.row + meta.settings._iDisplayStart + 1;
        //         }
        //   },
          {data: 'kode_joborder', className:'text-center', name: 'kode_joborder'},
          {data: 'tgl_joborder', name: 'tgl_joborder'},
          {data: 'status_joborder', name: 'status_joborder'},
          {data: 'driver.name', name: 'driver.name'},
          {data: 'mobil.nomor_plat', name: 'mobil.nomor_plat'},
          {data: 'jenismobil.name', name: 'jenismobil.name'},
          {data: 'customer.name', name: 'customer.name'},
          {data: 'muatan.name', name: 'muatan.name'},
          {data: 'ruteawal.name', name: 'ruteawal.name'},
          {data: 'ruteakhir.name', name: 'ruteakhir.name'},
          {data: 'total_uang_jalan', name: 'total_uang_jalan'},
          {data: 'status_payment', name: 'status_payment'},
          {data: 'sisa_uang_jalan', name: 'sisa_uang_jalan'},
          {data: 'keterangan_joborder', name: 'keterangan_joborder'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
            {
                targets: [ 13 ],
                visible: false,
                searchable: false
            },
            {
            className: 'dt-center',
            targets: 2,
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'ONGOING', 'class': ' bg-warning'},
                1: {'title': 'DONE', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            },
          },
          {
            className: 'dt-center',
            targets: 11,
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'Belum Bayar', 'class': ' bg-danger'},
                1: {'title': 'Progress Payment', 'class': ' bg-warning'},
                2: {'title': 'Lunas', 'class': ' bg-success'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
            //   let url;
            //   if(data > 0){
            //     url = '';
            //   }else{
            //     url = '';
            //   }


              return '<a><span class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span></a>';
            },
          },
          {
            targets: [ 10,  12],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          }

        ],
      });

      dataTable.buttons().container().appendTo($('#print'));

      $("#terapkan_filter").click(function() {
        dataTable.draw();
      });


      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.joborder.index") }}/' + id);
      });
      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
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


      modalValidasi.addEventListener('show.bs.modal', function (event) {
        let id = event.relatedTarget.getAttribute('data-bs-id');
        let kode = event.relatedTarget.getAttribute('data-bs-kode');
        let invoice = event.relatedTarget.getAttribute('data-bs-invoice');
        let gaji = event.relatedTarget.getAttribute('data-bs-gaji');
        if(gaji != '' && invoice == ''){
            $('#text_jo').text('Peringatan Joborder Sudah Terkoneksi Dengan Penggajian Kode :'+gaji+'');
            $('#text_jo').addClass('text-danger');
        }else if(invoice != '' && gaji == ''){
            $('#text_jo').text('Peringatan Joborder Sudah Terkoneksi Dengan Invoice Kode : '+invoice+'');
            $('#text_jo').addClass('text-danger');
        }else if(invoice != '' && gaji != ''){
            $('#text_jo').text('Peringatan Joborder Sudah Terkoneksi Dengan Invoice Dan Penggajian Kode : '+invoice+' Dan '+ gaji +'');
            $('#text_jo').addClass('text-danger');
        }else{
            $('#text_jo').text('Apa Anda Yakin ingin Membatalkan Konfirmasi Joborder - Kode :'+kode+'');
            $('#text_jo').addClass('text-warning');
        }


        // $(this).find('#select2Validasi').val(validasi).trigger('change');
        this.querySelector('input[name=id]').value = id;
        this.querySelector('#formUpdateValidasi').setAttribute('action', '{{ route("backend.joborder.validasi") }}');
      });
      modalValidasi.addEventListener('hidden.bs.modal', function (event) {
        // $(this).find('#select2Validasi').val('').trigger('change');
        this.querySelector('input[name=id]').value = '';
        $('#text_jo').text('');
        $('#text_jo').removeClass();
        this.querySelector('#formUpdateValidasi').setAttribute('href', '');
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
              bsValidasi.hide();
            }
          },
          error: function (response) {
            bsValidasi.hide();
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            toastr.error(response.responseJSON.message, 'Gagal !');

          }
        });
      });


    });
  </script>
@endsection
