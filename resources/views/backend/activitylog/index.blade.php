@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header mb-3">
                <h5 class="card-title mb-3">Table {{ $config['page_title'] }}</h5>
                <div class="col-xl-12">
                    <div class="mt-xl-0 mt-4">
                        <div class="d-flex align-items-start">


                            <div class="flex-grow-1">
                                <div class="col-xl-12">
                                    <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Filter</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- <input id="cek_driver_id" name="cek_gaji_id" type="hidden" value="{{$data['gaji']['id'] ?? ''}}"> --}}
                        <input id="cek_name" name="cek_name" type="hidden" value="{{$data['driver']['name'] ?? ''}}">
                        {{-- {{dd($data['mutasi'])}} --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                    <div class="card border shadow-none card-body text-muted mb-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Filter Tanggal</label>
                                                    <div class=" input-group mb-3">
                                                        <input type="text" id="tgl_awal" class="form-control datePicker"
                                                                placeholder="Tanggal Awal"  value="{{ \Carbon\Carbon::now()->startOfYear()->format('Y-m-d') }}"
                                                               />
                                                        <span class="input-group-text" id="basic-addon2">S/D</span>
                                                        <input type="text" id="tgl_akhir" class="form-control datePicker"
                                                                placeholder="Tanggal Akhir"  value="{{ \Carbon\Carbon::now()->lastOfYear()->format('Y-m-d') }}"
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
                                <th>Tanggal</th>
                                <th>Modul</th>
                                <th>Jenis</th>
                                <th width="100px">Aksi</th>
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


</style>
@endsection
@section('script')


  <script>

     $(document).ready(function () {
        $('#tgl_awal, #tgl_akhir').flatpickr({
            dateFormat: "Y-m-d"
         });





      let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
          url: "{{ route('backend.activitylog.index') }}",
          data: function (d) {
            // d.status =  select2Status.find(':selected').val();
            d.tgl_awal = $('#tgl_awal').val();
            d.tgl_akhir = $('#tgl_akhir').val();
          }
        },

        columns: [
          {data: 'created_at',  width: '150px', name: 'created_at'},
          {data: 'log_name', name: 'log_name'},
          {data: 'event', name: 'event'},
          {data: 'action', name: 'action',  className: 'text-center', orderable: false, searchable: false},
        ],
        columnDefs: [
            {
            className: 'dt-center',
            targets: 2,
            width: '75px',
            render: function (data, type, full, meta) {
              let status = {
                'updated': {'title': 'Updated', 'class': ' bg-warning'},
                'deleted': {'title': 'Deleted', 'class': ' bg-danger'},
              };
              if (typeof status[data] === 'undefined') {
                return data;
              }
              return '<span style="color:black" class="badge bg-pill' + status[data].class + '">' + status[data].title +
                '</span>';
            },
          },

        ],
      });
      $("#terapkan_filter").click(function() {
        dataTable.draw();
      });



    });
  </script>
@endsection
