@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div id="fluid" class="container-fluid full">
        {{-- <h5 class="card-title mb-3">{{ $config['page_title'] }}</h5> --}}
        <div class="card">
            <div class="card-header text-center">
                <h2 class="">{{ $config['page_title'] }}</h2>
            </div>
            <button id="fullscreen-button" hidden="true"><i class="fas fa-expand"></i></button>
            <button id="compress-button"><i class="fas fa-compress"></i></button>
            <div class="card-body">
            <!-- Nav tabs -->
            <input type="hidden" id="cek_ijin" value="{{Auth::user()->can('backend-dashboard-ijin')}}">
            <input type="hidden" id="cek_operasional" value="{{Auth::user()->can('backend-dashboard-operasional')}}">
            <input type="hidden" id="cek_invoice" value="{{Auth::user()->can('backend-dashboard-invoice')}}">
            <input type="hidden" id="cek_status" value="{{Auth::user()->can('backend-dashboard-status_jo')}}">
                <ul class="nav nav-pills nav-justified" role="tablist">
                    @if(Auth::user()->can('backend-dashboard-ijin') == 'true')
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-bs-toggle="tab" href="#home-1" role="tab" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span style="font-size: 24px" class="d-none d-sm-block">IJIN DAN DOKUMEN</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->can('backend-dashboard-operasional') == 'true')
                    @php($cek_ijin = Auth::user()->can('backend-dashboard-ijin') == 'true' ? '' : 'active')
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link {{$cek_ijin}}" data-bs-toggle="tab" href="#profile-1" role="tab" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                            <span  style="font-size: 24px" class="d-none d-sm-block">OPERASIONAL</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->can('backend-dashboard-invoice') == 'true')
                    @php($cek_ijin = Auth::user()->can('backend-dashboard-ijin') == 'true' || Auth::user()->can('backend-dashboard-operasional') == 'true' ? '' : 'active')
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link {{$cek_ijin}}" data-bs-toggle="tab" href="#messages-1" role="tab" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                            <span  style="font-size: 24px" class="d-none d-sm-block">INVOICE</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->can('backend-dashboard-status_jo') == 'true')
                    @php($cek_ijin = Auth::user()->can('backend-dashboard-status_jo') == 'true' ? '' : 'active')
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link {{$cek_ijin}}" data-bs-toggle="tab" href="#status-1" role="tab" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                            <span  style="font-size: 24px" class="d-none d-sm-block">STATUS JOBORDER</span>
                        </a>
                    </li>
                    @endif
                </ul>

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">
                    @if(Auth::user()->can('backend-dashboard-ijin') == 'true')
                        <div class="tab-pane active" id="home-1" role="tabpanel" style="padding-top:20px">
                            <p class="mb-0">


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card-body" style="border: 1px solid #fff; margin-top:10px">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Berlaku Sim</h4>
                                            </div>
                                            <div class="row" style="padding-bottom: 10px">
                                                <div class="col-sm-12 text-end">
                                                    <div class="dt-buttons btn-group flex-wrap">
                                                        <button id="excel" class="btn btn-secondary buttons-excel buttons-html5" onClick="excel('berlaku_sim')" tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                        <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('berlaku_sim')" tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="Datatablesim" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>Nama Lengkap</th>
                                                            <th>No hp</th>
                                                            <th>Tanggal Expired SIM</th>
                                                            <th>Masa  Berlaku</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body" style="border: 1px solid #fff; margin-top:10px">
                                            <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Pajak 5 Tahun Kendaraan</h4>
                                            </div>
                                            <div class="row" style="padding-bottom: 10px">
                                                <div class="col-sm-12 text-end">
                                                    <div class="dt-buttons btn-group flex-wrap">
                                                        <button id="excel" class="btn btn-secondary buttons-excel buttons-html5" onClick="excel('berlaku_pajak')" tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                        <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('berlaku_pajak')" tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="Datatablepajak" class="table table-bordered border-bottom w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>No Polisi</th>
                                                            <th>Tanggal Expired</th>
                                                            <th>Masa  Berlaku</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header text-center">

                                        <div class="alert alert-warning alert-dismissible fade show px-3 mb-0" role="alert">
                                            <h3 class="text-black">INFORMASI KENDARAAN</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6" >
                                            <div class="card-body" style="min-height: 915px; border: 1px solid #fff; margin-top:10px">
                                                <div class="text-center"  style="padding: 20px">
                                                    <h4 class="main-content-label mb-1">Pajak 1 Tahun Kendaraan</h4>
                                                </div>
                                                <div class="row" style="padding-bottom: 10px">
                                                    <div class="col-sm-12 text-end">
                                                        <div class="dt-buttons btn-group flex-wrap">
                                                            <button id="excel" class="btn btn-secondary buttons-excel buttons-html5"  onClick="excel('berlaku_stnk')"  tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                            <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('berlaku_stnk')" tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="Datatablestnk" class="table table-bordered border-bottom w-100" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th>No Polisi</th>
                                                                <th>Tanggal Expired</th>
                                                                <th>Masa  Berlaku</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                            <div class="card-body" style="min-height: 915px; border: 1px solid #fff; margin-top:10px">
                                                <div class="text-center"  style="padding: 20px">
                                                    <h4 class="main-content-label mb-1">KIR Kendaraan</h4>
                                                </div>
                                                <div class="row" style="padding-bottom: 10px">
                                                    <div class="col-sm-12 text-end">
                                                        <div class="dt-buttons btn-group flex-wrap">
                                                            <button id="excel" class="btn btn-secondary buttons-excel buttons-html5" onClick="excel('berlaku_kir')"  tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                            <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('berlaku_kir')" tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="Datatablekir" class="table table-bordered border-bottom w-100" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th>No Polisi</th>
                                                                <th>Tanggal Expired</th>
                                                                <th>Masa  Berlaku</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <div class="card-body" style="min-height: 915px; border: 1px solid #fff; margin-top:10px">
                                                <div class="text-center"  style="padding: 20px">
                                                    <h4 class="main-content-label mb-1">Ijin Usaha</h4>
                                                </div>
                                                <div class="row" style="padding-bottom: 10px">
                                                    <div class="col-sm-12 text-end">
                                                        <div class="dt-buttons btn-group flex-wrap">
                                                            <button id="excel" class="btn btn-secondary buttons-excel buttons-html5" onClick="excel('berlaku_ijin_usaha')"  tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                            <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('berlaku_ijin_usaha')" tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="Datatableijinusaha" class="table table-bordered border-bottom w-100" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th>No Polisi</th>
                                                                <th>Tanggal Expired</th>
                                                                <th>Masa  Berlaku</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                            <div class="card-body" style="min-height: 915px; border: 1px solid #fff; margin-top:10px">
                                                <div class="text-center"  style="padding: 20px">
                                                    <h4 class="main-content-label mb-1">Ijin Bongkar Muat (BM) Kendaraan</h4>
                                                </div>
                                                <div class="row" style="padding-bottom: 10px">
                                                    <div class="col-sm-12 text-end">
                                                        <div class="dt-buttons btn-group flex-wrap">
                                                            <button id="excel" class="btn btn-secondary buttons-excel buttons-html5" onClick="excel('berlaku_ijin_bongkar')" tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                            <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('berlaku_ijin_bongkar')" tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="Datatablebm" class="table table-bordered border-bottom w-100" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th>No Polisi</th>
                                                                <th>Tanggal Expired</th>
                                                                <th>Masa  Berlaku</th>
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

                            </p>
                        </div>
                    @endif
                    @if(Auth::user()->can('backend-dashboard-operasional') == 'true')
                        @php($cek_ijin = Auth::user()->can('backend-dashboard-ijin') == 'true' ? '' : 'active')
                        <div class="tab-pane {{$cek_ijin}}" id="profile-1" role="tabpanel" style="padding-top:20px">
                            <p class="mb-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header text-center">
                                                <div class="alert alert-secondary alert-dismissible fade show px-3 mb-0" role="alert">
                                                    <h3 class="text-black">SUPIR DAN KENDARAAN TIDAK JALAN</h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="card-body">
                                                        <div class="text-center"  style="padding: 20px">
                                                            <h4 class="main-content-label mb-1">Supir</h4>

                                                        </div>
                                                        <div class="row" style="padding-bottom: 10px">
                                                            <div class="col-sm-12 text-end">
                                                                <div class="dt-buttons btn-group flex-wrap">
                                                                    <button id="excel" class="btn btn-secondary buttons-excel buttons-html5" onClick="excel('driver_tidak_jalan')"  tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                                    <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('driver_tidak_jalan')" tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table id="Datatablesupirtj" class="table table-bordered border-bottom" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="1%">No</th>
                                                                        <th>Nama Lengkap</th>
                                                                        <th>No hp</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="card-body">
                                                        <div class="text-center"  style="padding: 20px">
                                                            <h4 class="main-content-label mb-1">Kendaraan</h4>
                                                        </div>
                                                        <div class="row" style="padding-bottom: 10px">
                                                            <div class="col-sm-12 text-end">
                                                                <div class="dt-buttons btn-group flex-wrap">
                                                                    <button id="excel" class="btn btn-secondary buttons-excel buttons-html5" onClick="excel('kendaraan_tidak_jalan')"  tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                                    <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('kendaraan_tidak_jalan')" tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="table-responsive">
                                                            <table id="Datatablemobiltj" class="table table-bordered border-bottom" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="1%">No</th>
                                                                        <th width="200px">Nomor Plat Polisi</th>
                                                                        <th>Merek</th>
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
                            </p>
                        </div>
                    @endif
                    @if(Auth::user()->can('backend-dashboard-invoice') == 'true')
                            @php($cek_ijin = Auth::user()->can('backend-dashboard-ijin') == 'true' || Auth::user()->can('backend-dashboard-operasional') == 'true' ? '' : 'active')
                        <div class="tab-pane {{$cek_ijin}}" id="messages-1" role="tabpanel" style="padding-top:20px">
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
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label>Customer<span class="text-danger">*</span></label>
                                                                <select id="select2Customer" style="width: 100% !important;" name="customer_id">
                                                                </select>
                                                              </div>
                                                        </div>
                                                        <div class="col-md-3 text-end" style="padding-top:30px;">
                                                            <div class="d-flex justify-content-start">
                                                                <a id="terapkan_filter" class="btn btn-success">
                                                                    Terapkan Filter
                                                                    <i class="fas fa-align-justify"></i>
                                                                </a>
                                                                <button  class="btn btn-danger" onClick="" id="refresh">Refresh</button>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 text-end" style="padding-top:30px;">
                                                            <div class="dt-buttons btn-group flex-wrap">
                                                                <button onClick="excel('invoice')" id="excel" class="btn btn-secondary buttons-excel buttons-html5"  tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                                <button onClick="pdf('invoice')" class="btn btn-secondary buttons-pdf buttons-html5"  tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
                                                            </div>
                                                        </div>


                                                    </div>






                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <p class="mb-0">
                                <div class="row">

                                        <div class="card">
                                            <div class="card-header text-center">
                                                <div class="alert alert-success alert-dismissible fade show px-3 mb-0" role="alert">
                                                    <h3 class="text-black">INVOICE JATUH TEMPO</h3>
                                                </div>

                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-2">
                                                    </div>
                                                    <div class="col-8">
                                                    <div class="table-responsive">
                                                        <table id="Datatableinvoice" class="table table-bordered border-bottom" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%">No</th>
                                                                    <th>Kode Invoice</th>
                                                                    <th>Tanggal Invoice</th>
                                                                    <th>Customer</th>
                                                                    <th>Nominal Invoice</th>
                                                                    <th>Due Date</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="4" style="text-align:end !important">Total :</th>
                                                                    <th  class="text-end"  id="sum_nominal"></th>
                                                                    <th></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="col-2">
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>

                                </div>
                                <div class="row">

                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header text-center">
                                                <div class="alert alert-danger alert-dismissible fade show px-3 mb-0" role="alert">
                                                    <h3 class="text-black">JOBORDER BELUM ADA INVOICE</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-xl-12">
                                                <div class="mt-xl-0 mt-4">

                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">

                                                    </div>
                                                </div>

                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                                                <div class="card border shadow-none card-body text-muted mb-0">
                                                                    <div class="row">
                                                                        <div class="col-md-6">

                                                                        </div>
                                                                        <div class="col-md-3 text-end" style="padding-top:30px;">

                                                                        </div>
                                                                        <div class="col-md-3 text-end" style="padding-top:30px;">
                                                                            <div class="dt-buttons btn-group flex-wrap">
                                                                                <button id="excel" class="btn btn-secondary buttons-excel buttons-html5"   onClick="excel('joborder')" tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                                                <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('joborder')" tabindex="0" aria-controls="Datatable" type="button" id="pdf"><span>PDF</span></button>
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
                                                <div class="text-center"  style="padding: 20px">
                                                    {{-- <h4 class="main-content-label mb-1">Berlaku Sim</h4> --}}
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="Datatablejo" class="table table-bordered border-bottom" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th>Kode Joborder</th>
                                                                <th>Driver</th>
                                                                <th>Nomor Polisi</th>
                                                                <th>Jenis Mobil</th>
                                                                <th>Customer</th>
                                                                <th>Muatan</th>
                                                                <th>Alamat Awal (Dari)</th>
                                                                <th>Alamat Akhir (Ke)</th>
                                                                <th>Tanggal Closing</th>
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


                            </p>
                        </div>
                    @endif
                    @if(Auth::user()->can('backend-dashboard-status_jo') == 'true')
                    @php($cek_ijin = Auth::user()->can('backend-dashboard-status_jo') == 'true' ? '' : 'active')
                    <div class="tab-pane {{$cek_ijin}}" id="status-1" role="tabpanel" style="padding-top:20px">
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
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label>Customer<span class="text-danger">*</span></label>
                                                            <select id="select2CustomerStatus" style="width: 100% !important;" name="customer_id">
                                                            </select>
                                                          </div>
                                                    </div>
                                                    <div class="col-md-3 text-end" style="padding-top:30px;">
                                                        <div class="d-flex justify-content-start">
                                                            <a id="terapkan_filter_status" class="btn btn-success">
                                                                Terapkan Filter
                                                                <i class="fas fa-align-justify"></i>
                                                            </a>
                                                            <button  class="btn btn-danger" onClick="" id="refresh_status">Refresh</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 text-end" style="padding-top:30px;">
                                                        <div class="dt-buttons btn-group flex-wrap">
                                                            <button id="excelstatys" onClick="excel('status_joborder')" class="btn btn-secondary buttons-excel buttons-html5"  tabindex="0" aria-controls="Datatable" type="button"><span>Excel</span></button>
                                                            <button class="btn btn-secondary buttons-pdf buttons-html5" onClick="pdf('status_joborder')" tabindex="0" aria-controls="Datatable" type="button" id="pdfstatus"><span>PDF</span></button>
                                                        </div>
                                                    </div>


                                                </div>






                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <p class="mb-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            <div class="alert alert-success alert-dismissible fade show px-3 mb-0" role="alert">
                                                <h3 class="text-black">Status Joborder</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {{-- <div class="text-center"  style="padding: 20px">
                                                <h4 class="main-content-label mb-1">Berlaku Sim</h4>
                                            </div> --}}
                                            <div class="table-responsive">
                                                <table id="DatatableStatusJo" class="table table-bordered border-bottom" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Kode Jo</th>
                                                            <th>Tanggal Jo</th>
                                                            <th>Driver</th>
                                                            <th>Nopol</th>
                                                            <th>Jenis Mobil</th>
                                                            <th>Customer</th>
                                                            <th>Rute Awal</th>
                                                            <th>Rute Akhir</th>
                                                            <th>Muatan</th>
                                                            <th>Tonase</th>
                                                            <th>Total UJ</th>
                                                            <th>Kode Gaji</th>
                                                            <th>Gaji</th>
                                                            <th>Tanggal Pay Gaji</th>
                                                            <th>Kode Invoice</th>
                                                            <th>Tagihan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="11" style="text-align:end !important">Total :</th>
                                                            <th  class="text-end" ></th>
                                                            <th></th>
                                                            <th  class="text-end" ></th>
                                                            <th ></th>
                                                            <th ></th>
                                                            <th  class="text-end" ></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>



                        </p>
                    </div>
                    @endif
                </div>

            </div>
        </div>









    </div>
    <input type="hidden" id="urlpdf" value="{{ route('backend.dashboard.pdf') }}">
    <input type="hidden" id="urlexcel" value="{{ route('backend.dashboard.excel') }}">
