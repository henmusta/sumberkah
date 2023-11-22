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
                                            <div id="bulan" class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="select2Bulan">Bulan<span class="text-danger"></span></label>
                                                    <select style="width: 100% !important;" id="select2Bulan" class="js-example-basic-multiple" name="bulan" multiple="multiple">
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="tahun" class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="select2Bulan">Tahun<span class="text-danger"></span></label>
                                                    <select style="width: 100% !important;" id="select2Tahun" class="js-example-basic-multiple" name="tahun">
                                                        <option value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">{{ \Carbon\Carbon::now()->format('Y') }}</option>
                                                    </select>
                                                </div>
                                            </div>
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

 <input type="hidden" id="report-url" value="{{ route('backend.bulanangaji.getreport') }}">
 <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<style>


</style>
@endsection
@section('script')


  <script>

     $(document).ready(function () {

        let select2Bulan = $('#select2Bulan');
     select2Bulan.select2({
        dropdownParent:select2Bulan.parent(),
        searchInputPlaceholder: 'Cari',
        width: '100%',
        placeholder: 'select bulan',
        ajax: {
          url: "{{ route('datepicker.index') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              type : 'bulan',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });

      let select2Tahun = $('#select2Tahun');
     select2Tahun.select2({
        dropdownParent:select2Tahun.parent(),
        searchInputPlaceholder: 'Cari',
        width: '100%',
        placeholder: 'select Tahun',
        ajax: {
          url: "{{ route('datepicker.index') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              type : 'tahun',
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });




        $("#terapkan_filter").click(function() {
            var url = document.getElementById("report-url").value;
            if( $('#select2Bulan').val() == null){
                toastr.error('Pilihan Bulan Wajib Diisi', 'Notif !');
            }else{
                var data = {
                tahun : $('#select2Tahun').val() || '',
                bulan : $('#select2Bulan').val() || '',
            };
            console.log(data);
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
                    console.log(res);
                    $("#report").html(res);
                    $("#cetak").prop('hidden', false);
                }
            });
            }

        });

        $("#pdf").click(function() {
                        let params = new URLSearchParams({
                            tahun : $('#select2Tahun').val() || '',
                            bulan : $('#select2Bulan').val() || '',
                        });

                        let url =  "{{ route('backend.bulanangaji.pdf') }}?" +params.toString();
                        window.open(url, '_blank');
        });

        $("#excel").click(function() {
                        let params = new URLSearchParams({
                            tahun : $('#select2Tahun').val() || '',
                            bulan : $('#select2Bulan').val() || '',
                        });

                        let url =  "{{ route('backend.bulanangaji.excel') }}?" +params.toString();
                        window.open(url, '_blank');
        });


     });


  </script>
@endsection
