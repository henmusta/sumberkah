@extends('backend.layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="card ">
            <div class="card-header mb-3">
                <h5 class="card-title mb-3">{{ $config['page_title'] }}</h5>
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        {{-- <h5 class="card-title mb-3">Transaction</h5> --}}
                    </div>

                </div>

            </div>
            <form id="formUpdate" action="{{ route('backend.settings.update', Request::segment(3)) }}">
              <meta name="csrf-token" content="{{ csrf_token() }}">
              @method('PUT')
                <div class="card-body">

                        <div id="errorEdit" class="mb-3" style="display:none;">
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-text">
                            </div>
                        </div>
                        </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-3">Nama App</h6>
                                    <div class="form-group">
                                        <input name="name" class="form-control " placeholder="Nama Aplikasi"
                                            value="{{$data['name']}}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <h6 class="mb-3">Layout</h6>
                                        <div class="form-check form-check-inline">
                                            <input onchange="document.body.setAttribute('data-layout', 'vertical')" class="form-check-input" type="radio" name="layout"
                                                id="layout-vertical" value="vertical" {{ $data['layout'] == 'vertical' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="layout-vertical">Vertical</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input onchange="document.body.setAttribute('data-layout', 'horizontal')" class="form-check-input" type="radio" name="layout"
                                                id="layout-horizontal" value="horizontal" {{ $data['layout'] == 'horizontal' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="layout-horizontal">Horizontal</label>
                                        </div>
                                </div>
                                <div class="col-sm-3">
                                    <h6 class="mb-3">Layout Mode</h6>
                                    <div class="form-check form-check-inline">
                                        <input {{ $data['layout_mode'] == 'light' ? 'checked' : '' }}  class="form-check-input"  type="radio" name="layout-mode"
                                            id="layout-mode-light" value="light" onchange="document.body.setAttribute('data-layout-mode', 'light')">
                                        <label class="form-check-label" for="layout-mode-light">Light</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input  {{ $data['layout_mode'] == 'dark' ? 'checked' : '' }}  class="form-check-input" type="radio" name="layout-mode"
                                            id="layout-mode-dark" value="dark" onchange="document.body.setAttribute('data-layout-mode', 'dark')">
                                        <label class="form-check-label" for="layout-mode-dark">Dark</label>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <h6 class="mb-3">Layout Width</h6>

                                    <div class="form-check form-check-inline">
                                        <input {{ $data['layout_width'] == 'fluid' ? 'checked' : '' }} class="form-check-input" type="radio" name="layout-width"
                                            id="layout-width-fluid" value="fluid" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                                        <label class="form-check-label" for="layout-width-fluid">Fluid</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input {{ $data['layout_width'] == 'boxed' ? 'checked' : '' }} class="form-check-input" type="radio" name="layout-width"
                                            id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                                        <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mt-4 mb-3">Layout Position</h6>

                                    <div class="form-check form-check-inline">
                                        <input {{ $data['layout_position'] == 'fixed' ? 'checked' : '' }} class="form-check-input" type="radio" name="layout-position"
                                            id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                                        <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input {{ $data['layout_position'] == 'scrollable' ? 'checked' : '' }} class="form-check-input" type="radio" name="layout-position"
                                            id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                                        <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <h6 class="mt-4 mb-3">Topbar Color</h6>

                                    <div class="form-check form-check-inline">
                                        <input {{ $data['topbar_color'] == 'light' ? 'checked' : '' }} class="form-check-input" type="radio" name="topbar-color"
                                            id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                                        <label class="form-check-label" for="topbar-color-light">Light</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input {{ $data['topbar_color'] == 'dark' ? 'checked' : '' }} class="form-check-input" type="radio" name="topbar-color"
                                            id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                                        <label class="form-check-label" for="topbar-color-dark">Dark</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">

                                    <h6 class="mt-4 mb-3 sidebar-setting">Sidebar Size</h6>

                                    <div class="form-check sidebar-setting">
                                        <input {{ $data['sidebar_size'] == 'lg' ? 'checked' : '' }} class="form-check-input" type="radio" name="sidebar-size"
                                            id="sidebar-size-default" value="lg" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                                        <label class="form-check-label" for="sidebar-size-default">Default</label>
                                    </div>
                                    <div class="form-check sidebar-setting">
                                        <input {{ $data['sidebar_size'] == 'md' ? 'checked' : '' }} class="form-check-input" type="radio" name="sidebar-size"
                                            id="sidebar-size-compact" value="md" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                                        <label class="form-check-label" for="sidebar-size-compact">Compact</label>
                                    </div>
                                    <div class="form-check sidebar-setting">
                                        <input {{ $data['sidebar_size'] == 'sm' ? 'checked' : '' }} class="form-check-input" type="radio" name="sidebar-size"
                                            id="sidebar-size-small" value="sm" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                                        <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <h6 class="mt-4 mb-3 sidebar-setting">Sidebar Color</h6>
                                    <div class="form-check sidebar-setting">
                                        <input {{ $data['sidebar_color'] == 'light' ? 'checked' : '' }} class="form-check-input" type="radio" name="sidebar-color"
                                            id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                                        <label class="form-check-label" for="sidebar-color-light">Light</label>
                                    </div>
                                    <div class="form-check sidebar-setting">
                                        <input {{ $data['sidebar_color'] == 'dark' ? 'checked' : '' }} class="form-check-input" type="radio" name="sidebar-color"
                                            id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                                        <label class="form-check-label" for="sidebar-color-dark">Dark</label>
                                    </div>
                                    <div class="form-check sidebar-setting">
                                        <input {{ $data['sidebar_color'] == 'brand' ? 'checked' : '' }} class="form-check-input" type="radio" name="sidebar-color"
                                            id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                                        <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                                    </div>
                                </div>
                            </div><hr><hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="d-flex flex-column">
                                    <div class="form-group">
                                        <label class="mx-0 text-bold d-block">Icon</label>
                                        <img id="avatar"
                                            src="{{ $data['icon'] != NULL ? asset("/storage/images/logo/".$data['icon']) : asset('assets/backend/images/users/avatar-1.jpg') }}"
                                            style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto"
                                            height="150px"
                                            width="150px" alt="">
                                        <input type="file" class="image d-block image"  name="icon" accept=".jpg, .jpeg, .png">
                                        <p class="text-muted"><small>Allowed JPG, JPEG or PNG. Max
                                            size of
                                            2000kB</small></p>
                                    </div>
                                </div>
                             </div>
                            <div class="col-sm-4">
                                <div class="d-flex flex-column">
                                <div class="form-group">
                                    <label class="mx-0 text-bold d-block">Sidebar Logo</label>
                                    <img id="avatar"
                                        src="{{ $data['sidebar_logo'] != NULL ? asset("/storage/images/logo/".$data['sidebar_logo']) : asset('assets/img/profile-photos/1.png') }}"
                                        style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto"
                                        height="150px"
                                        width="150px" alt="">
                                    <input type="file" class="favicon d-block image" name="sidebar_logo" accept=".jpg, .jpeg, .png">
                                    <p class="text-muted"><small>Allowed JPG, JPEG or PNG. Max
                                        size of
                                        2000kB</small></p>
                                </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-flex flex-column">
                                <div class="form-group">
                                    <label class="mx-0 text-bold d-block">favicon</label>
                                    <img id="avatar"
                                        src="{{ $data['favicon'] != NULL ? asset("/storage/images/logo/".$data['favicon']) : asset('assets/img/profile-photos/1.png') }}"
                                        style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto"
                                        height="150px"
                                        width="150px" alt="">
                                    <input type="file" class="favicon d-block image" name="favicon" accept=".jpg, .jpeg, .png">
                                    <p class="text-muted"><small>Allowed JPG, JPEG or PNG. Max
                                        size of
                                        2000kB</small></p>
                                </div>
                                </div>
                            </div>
                       </div>



              </div>
              <div class="card-footer">
                <div class="d-flex justify-content-end">
                  <button type="button" class="btn btn-secondary me-2" onclick="window.history.back();">
                    Cancel
                  </button>
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>
          </div>
    </div>
  </div>
@endsection

@section('css')
@endsection
@section('script')
  <script>
    $(document).ready(function () {
      let select2Role = $('#select2Role');

      $("#formUpdate").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url = form.attr("action");
        let data = new FormData(this);
        $.ajax({
          beforeSend: function () {
            btnSubmit.addClass("disabled").html("<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ...").prop("disabled", "disabled");
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          success: function (response) {
            let errorEdit = $('#errorEdit');
            errorEdit.css('display', 'none');
            errorEdit.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              setTimeout(function () {
                if (!response.redirect || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
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

      select2Role.select2({
        dropdownParent: select2Role.parent(),
        searchInputPlaceholder: 'Cari Role',
        width: '100%',
        ajax: {
          url: "{{ route('backend.roles.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      });

      $(".image").change(function () {
        let thumb = $(this).parent().find('img');
        if (this.files && this.files[0]) {
          let reader = new FileReader();
          reader.onload = function (e) {
            thumb.attr('src', e.target.result);
          }
          reader.readAsDataURL(this.files[0]);
        }
      });
    });
  </script>
@endsection
