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
                                <a class="btn btn-primary " href="{{ route('backend.penggajian.create') }}">
                                    Tambah
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                    <div class="card border shadow-none card-body text-muted mb-0">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Status Payment<span class="text-danger"></span></label>
                                                    <select id="select2StatusPayment" style="width: 100% !important;" name="status_Payment">
                                                        <option value=""></option>
                                                        <option value="0">Belum Bayar</option>
                                                        <option value="1">Cicilan</option>
                                                        <option value="2">Lunas</option>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Bulan Kerja<span class="text-danger"></span></label>
                                                    <input type="text" id="bulan_kerja" class="form-control datePicker" name="bulan_kerja" placeholder="Bulan_kerja" value=""/>
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Driver<span class="text-danger"></span></label>
                                                    <select id="select2Driver" style="width: 100% !important;" name="driver_id">
                                                    </select>
                                                  </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Nomor Plat Polisi<span class="text-danger"></span></label>
                                                    <select id="select2Mobil" style="width: 100% !important;" name="mobil_id">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>Nomor Slip Gaji<span class="text-danger"></span></label>
                                                    <select id="select2Gaji" style="width: 100% !important;" name="penggajian_id">
                                                        <option value="{{ $data['gaji']['id'] ?? '' }}"> {{$data['gaji']['kode_gaji'] ?? '' }}</option>
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
                                <th class="text-center">Kode Gaji</th>
                                <th>Tanggal Gaji</th>
                                <th>Driver</th>
                                <th>No Polisi</th>
                                <th>Bulan Kerja</th>
                                <th>Total Gaji</th>
                                <th>Sisa Gaji</th>
                                <th>Status</th>
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


@endsection

@section('css')
<style>

tr.group,
tr.group:hover {
    background-color: #22354eaa !important;
}

</style>
@endsection
@section('script')
<script src="https://cdn.datatables.net/rowgroup/1.0.2/js/dataTables.rowGroup.min.js"></script>

  <script>

     $(document).ready(function () {

        $('#tgl_awal, #tgl_akhir').flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true
        });


        $('#bulan_kerja').flatpickr({
                disableMobile: "true",
                allowInput: true,
                plugins: [
                    new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y-m",
                    // altFormat: "Y",
                    theme: "dark"
                    })
                ]
        });

        let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);

      let select2Driver = $('#select2Driver');
      let select2Mobil = $('#select2Mobil');
      let select2Gaji = $('#select2Gaji');
      let select2StatusPayment = $('#select2StatusPayment');

      select2StatusPayment.select2({
        dropdownParent:select2StatusPayment.parent(),
        searchInputPlaceholder: 'Cari Status Payment',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Status Payment',

      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });

      select2Gaji.select2({
        dropdownParent:  select2Gaji.parent(),
        searchInputPlaceholder: 'Cari Gaji',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih Gaji',
        ajax: {
          url: "{{ route('backend.penggajian.select2') }}",
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

      let dataTable = $('#Datatable').DataTable({
        buttons: [
            {
                extend: 'excel',
                footer: true,
                text: 'Excel',
                title: 'Laporan Gaji',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'pdfHtml5',
                footer: true,
                text: 'PDF',
                title: 'Laporan Gaji',
                pageSize: 'A4',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ,6, 7]
                },
                // customize : function(doc) {
                //     doc.styles['td:nth-child(2)'] = {
                //     width: '200px',
                //     'max-width': '200px'
                //     }
                // }
            },


        ],
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.penggajian.index') }}",
          data: function (d) {
            d.status_payment = $('#select2StatusPayment').find(':selected').val();
            d.driver_id = $('#select2Driver').find(':selected').val();
            d.mobil_id = $('#select2Mobil').find(':selected').val();
            d.bulan_kerja =  $('#bulan_kerja').val();
            d.id = $('#select2Gaji').find(':selected').val();
            d.tgl_awal = $('#tgl_awal').val();
            d.tgl_alhir = $('#tgl_akhir').val();
          }
        },

        columns: [
          {data: 'kode_gaji',   className: 'text-center', name: 'kode_gaji'},
          {data: 'tgl_gaji', name: 'tgl_gaji'},
          {data: 'driver.name', name: 'driver.name'},
          {data: 'mobil.nomor_plat', name: 'mobil.nomor_plat'},
          {data: 'bulan_kerja', name: 'bulan_kerja'},
          {data: 'total_gaji', name: 'total_gaji'},
          {data: 'sisa_gaji', name: 'sisa_gaji'},
          {data: 'status_payment', name: 'status_payment'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
        ],

        columnDefs: [

          {
            targets: [5, 6],
            className: 'text-end',
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          },


          {
            className: 'dt-center',
            targets: 7,
            render: function (data, type, full, meta) {
              let status = {
                0: {'title': 'Belum Bayar', 'class': ' bg-danger'},
                1: {'title': 'Belum Lunas', 'class': ' bg-warning'},
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
        ],
      });
      dataTable.buttons().container().appendTo($('#print'));
      $("#terapkan_filter").click(function() {
        dataTable.draw();
      });

      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.penggajian.index") }}/' + id);
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

    });
  </script>
@endsection
