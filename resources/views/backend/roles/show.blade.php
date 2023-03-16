@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">

                <!-- Invoice header -->
                <div class="d-flex border-bottom pb-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img class="img-sm img-fluid" src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="logo">
                        </div>
                        <div class="flex-grow-1 ms-1 h2 mb-0">{{Setting::get_setting()->name}}</div>
                    </div>
                    <div class="ms-auto">
                        <a href="javascript:window.print()" class="btn btn-light btn-icon"><i class="demo-pli-printer fs-5"></i></a>
                        <a href="#" class="btn btn-primary" onclick="window.history.back();">Cancel</a>
                    </div>
                </div>

                <div class="row" style="padding-top:10px;">
                    <div class="col-12">
                        <div class="bg-light p-3 rounded bg-opacity-50 mt-5">
                            <p class="h5">Role</p>
                            <p>{{ $data['role']['name'] ?? '' }}</p>
                        </div>
                    </div>

                </div>



                <!-- Invoice table -->
                <div class="table-responsive mt-4">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr class="">
                                <th class="text-left">Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['kegiatan'] as $val)
                           {{-- {{dd($val['roles'])}} --}}
                            <tr>
                              <td>{{$val['kegiatan']['name'] ?? '-'}}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>


                <!-- END : Footer information -->

            </div>
        </div>
    </div>
  </div>
@endsection

@section('css')
@endsection
@section('script')
  <script>
    $(document).ready(function () {
        // $('#tanggal_lahir').datepicker({ dateFormat: "yy-mm-dd" });
        $('#tanggal_lahir').flatpickr({
           dateFormat: "Y-m-d",
         });







    });
  </script>
@endsection
