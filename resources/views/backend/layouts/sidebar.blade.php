

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->sidebar_logo)}}" alt="" height="22">
            </span>
        </a>

        <a href="index.html" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->sidebar_logo)}}" alt="" height="50">
            </span>
            <span class="logo-sm">
                <img src="{{URL::to('storage/images/logo/'.Setting::get_setting()->icon)}}" alt="" height="22">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 header-item vertical-menu-btn topnav-hamburger">
        <div class="hamburger-icon">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </button>

    <div data-simplebar="init" class="sidebar-menu-scroll"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: -17px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px;">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            {!! Menu::sidebar() !!}
        </div>
        <!-- Sidebar -->


    </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 1273px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 545px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></div>

