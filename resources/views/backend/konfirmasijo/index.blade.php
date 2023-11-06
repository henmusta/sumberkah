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
                                <a class="btn btn-primary " href="{{ route('backend.konfirmasijo.create') }}">
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
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label>Id Joborder<span class="text-danger">*</span></label>
                                                    <select id="select2Joborder" style="width: 100% !important;" name="joborder_id">

                                                    </select>
                                                  </div>
                                            </div>
                                            <div class="col-md-4 text-end" style="padding-top:30px;">
                                                <a id="terapkan_filter" class="btn btn-success">
                                                    Terapkan Filter
                                                    <i class="fas fa-align-justify"></i>
                                                </a>
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
                                <th class="text-center">Kode Joboorder</th>
                                <th>Tanggal Konfirmasi</th>
                                <th>Tanggal Muat</th>
                                <th>Tanggal Bongkar</th>
                                <th>Nopol</th>
                                <th>Nama Supir</th>
                                <th>Rute Awal</th>
                                <th>Rute Akhir</th>
                                <th>Biaya Lain</th>
                                <th>Satuan</th>
                                <th>Berat Muatan</th>
                                {{-- <th>Tipe Muatan</th> --}}
                                <th>Keterangan</th>
                                {{-- <th width="8%">Aksi</th> --}}
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
        let select2Joborder = $('#select2Joborder');
        let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.konfirmasijo.index') }}",
          data: function (d) {
            d.id = $('#select2Joborder').find(':selected').val();
          }
        },

        columns: [
          {data: 'kode_joborder', name: 'kode_joborder'},
          {data: 'tgl_konfirmasi', name: 'tgl_konfirmasi'},
          {data: 'tgl_muat', name: 'tgl_muat'},
          {data: 'tgl_bongkar', name: 'tgl_bongkar'},
          {data: 'joborder.mobil.nomor_plat', name: 'joborder.mobil.nomor_plat'},
          {data: 'joborder.driver.name', name: 'joborder.driver.name'},
          {data: 'joborder.ruteawal.name', name: 'joborder.ruteawal.name'},
          {data: 'joborder.ruteakhir.name', name: 'joborder.ruteakhir.name'},
          {data: 'konfirmasi_biaya_lain', name: 'konfirmasi_biaya_lain'},
          {data: 'joborder.rute.ritase_tonase', name: 'joborder.rute.ritase_tonase'},
          {data: 'berat_muatan', name: 'berat_muatan'},

          {data: 'keterangan_konfirmasi', name: 'keterangan_konfirmasi'},
        ],

        columnDefs: [
          {
            targets: [8],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          },
          {
            targets: [10],
            render: function (data, type, full, meta) {
                let dta = data % 1;
                let cek = (dta == 0 ) ? 0 : 3;
                return   $.fn.dataTable.render.number('.', ',', cek, '').display(data);
            }
          }
        ],
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



     $("#terapkan_filter").click(function() {
        dataTable.draw();
      });


    });
  </script>
@endsection
