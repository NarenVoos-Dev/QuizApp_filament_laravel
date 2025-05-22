<nav class="left-auto px-0 mx-4 mt-4 shadow-none navbar navbar-main navbar-expand-lg border-radius-xl position-sticky blur shadow-blur top-1 z-index-sticky"
    id="navbarBlur" navbar-scroll="true">
    <div class="px-3 py-1 container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="px-0 pt-1 pb-0 mb-0 bg-transparent breadcrumb me-sm-6 me-5">
                <li class="text-sm breadcrumb-item">
                    <a class="opacity-5 text-dark" href="#">Inicio</a>
                </li>
                <li class="text-sm breadcrumb-item text-dark active" aria-current="page">
                    @yield('title', 'Dashboard')
                </li>
            </ol>
            <h6 class="mb-0 font-weight-bolder">@yield('title', 'Dashboard')</h6>
        </nav>

        <div class="mt-2 collapse navbar-collapse mt-sm-0 me-md-0 me-sm-4 justify-content-end" id="navbar">

            <ul class="navbar-nav justify-content-end">

                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="#" class="p-0 nav-link text-body" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>


                <li class="p-4 nav-item dropdown d-flex align-items-center">
                    <a href="#" class="p-0 nav-link text-body" id="dropdownMenuButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <h5 class="d-sm-inline d-none text-body font-weight-bold">
                            {{ Auth::check() ? Auth::user()->name : 'Iniciar Sesión' }}
                        </h5>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>