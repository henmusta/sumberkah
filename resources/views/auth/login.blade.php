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

    </head>
@if(Setting::get_setting()->layout_mode == 'dark')
    @php($bg = 'bg-dark')
@else
    @php($bg = 'bg-white')
@endif
   <body>
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0 align-items-center {{$bg}}">
                    <div class="col-xxl-4 col-lg-4 col-md-6">
                        <div id="errorCreate" class="mb-3" style="display:none;">
                            <div class="alert alert-danger" role="alert">
                              <div class="alert-text">
                              </div>
                            </div>
                          </div>


                        <div class="row justify-content-center g-0">
                            <div class="col-xl-9">
                                <div class="p-4">
                                    <div class="card mb-0">
                                        <div class="card-body">
                                            <div class="auth-full-page-content rounded d-flex p-3 my-2">
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
                                                                <h5 class="mb-0">Selamat Datang !</h5>
                                                                <p class="text-muted mt-2">Sign in to continue to Admin Menu.</p>
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
                                                                        <i data-eva="people-outline"></i>
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
                                                                        <i data-eva="lock-outline"></i>
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
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
                    <div class="col-xxl-8 col-lg-8 col-md-6">
                        <div class="auth-bg {{$bg}} py-md-5 p-4 d-flex">
                            <div class="bg-overlay bg-white"></div>
                            <!-- end bubble effect -->
                            <div class="row justify-content-center align-items-center">
                                <div class="col-xl-8">
                                    <div class="mt-4">
                                        <img src="assets/backend/images/maintenance.png" width="1500px" class="img-fluid" alt="">
                                    </div>
                                    <div class="p-0 p-sm-4 px-xl-0 py-5">
                                        <div id="reviewcarouselIndicators" class="carousel slide auth-carousel" data-bs-ride="carousel">
                                            <div class="carousel-indicators carousel-indicators-rounded">
                                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                {{-- <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button> --}}
                                            </div>

                                            <!-- end carouselIndicators -->
                                            <div class="carousel-inner w-75 mx-auto">
                                                <div class="carousel-item active">
                                                    <div class="testi-contain text-center">
                                                        <h5 class="font-size-20 mt-4">{{Setting::get_setting()->name}}
                                                        </h5>
                                                        <p class="font-size-15 text-muted mt-3 mb-0">Sumber Karya Berkah</p>
                                                    </div>
                                                </div>

                                                {{-- <div class="carousel-item">
                                                    <div class="testi-contain text-center">
                                                        <h5 class="font-size-20 mt-4">“Our task must be to
                                                            free widening our circle”</h5>
                                                        <p class="font-size-15 text-muted mt-3 mb-0">
                                                            Curabitur eget nulla eget augue dignissim condintum Nunc imperdiet ligula porttitor commodo elementum
                                                            Vivamus justo risus fringilla suscipit faucibus orci luctus
                                                            ultrices posuere cubilia curae lectus non ultricies cursus.
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-center">
                                                        <h5 class="font-size-20 mt-4">“I've learned that
                                                            people will forget what you”</h5>
                                                        <p class="font-size-15 text-muted mt-3 mb-0">
                                                            Pellentesque lacinia scelerisque arcu in aliquam augue molestie rutrum magna Fusce dignissim dolor id auctor accumsan
                                                            vehicula dolor
                                                            vivamus feugiat odio erat sed vehicula lorem tempor quis Donec nec scelerisque magna
                                                        </p>
                                                    </div>
                                                </div> --}}
                                            </div>
                                            <!-- end carousel-inner -->
                                        </div>
                                        <!-- end review carousel -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>

        <!-- JAVASCRIPT -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="{{asset('assets/backend/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/backend/libs/metismenujs/metismenujs.min.js')}}"></script>
        <script src="{{asset('assets/backend/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/backend/libs/eva-icons/eva.min.js')}}"></script>

        <script src="{{asset('assets/backend/js/pages/pass-addon.init.js')}}"></script>

       <script src="{{asset('assets/backend/js/pages/eva-icon.init.js')}}"></script>
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                //
                Swal.fire({
                    title: 'Berhasil Login!',
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location.href = response.redirect;
                });
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
