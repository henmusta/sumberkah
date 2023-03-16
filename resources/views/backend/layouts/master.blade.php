<!doctype html>
<html lang="en">
<!-- Mirrored from themesbrand.com/borex/layouts/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 30 Dec 2022 12:49:54 GMT -->
    <head>
        <meta charset="utf-8" />
        <title>{{Setting::get_setting()->name}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/backend/images/favicon.ico')}}">

        @include('backend.layouts.headercss')
        <style>
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

        <!-- JAVASCRIPT -->
        @include('backend.layouts.footer')
    @include('backend.layouts.footerjs')
    </body>


<!-- Mirrored from themesbrand.com/borex/layouts/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 30 Dec 2022 12:49:54 GMT -->
</html>
