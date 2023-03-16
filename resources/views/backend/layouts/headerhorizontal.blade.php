<header id="page-topbar" class="ishorizontal-topbar">

    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ url('backend/dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="" height="50">
                    </span>
                </a>

                <a href="{{ url('backend/dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="" height="50">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <div style="padding-top:15px;" class="d-none d-sm-block ms-5 align-self-center">
                @component('components.breadcrumb', ['page_breadcrumbs' => $page_breadcrumbs])
                    @slot('title'){{ $config['page_title'] }}@endslot
                @endcomponent
           </div>
        </div>

        <div class="d-flex">
            <div class="dropdown">
                <button type="button" class="btn header-item"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-sm" data-eva="search-outline"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
                    <form class="p-2">
                        <div class="search-box">
                            <div class="position-relative">
                                <input type="text" class="form-control bg-light border-0" placeholder="Search...">
                                <i class="search-icon" data-eva="search-outline" data-eva-height="26" data-eva-width="26"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



            <div class="dropdown d-none d-lg-inline-block">
                <button type="button" class="btn header-item noti-icon"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-sm" data-eva="grid-outline"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-0 font-size-15"> Web Apps </h5>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small fw-semibold text-decoration-underline"> View All</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>



            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle" id="right-bar-toggle">
                    <i class="icon-sm" data-eva="settings-outline"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"  src=" {{ isset(Auth::user()->image) ? asset("storage/images/thumbnail/".Auth::user()->image) : asset('assets/img/profile-photos/1.png') }}"
                    alt="Header Avatar">
                </button>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="p-3 border-bottom">
                        <h6 class="mb-0">{{Auth::user()->name}}</h6>
                        <p class="mb-0 font-size-11 text-muted">{{Auth::user()->email}}</p>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item d-flex align-items-center" href="{{ url('backend/settings') }}"><i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Settings</span></a>
                    <a class="dropdown-item" href="{{ route('logout') }}"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Logout</span></a>
                </div>
            </div>
        </div>
    </div>
    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                <div class="collapse navbar-collapse" id="topnav-menu-content">
                     {!! MenuTop::sidebar() !!}
                </div>
            </nav>
        </div>
    </div>
</header>