</div>
@endsection

@section('css')
<style>
div.dt-top-container {
  display: grid;
  grid-template-columns: auto auto auto;
}

div.dt-center-in-div {
  margin: 0 auto;
}

div.dt-btn-container {
  margin: 0 0 0 auto;
}
</style>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/jspdf@1.5.3/dist/jspdf.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.0/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendor_components/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script>

    $(document).ready(function () {
    let cek_ijin = $('#cek_ijin').val();
    let cek_operasional = $('#cek_operasional').val();
    let cek_invoice = $('#cek_invoice').val();
    let cek_status = $('#cek_status').val();


    //invoice
    if(cek_invoice == '1'){
     let dataTablejo = $('#Datatablejo').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTablejo.draw();
                }
            }
        ],
        // responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[9, 'asc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
            url: "{{ route('backend.dashboard.dtjo') }}",
            data: function (d) {
                d.customer_id = $('#select2Customer').find(':selected').val();
            }
        },

        columns: [
            {
                data: "id", name:'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
               data: "kode_joborder", name:'kode_joborder',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   let kode = '<a target="_blank" href="{{ route('backend.joborder.index') }}?joborder_id='+row.id+'">'+data+'</a>';
                   return kode;
               }
            },
            {data: 'driver.name', name: 'driver.name'},
            {data: 'mobil.nomor_plat', name: 'mobil.nomor_plat'},
            {data: 'jenismobil.name', name: 'jenismobil.name'},
            {data: 'customer.name', name: 'customer.name'},
            {data: 'muatan.name', name: 'muatan.name'},
            {data: 'ruteawal.name', name: 'ruteawal.name'},
            {data: 'ruteakhir.name', name: 'ruteakhir.name'},
            {data: 'konfirmasijo.0.tgl_konfirmasi', name: 'konfirmasi_joborder.tgl_konfirmasi'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [


        ],
        });



        let dataTableinvoice = $('#Datatableinvoice').DataTable({
            footerCallback: function ( row, rowData, start, end, display ) {
            var api = this.api(), rowData;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            var sum = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            $( api.column( 4 ).footer() ).html(
               $.fn.dataTable.render.number( ',', '.', 0, '' ).display(sum)
            );
            // console.log(tot);
         },
            dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTableinvoice.draw();
                }
            }
        ],
        // responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[5, 'asc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
            url: "{{ route('backend.dashboard.dtinvoice') }}",
            data: function (d) {
                d.customer_id = $('#select2Customer').find(':selected').val();
            }
        },

        columns: [
            {
                data: "id", name:'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
               data: "kode_invoice", name:'kode_invoice',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   let kode = '<a target="_blank" href="{{ route('backend.invoice.index') }}?invoice_id='+row.id+'">'+data+'</a>';
                   return kode;
               }
            },
            {data: 'tgl_invoice', name: 'tgl_invoice'},
            {data: 'customer.name', name: 'customer.name'},
            {data: 'total_harga', name: 'total_harga', class: 'text-end'},
            {data: 'tgl_jatuh_tempo', name: 'tgl_jatuh_tempo'},
        ],
        columnDefs: [
            {
                targets: [4],
                render: $.fn.dataTable.render.number('.', ',', 0, '')
            }
            ],
        });

    let select2Customer = $('#select2Customer');
    select2Customer.select2({
        dropdownParent: select2Customer.parent(),
        searchInputPlaceholder: 'Cari Customer',
        width: '100%',
        placeholder: 'Pilih Customer',
        ajax: {
          url: "{{ route('backend.customer.select2') }}",
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
            let data = e.params.data;s
    });

     $("#terapkan_filter").click(function() {
        dataTablejo.draw();
        dataTableinvoice.draw();
      });

      $("#refresh").click(function() {
        $("#select2Customer").val("").trigger("change");
        dataTablejo.draw();
        dataTableinvoice.draw();
      });




    }
   //operasional
    if(cek_operasional == '1'){
     let dataTablesupirtj = $('#Datatablesupirtj').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTablesupirtj.draw();
                }
            }
        ],
    //    responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[0, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtdriver') }}",
         data: function (d) {
           d.status_jalan = 1;
         }
       },

       columns: [
         {
               data: "id", name:'id', className:'text-center', width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'name', name: 'name', width: "50%"},
         {data: 'telp', name: 'telp', width: "49%"}
       ],
       columnDefs: [


       ],
     });



     let dataTablemobiltj = $('#Datatablemobiltj').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTablemobiltj.draw();
                }
            }
        ],
       responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[0, 'desc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
        url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
            d.status_jalan = 1;
         }
       },

       columns: [
         {
               data: "id", name:'id',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'merkmobil.name', name: 'merkmobil.name'},
         {data: 'jenismobil.name', name: 'jenismobil.name'},
         {data: 'dump', className:'text-center', name: 'dump'},
       ],
     });

    }
   //ijin
    if(cek_ijin ==  '1'){
     let dataTablesim = $('#Datatablesim').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTablesim.draw();
                }
            }
        ],
    //    responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[4, 'asc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtdriver') }}",
         data: function (d) {
            d.type = 'berlaku_sim';
         }
       },

       columns: [
         {
               data: "id", name:'id',
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'name', name: 'name'},
         {data: 'telp', name: 'telp'},
         {data: 'tgl_sim', name: 'tgl_sim'},
         {data: 'exp_sim', className:'text-center', name: 'tgl_sim'},

       ],
       columnDefs: [


       ],
     });



     let dataTablestnk = $('#Datatablestnk').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTablestnk.draw();
                }
            }
        ],
    //    responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[3, 'asc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
        url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
            d.type = 'berlaku_stnk';
         }
       },

       columns: [
         {
               data: "id", name:'id',
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_stnk', name: 'berlaku_stnk'},
         {data: 'exp_stnk', className:'text-center', name: 'berlaku_stnk'},
       ],
     });



     let dataTablepajak = $('#Datatablepajak').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTablepajak.draw();
                }
            }
        ],
        // responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[3, 'asc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
            d.type = 'berlaku_pajak';
         }
       },

       columns: [
        {
               data: "id", name:'id', className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_pajak', name: 'berlaku_pajak'},
         {data: 'exp_pajak', className:'text-center', name: 'berlaku_pajak'},

       ],
       columnDefs: [


       ],
     });




     let dataTablekir = $('#Datatablekir').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTablekir.draw();
                }
            }
        ],
        // responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[3, 'asc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
            d.type = 'berlaku_kir';
         }
       },

       columns: [
        {
               data: "id", name:'id',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_kir', name: 'berlaku_kir'},
         {data: 'exp_kir', className:'text-center', name: 'berlaku_kir'},

       ],
       columnDefs: [


       ],
     });

     let dataTablebm = $('#Datatablebm').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTablebm.draw();
                }
            }
        ],
        // responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[3, 'asc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
            d.type = 'berlaku_ijin_bongkar';
         }
       },

       columns: [
        {
               data: "id", name:'id',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_ijin_bongkar', name: 'berlaku_ijin_bongkar'},
         {data: 'exp_bm', className:'text-center', name: 'berlaku_ijin_bongkar'},

       ],
       columnDefs: [


       ],
     });


     let dataTableijinusaha = $('#Datatableijinusaha').DataTable({
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTableijinusaha.draw();
                }
            }
        ],
        // responsive: true,
       scrollX: false,
       processing: true,
       serverSide: true,
       order: [[3, 'asc']],
       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
       pageLength: 10,
       ajax: {
         url: "{{ route('backend.dashboard.dtmobil') }}",
         data: function (d) {
            d.type = 'berlaku_ijin_usaha';
         }
       },

       columns: [
        {
               data: "id", name:'id',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {data: 'nomor_plat', name: 'nomor_plat'},
         {data: 'berlaku_ijin_usaha', name: 'berlaku_ui'},
         {data: 'exp_iu', className:'text-center', name: 'berlaku_ijin_usaha'},

       ],
       columnDefs: [


       ],
     });

    }

    if(cek_status == '1'){
    let dataTableStatusJo = $('#DatatableStatusJo').DataTable({
        footerCallback: function ( row, rowData, start, end, display ) {
            var api = this.api(), rowData;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

           var sum_total = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var sum_gaji = api
                .column( 13 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

           var sum_invoice = api
                .column( 16 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );



            $( api.column( 11 ).footer() ).html(
               $.fn.dataTable.render.number( ',', '.', 0, '' ).display(sum_total)
            );

            $( api.column( 13 ).footer() ).html(
               $.fn.dataTable.render.number( ',', '.', 0, '' ).display(sum_gaji)
            );

            $( api.column( 16 ).footer() ).html(
               $.fn.dataTable.render.number( ',', '.', 0, '' ).display(sum_invoice)
            );
            // console.log(tot);
        },
        dom: '<"dt-top-container"<l><"dt-center-in-div"f><"dt-btn-container"B>r>tip',
        buttons: [
            {
                text: 'Refresh',
                action: function ( e, dt, node, config ) {
                    dataTableStatusJo.draw();
                }
            }
        ],
            // responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        ajax: {
            url: "{{ route('backend.dashboard.dtstatusjo') }}",
            data: function (d) {
                d.customerstatus_id = $('#select2CustomerStatus').find(':selected').val();
            }
        },

       columns: [
        {
               data: "id", name:'id',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   return meta.row + meta.settings._iDisplayStart + 1;
               }
         },
         {
               data: "kode_joborder", name:'kode_joborder',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                   let kode = '<a target="_blank" href="{{ route('backend.joborder.index') }}?joborder_id='+row.id+'">'+data+'</a>';
                   return kode;
               }
         },
         {data: 'tgl_joborder', name: 'tgl_joborder'},
         {data: 'driver.name', name: 'driver.name'},
         {data: 'mobil.nomor_plat', name: 'mobil.nomor_plat'},
         {data: 'jenismobil.name', name: 'jenismobil.name'},
         {data: 'customer.name', name: 'customer.name'},
         {data: 'ruteawal.name', name: 'ruteawal.name'},
         {data: 'ruteakhir.name', name: 'ruteakhir.name'},
         {data: 'muatan.name', name: 'muatan.name'},
         {data: 'konfirmasijo.0.berat_muatan', name: 'konfirmasijo.0.berat_muatan'},
         {data: 'total_uang_jalan', name: 'total_uang_jalan', width: '80px'},
         {
               data: "gaji.kode_gaji", name:'gaji.kode_gaji',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                let kode = '-';
                if(row.gaji !== null){
                    kode = '<a target="_blank" href="{{ route('backend.penggajian.index') }}?penggajian_id='+row.gaji.id+'">'+data+'</a>';
                }
                   return kode;
               }
          },
         {data: 'gaji.total_gaji', name: 'gaji.total_gaji', width: '80px'},
         {data: 'gaji.payment.0.tgl_payment', name: 'gaji.payment.tgl_payment'},
        //  {data: 'invoice.kode_invoice', name: 'invoice.kode_invoice'},
          {
               data: "invoice.kode_invoice", name:'invoice.kode_invoice',className:'text-center',  width: "1%",
               render: function (data, type, row, meta) {
                let kode = '-';
                if(row.invoice !== null){
                    kode = '<a target="_blank" href="{{ route('backend.invoice.index') }}?invoice_id='+row.invoice.id+'">'+data+'</a>';
                }
                   return kode;
               }
          },
         {data: 'invoice.total_harga', name: 'invoice.total_harga', width: '80px'},
       ],
       columnDefs: [
        {
            className: 'text-end',
            targets: [11, 13, 16],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
        },
        {
            className: 'text-center',
            targets: [10, 12, 14]
        },
        {
            targets: [16, 13],
            defaultContent: "0",
        },
        {
            targets:[10,12,15, 14],
            defaultContent: "-",
        },
        {
            targets: [10, 13],
            orderable: false, searchable: false
        }

       ],
     });


     $("#terapkan_filter_status").click(function() {
        dataTableStatusJo.draw();
      });

      $("#refresh_status").click(function() {
        $("#select2CustomerStatus").val("").trigger("change");
        dataTableStatusJo.draw();
      });
    }



     let select2CustomerStatus = $('#select2CustomerStatus');
     select2CustomerStatus.select2({
        dropdownParent: select2CustomerStatus.parent(),
        searchInputPlaceholder: 'Cari Customer',
        width: '100%',
        placeholder: 'Pilih Customer',
        ajax: {
          url: "{{ route('backend.customer.select2') }}",
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








    //pdf excel





   });

   function excel(type){
        let st_jalan = null;
        if(type == 'driver_tidak_jalan' || type == 'kendaraan_tidak_jalan'){
            st_jalan = '1';
        }
        let params =  new URLSearchParams({
           customer_id : $('#select2Customer').val() || '',
           customerstatus_id : $('#select2CustomerStatus').val() || '',
           status_jalan : st_jalan,
           type : type,
        });

        let url =  $('#urlexcel').val()+"?"+params.toString();
        window.open(url, '_blank');
    }


   function pdf(type){
        let st_jalan = null;
        if(type == 'driver_tidak_jalan' || type == 'kendaraan_tidak_jalan'){
            st_jalan = '1';
        }
        let params =  new URLSearchParams({
           customer_id : $('#select2Customer').val() || '',
           customerstatus_id : $('#select2CustomerStatus').val() || '',
           status_jalan : st_jalan,
           type : type,
        });

        let url =  $('#urlpdf').val()+"?"+params.toString();
        window.open(url, '_blank');
    }
 </script>
@endsection
