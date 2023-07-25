@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mb-3">
                <h5 class="card-title mb-3">{{ $config['page_title'] }}</h5>
                <div class="col-md-xl-12">
                    <div class="mt-xl-0 mt-4">

                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Filter</button>
                            </div>
                        </div>
                    </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                    <div class="card border shadow-none card-body text-muted mb-0">


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

                                            {{-- <div class="col-md-2 text-end" style="padding-top:30px;">
                                                <revi id="print">
                                                    <button id="pdf" class="btn btn-secondary buttons-excel buttons-html5"  tabindex="0" aria-controls="Datatable" type="button"><span>pdf</span></button>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-2 text-end" style="padding-top:30px;">
                                                <a id="terapkan_filter" class="btn btn-success">
                                                    Preview
                                                    <i class="fas fa-align-justify"></i>
                                                </a>
                                            </div>
                                            <div id="cetak" class="col-md-2 text-end" style="padding-top:30px;" hidden="true">
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
            <div class="card-body" id="report">


            </div>
        </div>
    </div>
</div>
 {{--Modal--}}

 <input type="hidden" id="report-url" value="{{ route('backend.rptjo.getreport') }}">
 <meta name="csrf-token" content="{{ csrf_token() }}" />
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

        $("#terapkan_filter").click(function() {
            var url = document.getElementById("report-url").value;
            var data = {
                tgl_awal : $('#tgl_awal').val() || '',
                tgl_akhir : $('#tgl_akhir').val() || '',
            };
            let btnSubmit = $("#terapkan_filter");
            let btnSubmitHtml = btnSubmit.html();
            $.ajax({
                beforeSend: function () {
                    btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
                },
                cache: false,
                processData: false,
                contentType: 'application/json',
                type: "POST",
                url: url,
                data: JSON.stringify(data),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
                    $("#report").html(res);
                    $("#cetak").prop('hidden', false);
                }
            });
        });

        $("#pdf").click(function() {
                        let params = new URLSearchParams({
                            tgl_awal : $('#tgl_awal').val() || '',
                            tgl_akhir : $('#tgl_akhir').val() || '',
                        });

                        let url =  "{{ route('backend.rptjo.pdf') }}?" +params.toString();
                        window.open(url, '_blank');
        });

        $("#excel").click(function() {
                        let params = new URLSearchParams({
                            tgl_awal : $('#tgl_awal').val() || '',
                            tgl_akhir : $('#tgl_akhir').val() || '',
                        });

                        window.location.href = "{{ route('backend.rptjo.excel') }}?" +params.toString()
        });


     });


  </script>
@endsection
