@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header -4 mb-3">
                <h5 class="card-title mb-3">Table {{ $config['page_title'] }}</h5>
                <div class="row">
                    <div class="col-md-6 d-flex gap-1 align-items-end mb-3">

                    </div>
                    <!-- Left toolbar -->
                    <div class="col-md-6 gap-1 text-align-webkit-right mb-3">
                        {{-- <a class="btn btn-primary " href="{{ route('backend.users.create') }}">
                            <i class="demo-psi-add fs-5"></i>
                            <span class="vr"></span>
                            Tambah
                        </a> --}}
                    </div>


                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="Datatable" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Logo</th>
                                <th>Sidebar Logo</th>
                                <th>Favicon</th>
                                <th>Aksi</th>
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


@endsection

@section('css')

@endsection
@section('script')

  <script>

     $(document).ready(function () {

        let dataTable = $('#Datatable').DataTable({
            responsive: true,
            scrollX: false,
            processing: true,
            serverSide: true,
            order: [[1, 'desc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            pageLength: 10,
            ajax: "{{ route('backend.settings.index') }}",
            columns: [
            {data: 'name', name: 'name'},
            {data: 'icon', name: 'icon'},
            {data: 'sidebar_logo', name: 'sidebar_logo'},
            {data: 'favicon', name: 'favicon'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            columnDefs: [

            ],
        });
    });


  </script>
@endsection
