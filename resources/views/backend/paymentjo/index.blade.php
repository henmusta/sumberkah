@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mb-3">
                <h5 class="card-title mb-3">Table {{ $config['page_title'] }}</h5>
                <div class="mt-xl-0 mt-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="col-xl-12">
                                <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Filter</button>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->can('backend-paymentjo-create') == 'true')
                        <div class="flex-shrink-0">
                            <a class="btn btn-primary " href="{{ route('backend.paymentjo.create') }}">
                                Tambah
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                <div class="card border shadow-none card-body text-muted mb-0">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label>Id Joborder<span class="text-danger">*</span></label>
                                                <select id="select2Joborder" style="width: 100% !important;" name="joborder_id">
                                                    <option value="{{ $data['joborder']['id'] ?? '' }}"> {{$data['joborder']['kode_joborder'] ?? '' }}</option>
                                                </select>
                                              </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label>Nopol<span class="text-danger">*</span></label>
                                                <select id="select2Mobil" style="width: 100% !important;" name="nopol">
                                                </select>
                                              </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label>Driver<span class="text-danger">*</span></label>
                                                <select id="select2Driver" style="width: 100% !important;" name="driver">
                                                </select>
                                              </div>
                                        </div>
                                        <div class="col-md-3 text-end" style="padding-top:30px;">
                                            <a id="terapkan_filter" class="btn btn-success">
                                                Terapkan Filter
                                                <i class="fas fa-align-justify"></i>
                                            </a>
                                            <button  class="btn btn-danger" onClick="window.location.reload();">Refresh</button>
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
                                <th class="text-center">Kode Joboorder</th>
                                <th>Tanggal Bayar</th>
                                <th>Nominal</th>
                                <th>Nominal Kasbon</th>
                                <th>Jenis Pembayaran</th>
                                <th>Keterangan</th>
                                <th width="10%">Aksi</th>
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

  <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalmodalEdit" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit {{ $config['page_title'] }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formUpdate" action="#" autocomplete="off">
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
            <div class="row" >
                <label> Tabel Pembayaran<span class="text-danger">*</span></label>

                <div class="col-12">

                        <input type="hidden" id="id" name="id" value=""  class="form-control text-end" />
                        <div class="table-responsive">
                            <table id="DatatableSingle" class="table " width="100%">
                                <thead>
                                    <tr>
                                        <th>Jenis Pembayaran</th>
                                        <th>Keterangan Pembayaran</th>
                                        <th>Keterangan Kasbon</th>
                                        <th>Nominal Pembayaran</th>
                                        <th>Nominal Kasbon</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th><select class="form-select" id="select2JenisPayment" name="jenis_payment">
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select></th>
                                        <th> <input  style="width: 200px;" type="text" id="keterangan" name="keterangan"  class="form-control text-end" /></th>
                                        <th><input  style="width: 200px;" type="text" id="keterangan_kasbon" name="keterangan_kasbon"  class="form-control text-end" /></th>
                                        <th><input  style="width: 250px;" type="text" id="nominal" name="nominal"  class="form-control text-end" /></th>
                                        <th><input  style="width: 250px;" type="text" id="nominal_kasbon" name="nominal_kasbon"  class="form-control text-end" /></th>

                                    </tr>
                                </tbody>
                                <tfoot>
                                    <th colspan="4" class="text-end">Tanggal Pembayaran</th>
                                    <th>
                                        <input type="text" id="tgl_pembayaran" name="tgl_pembayaran"  class="form-control" placeholder="Tanggal Pembayaran" />
                                    </th>
                                </tfoot>
                            </table>
                        </div>
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
        $('#tgl_pembayaran').flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true
         });

    let select2Mobil = $('#select2Mobil');
    select2Mobil.select2({
        dropdownParent:   select2Mobil.parent(),
        searchInputPlaceholder: 'Cari Mobil',
        width: '100%',
        placeholder: 'Pilih Mobil',
        ajax: {
          url: "{{ route('backend.mobil.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
            //   status_jalan: 1,
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
    });

    let select2Driver = $('#select2Driver');
    select2Driver.select2({
        dropdownParent:  select2Driver.parent(),
        searchInputPlaceholder: 'Cari Driver',
        width: '100%',
        placeholder: 'Pilih Driver',
        ajax: {
          url: "{{ route('backend.driver.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              validasi: 1,
              status_aktif: 1,
            //   status_jalan: 1,
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      }).on('select2:select', function (e) {
            let data = e.params.data;
            console.log(data.id);
      });


        let select2Joborder = $('#select2Joborder');
        const currenciesOptions = {
            caretPositionOnFocus: "start",
            currencySymbol: "Rp. ",
            unformatOnSubmit: true,
            allowDecimalPadding: true,
            decimalCharacter : ',',
            digitGroupSeparator : '.',
            decimalPlaces: 0,
            modifyValueOnWheel: false,
            minimumValue: 0
       };

    const  nominal 	= new AutoNumeric('#nominal',currenciesOptions),
           nominal_kasbon 	= new AutoNumeric('#nominal_kasbon',currenciesOptions);

    let selectJenisPayment = $('#select2JenisPayment');
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);
      let modalEdit = document.getElementById('modalEdit');
      const bsEdit = new bootstrap.Modal(modalEdit);
      var collapsedGroups = {};
      let dataTable = $('#Datatable').DataTable({
        // responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.paymentjo.index') }}",
          data: function (d) {
            d.id = $('#select2Joborder').find(':selected').val();
            d.nopol = $('#select2Mobil').find(':selected').val();
            d.driver = $('#select2Driver').find(':selected').val();
          }
        },

        columns: [
          {data: 'kode_joborder', name: 'kode_joborder'},
          {data: 'tgl_payment', name: 'tgl_payment'},
          {data: 'nominal', className: 'text-end', name: 'nominal'},
          {data: 'nominal_kasbon', className: 'text-end', name: 'nominal_kasbon'},
          {data: 'jenis_payment', name: 'jenis_payment'},
          {data: 'keterangan', name: 'keterangan'},
          {data: 'action', className:'text-center', name: 'action', orderable: false, searchable: false},
        ],
        rowGroup: {
            dataSrc: 'kode_joborder',
                startRender: function(rows, group) {
                    var collapsed = !!collapsedGroups[group];
                    rows.nodes().each(function(r) {
                        r.style.display = 'none';
                        if (collapsed) {
                            r.style.display = '';
                        }
                    })
                    // console.log();
                    let driver = rows.data()[0].joborder.driver.name;
                    let mobil = rows.data()[0].joborder.mobil.nomor_plat;
                    let kode = '<a class="btn-sm btn-info" href="{{ route('backend.joborder.index') }}?joborder_id='+rows.data()[0].joborder_id+'">'+group+'</a>';
                    let url = (rows.data()[0].joborder.status_payment < 2) ? '<a class="btn btn-primary" href="paymentjo/' + rows.data()[0].joborder_id+ '/edit">Update Pembayaran</a>' : '<span class="badge bg-pill  bg-success">Lunas</span>';
                    let status = (rows.data()[0].joborder.status_payment == 2 && rows.data()[0].joborder.status_joborder == 1) ? '<span class="badge bg-pill  bg-success">Done</span>' : (rows.data()[0].joborder.status_payment < 2 ) ? '' : '<span class="badge bg-pill  bg-warning">Ongoing</span>';
                    let sisa_tagihan_uang_jalan =  $.fn.dataTable.render.number('.', ',', 0, '').display(rows.data()[0].joborder.sisa_uang_jalan);
                    let potongan_kasbon =  $.fn.dataTable.render.number('.', ',', 0, '').display(rows.data()[0].joborder.total_kasbon);

                    let header_html = '<table width="100%">'+
                                            '<tr>'+
                                                '<th style="padding:2px; border:none" width="80px">'+kode+'</th>'+
                                                '<th style="padding:2px; border:none" width="150px">NOPOL : '+mobil+'</th>'+
                                                '<th style="padding:2px; border:none" width="200">DRIVER :'+driver+'</th>'+
                                                '<th style="padding:2px; border:none" width="150px">JUMLAH BAYAR :'+rows.count()+'</th>'+
                                                '<th style="padding:2px; border:none" width="200px">POTONG UJ : <span style="display: inline-block; min-width: 100px;">'+potongan_kasbon+'</span></th>'+
                                                '<th style="padding:2px; border:none" width="150px">SISA UJ : '+sisa_tagihan_uang_jalan+'</th>'+
                                                '<th class="float-end" style="padding:2px; border:none">'+url+status+'</th>'+
                                            '<tr>'+
                                        '</table>';

                    return $('<tr/>')
                    .append('<td colspan="7">'+header_html+'</td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
               }
        },
        columnDefs: [
          {
            targets: [2, 3],
            render: $.fn.dataTable.render.number('.', ',', 0, '')
          }

        ],
      });
      let single = $('#DatatableSingle').DataTable({
        // responsive: true,
        scrollX: false,
        processing: true,
        serverSide: false,
            paging		: false,
			searching 	: false,
			ordering 	: false,
			info 		: false,
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
      });

      $('#Datatable tbody').on('click', 'tr.group-start', function() {
        var judul = $(this).data('name');
        collapsedGroups[judul] = !collapsedGroups[judul];
        dataTable.draw(false);
      });


    //   selectJenisPayment.select2({
    //     dropdownParent: selectJenisPayment.parent(),
    //     searchInputPlaceholder: 'Cari Jenis Payment',
    //     width: '100%',
    //     placeholder: 'Pilih Jenis Payment',
    // });

      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.paymentjo.index") }}/' + id);
      });
      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });

      modalEdit.addEventListener('show.bs.modal', function (event) {
        let id = event.relatedTarget.getAttribute('data-bs-id');
        let tgl_payment = event.relatedTarget.getAttribute('data-bs-tgl_payment');
        let keterangan = event.relatedTarget.getAttribute('data-bs-keterangan');
        let keterangan_kasbon = event.relatedTarget.getAttribute('data-bs-keterangan_kasbon');
        let num_nominal_kasbon = event.relatedTarget.getAttribute('data-bs-nominal_kasbon');
        let num_nominal = event.relatedTarget.getAttribute('data-bs-nominal');
        let jenis_payment = event.relatedTarget.getAttribute('data-bs-jenis_payment');


        this.querySelector('input[name=id]').value = id;
        this.querySelector('input[name=tgl_pembayaran]').value = tgl_payment;
        this.querySelector('input[name=keterangan]').value = keterangan;
        this.querySelector('input[name=keterangan_kasbon]').value = keterangan_kasbon;
        nominal.set(num_nominal);
        nominal_kasbon.set(num_nominal_kasbon);
        selectJenisPayment.val(jenis_payment).trigger('change');
        this.querySelector('#formUpdate').setAttribute('action', '{{ route("backend.paymentjo.updatesingle") }}');
      });
      modalEdit.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=id]').value = '';
        this.querySelector('input[name=keterangan]').value = '';
        this.querySelector('input[name=tgl_pembayaran]').value = '';
        this.querySelector('input[name=keterangan_kasbon]').value = '';
        nominal.set(0);
        nominal_kasbon.set(0);
        selectJenisPayment.val('').trigger('change');
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


      $("#formUpdate").submit(function(e){
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
              bsEdit.hide();
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
