<!doctype html>
<html lang="en">


<!-- Mirrored from themesbrand.com/borex/layouts/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 30 Dec 2022 12:50:38 GMT -->
<head>

        <meta charset="utf-8" />
        <title>Login | {{Setting::get_setting()->name}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{URL::to('storage/images/logo/'.Setting::get_setting()->favicon)}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/backend/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/backend/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"  />
    </head>
@if(Setting::get_setting()->layout_mode == 'dark')
    @php($bg = 'bg-dark')
@else
    @php($bg = 'bg-white')
@endif
   <body>
        <section class="row d-flex justify-content-center align-items-center w-100 {{$bg}} g-0 p-5" style="min-height: 100vh">
            <div class="col-lg-4">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5">
                                    <a href="index-2.html" class="d-block auth-logo">
                                        <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="" height="60" class="auth-logo-dark me-start">
                                        <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="" height="60" class="auth-logo-light me-start">
                                    </a>
                                </div>
                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">Login !</h5>
                                    </div>
                                    <form id="formStore" class="custom-form mt-4 pt-2 login" method="POST" action="{{ route('backend.login') }}">
                                        @csrf
                                        @error('email')
                                            <span class="invalid-feedback"  style="display:block;">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="form-floating form-floating-custom mb-4">
                                            <input type="text" name="email" class="form-control" id="input-username" placeholder="Enter User Name" required>
                                            <label for="input-username">Username</label>
                                            <div class="form-floating-icon">
                                                <i class="fa fa-id-badge"></i>
                                            </div>

                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" style="display:block;">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="form-floating form-floating-custom mb-4 auth-pass-inputgroup">
                                            <input type="password"  name="password" class="form-control pe-5" id="password-input" placeholder="Enter Password">

                                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                                <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                            </button>
                                            <label for="input-password">Password</label>
                                            <div class="form-floating-icon">
                                                <i class="fa fa-key"></i>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- JAVASCRIPT -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="{{asset('assets/backend/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
       <script>
        $(document).ready(function () {
    $("#formStore").submit(function (e) {
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
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          success: function (response) {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            let errorCreate = $('#errorCreate');
            errorCreate.css('display', 'none');
            errorCreate.find('.alert-text').html('');
            if (response.status === "success") {
                toastr.success(response.message, 'Success !');
                window.location.href = response.redirect;
            } else {
                Swal.fire({
                    title: 'Gagal Untuk Login!',
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location.reload();
                });
            }
          },
          error: function (response) {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
                Swal.fire({
                    title: 'Gagal Untuk Login Perikasa Email Dan Password!',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location.reload();
                });
          }
        });
      });

        });



      </script>
    </body>

<!-- Mirrored from themesbrand.com/borex/layouts/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 30 Dec 2022 12:50:38 GMT -->
</html>
