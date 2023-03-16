@extends('backend.layouts.master')

@section('title') {{ $config['page_title'] }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mb-3">
                <h5 class="card-title mb-3">Table {{ $config['page_title'] }}</h5>
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <div class="col-xl-12">
                            <div class="mt-xl-0 mt-4">
                                <div class="d-flex gap-2 flex-wrap mb-3 text-center">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">Filter</button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="multi-collapse collapse show" id="multiCollapseExample2" style="">
                                            <div class="card border shadow-none card-body text-muted mb-0">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="mb-3">
                                                            <label>Nomor Slip Gaji<span class="text-danger">*</span></label>
                                                            <select id="select2Gaji" style="width: 100% !important;" name="penggajian_id">

                                                            </select>
                                                          </div>
                                                    </div>
                                                    <div class="col-4 text-end" style="padding-top:30px;">
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
                    <div class="flex-shrink-0">
                        <a class="btn btn-primary " href="{{ route('backend.paymentgaji.create') }}">
                            Tambah
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsove">
                    <table id="Datatable" class="table table-bordered border-bottom w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">Kode Gaji</th>
                                <th>Tanggal Bayar</th>
                                <th>Nominal</th>
                                <th>Jenis Pembayaran</th>
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

  <div class="modal fade" id="modalValidasi" tabindex="-1" aria-labelledby="modalmodalValidasi" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit {{ $config['page_title'] }}</h5>
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
              <label>Validasi<span class="text-danger">*</span></label>
              <input type="hidden" name="id">
              <select class="form-select" id="select2Validasi" name="validasi">
                <option value="1">Aktif</option>
                <option value="0">NonAktif</option>
              </select>
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
      let select2Validasi = $('#select2Validasi');
      let select2Gaji = $('#select2Gaji');
      let modalDelete = document.getElementById('modalDelete');
      const bsDelete = new bootstrap.Modal(modalDelete);
      let modalValidasi = document.getElementById('modalValidasi');
      const bsValidasi = new bootstrap.Modal(modalValidasi);
      var collapsedGroups = {};
      let dataTable = $('#Datatable').DataTable({
        responsive: true,
        scrollX: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        lengthMenu: [[50, -1], [50, "All"]],
        pageLength: 50,
        ajax: {
          url: "{{ route('backend.paymentgaji.index') }}",
          data: function (d) {
            d.id = $('#select2Gaji').find(':selected').val();
          }
        },

        columns: [
          {data: 'kode_gaji', name: 'kode_gaji'},
          {data: 'tgl_payment', name: 'tgl_payment'},
          {data: 'nominal', className: 'text-end', name: 'nominal'},
          {data: 'jenis_payment', name: 'jenis_payment'},
          {data: 'keterangan', name: 'keterangan'},
        ],
        rowGroup: {
            dataSrc: 'kode_gaji',
                startRender: function(rows, group) {
                    var collapsed = !!collapsedGroups[group];
                    rows.nodes().each(function(r) {
                        r.style.display = 'none';
                        if (collapsed) {
                            r.style.display = '';
                        }
                    })
                    console.log(rows.data()[0].penggajian.status_payment);
                    let url = (rows.data()[0].penggajian.status_payment < 2) ? '<a class="btn btn-primary" href="paymentgaji/' + rows.data()[0].penggajian_id+ '/edit">Tambah Pembayaran</a>' : '<span class="badge bg-pill  bg-success">Lunas</span>';
                    let sisa_tagihan=  $.fn.dataTable.render.number('.', ',', 3, '').display(rows.data()[0].penggajian.sisa_gaji);
                    return $('<tr/>')
                    .append('<td colspan="5"><div class="float-start"> KODE GAJI : ' + group + ' TOTAL PAYMENT : (' + rows.count() + ') SISA GAJI : '+sisa_tagihan+' </div><div class="float-end">'+url+'</div></td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
               }
        },
        columnDefs: [
          {
            targets: [2],
            render: $.fn.dataTable.render.number('.', ',', 3, '')
          }

        ],
      });


      $('#Datatable tbody').on('click', 'tr.group-start', function() {
        var judul = $(this).data('name');
        collapsedGroups[judul] = !collapsedGroups[judul];
        dataTable.draw(false);
      });

    select2Validasi.select2({
        dropdownParent: select2Validasi.parent(),
        searchInputPlaceholder: 'Cari Validasi',
        width: '100%',
        placeholder: 'Pilih Validasi',
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

      $("#terapkan_filter").click(function() {
        dataTable.draw();
      });
      modalDelete.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        this.querySelector('.urlDelete').setAttribute('href', '{{ route("backend.mobil.index") }}/' + id);
      });
      modalDelete.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('.urlDelete').setAttribute('href', '');
      });

      modalValidasi.addEventListener('show.bs.modal', function (event) {
        let id = event.relatedTarget.getAttribute('data-bs-id');
        let validasi = event.relatedTarget.getAttribute('data-bs-validasi');
        $(this).find('#select2Validasi').val(validasi).trigger('change');
        this.querySelector('input[name=id]').value = id;
        this.querySelector('#formUpdateValidasi').setAttribute('action', '{{ route("backend.rute.validasi") }}');
      });
      modalValidasi.addEventListener('hidden.bs.modal', function (event) {
        $(this).find('#select2Validasi').val('').trigger('change');
        this.querySelector('input[name=id]').value = '';
        this.querySelector('#formUpdateValidasi').setAttribute('href', '');
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
