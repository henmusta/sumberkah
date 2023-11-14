<!doctype html>
<html lang="en">
<!-- Mirrored from themesbrand.com/borex/layouts/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 30 Dec 2022 12:49:54 GMT -->
    <head>
        <meta charset="utf-8" />
        <title>{{Setting::get_setting()->name}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/backend/images/favicon.ico')}}">

        @include('backend.layouts.headercss')
        <style>
            select[readonly]
            {
                pointer-events: none;
            }
            .select2-selection__rendered {
                line-height: 35px !important;
            }
            .select2-container .select2-selection--single {
                height: 35px !important;
            }
            .select2-selection__arrow {
                height: 35px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__clear {

                height: 35px !important;

            }

            .select2-container .select2-selection--single .select2-selection__rendered {
                padding-left: 15px !important;
            }
        </style>
    </head>


    <body data-layout="{{Setting::get_setting()->layout}}"
        data-topbar="{{Setting::get_setting()->topbar_color}}"
        data-sidebar="{{Setting::get_setting()->sidebar_color}}"
        data-layout-mode="{{Setting::get_setting()->layout_mode}}"
        data-layout-scrollable="{{Setting::get_setting()->layout_position == 'fixed' ? 'false' : 'true'}}"
        data-layout-size="{{Setting::get_setting()->layout_width}}"
        data-sidebar-size="{{Setting::get_setting()->sidebar_size}}">

        <div id="layout-wrapper">


            @include('backend.layouts.headervertical')
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">
                @include('backend.layouts.sidebar')
            </div>
            <!-- Left Sidebar End -->
            @include('backend.layouts.headerhorizontal')

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                @yield('content')
                <!-- End Page-content -->


            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- chat offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasActivity" aria-labelledby="offcanvasActivityLabel">
            <div class="offcanvas-header border-bottom">
              <h5 id="offcanvasActivityLabel">Offcanvas right</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              ...
            </div>
        </div>
        <div class="modal fade" id="modalChangePassword" tabindex="-1" role="dialog"
        aria-labelledby="modalChangePasswordLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResetLabel">Ubah Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorChangePassword" class="form-group m-4" style="display:none;">
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                        <div class="alert-text">
                        </div>
                    </div>
                </div>
                <form id="formChangePassword" method="POST" action="{{ route('backend.users.changepassword', Auth::id()) }}">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                             <label>Password Lama <span class="text-danger">*</span></label>
                            <div class="input-group auth-pass-inputgroup ">

                                    <input type="password" id="old_password" name="old_password" class="form-control"
                                        placeholder="Input password lama" />
                                        <button class="btn btn-light shadow-none ms-0" type="button" onclick="myFunction()" id="password-addon"><i class="fa fa-eye"></i></button>
                                </div>
                            </div>
                        <div class="mb-3">
                            <label>Password Baru<span class="text-danger">*</span></label>
                            <div class="input-group auth-pass-inputgroup ">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Input password baru" />
                                <button class="btn btn-light shadow-none ms-0" type="button" onclick="myFunctionnew()" id="password-addon"><i class="fa fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Retype Password Baru<span class="text-danger">*</span></label>
                            <div class="input-group auth-pass-inputgroup ">
                                 <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                                placeholder="Input password baru kembali" />
                                <button class="btn btn-light shadow-none ms-0" type="button" onclick="myFunctionconfirm()" id="password-addon"><i class="fa fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">Submit</button>
                      </div>
                </form>
            </div>
        </div>
    </div>

        <!-- JAVASCRIPT -->
        @include('backend.layouts.footer')
    @include('backend.layouts.footerjs')
    <script type="text/javascript">
            $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
                if (jqxhr.status === 302) {
                    location.reload();
                }
                });
        $(document).ready(function () {

            $("#fullscreen-button").click(function() {
                $( "#fullscreen-button" ).prop('hidden', true);
                $( "#compress-button" ).prop('hidden', false);
                $("#fluid").addClass( 'full');
            });

            $("#compress-button").click(function() {
                $( "#fullscreen-button" ).prop('hidden', false);
                $("#compress-button" ).prop('hidden', true);
                $("#fluid").removeClass( 'full');
            });


                let modalChangePassword = document.getElementById('modalChangePassword');
                const bsChangePassword = new bootstrap.Modal(modalChangePassword);

                modalChangePassword.addEventListener('show.bs.modal', function (event) {

                });

                modalChangePassword.addEventListener('hidden.bs.modal', function (event) {

                });

                $("#formChangePassword").submit(function (e) {
                e.preventDefault();
                let form = $(this);
                let btnSubmit = form.find("[type='submit']");
                let btnSubmitHtml = btnSubmit.html();
                let url = form.attr("action");
                let data = new FormData(this);
                $.ajax({
                  beforeSend: function () {
                    btnSubmit.addClass("disabled").html("<i class='fa fa-spinner fa-pulse fa-fw'></i> Loading ...").prop("disabled", "disabled");
                  },
                  cache: false,
                  processData: false,
                  contentType: false,
                  type: "POST",
                  url: url,
                  data: data,
                  success: function (response) {
                    let errorChangePassword = $('#errorChangePassword');
                    errorChangePassword.css('display', 'none');
                    errorChangePassword.find('.alert-text').html('');
                    btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
                    if (response.status === "success") {
                      toastr.success(response.message, 'Success !');
                      bsChangePassword.hide();
                    } else {
                      toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
                      if (response.error !== undefined) {
                        errorChangePassword.removeAttr('style');
                        $.each(response.error, function (key, value) {
                          errorChangePassword.find('.alert-text').append('<span style="display: block">' + value + '</span>');
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

              $('body').addClass('loaded');
              $('h1').css('color', '#222222');

        });
       function myFunction() {
          var x = document.getElementById("old_password");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
       }
       function myFunctionnew() {
          var x = document.getElementById("password");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
       }
       function myFunctionconfirm() {
          var x = document.getElementById("password_confirmation");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
       }
</script>
    </body>


<!-- Mirrored from themesbrand.com/borex/layouts/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 30 Dec 2022 12:49:54 GMT -->
</html>
