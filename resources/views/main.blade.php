<!-- =========================================================
* Argon Dashboard PRO v1.1.0
=========================================================
* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro
* Copyright 2019 Creative Tim (https://www.creative-tim.com)
* Coded by Creative Tim
=========================================================
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>Sampelku</title>
    <!-- Favicon -->
    <link rel="icon" href="/assets/img/brand/favicon.png" type="image/png">
    <!-- Fonts -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"> -->
    <!-- Icons -->
    <link rel="stylesheet" href="/assets/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    <!-- Additional Stylesheet -->
    @yield('stylesheet')
    <!-- Argon CSS -->
    <link rel="stylesheet" href="/assets/css/argon.css?v=1.1.0" type="text/css">
    <link rel="stylesheet" href="/assets/style.css">

</head>

<body>
    <!-- Sidenav -->
    <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
        <div class="scrollbar-inner">
            <!-- Brand -->
            <div class="sidenav-header d-flex align-items-center">
                <a class="navbar-brand" href="/">
                    <img src="/assets/img/brand/blue.png" class="navbar-brand-img" alt="...">
                </a>
                <div class="ml-auto">
                    <!-- Sidenav toggler -->
                    <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-inner">
                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                    <ul class="navbar-nav">
                        @hasanyrole('admin')
                        <li class="nav-item">
                            <a class="nav-link @if(url()->current() == url('/')) active @endif" href="/">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-home text-primary text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1">Penggantian Sampel</span>
                            </a>
                        </li>
                        @endhasrole

                        @hasanyrole('pml|admin|pcl')
                        <li class="nav-item">
                            <a class="nav-link @if(substr_count(url()->current(), 'my-sample') == 1) active @endif" href="/my-sample">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-plus-circle text-warning text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1">Sampel Saya</span>
                            </a>
                        </li>
                        @endhasrole

                        @hasanyrole('pcl')
                        <li class="nav-item">
                            <a class="nav-link @if(substr_count(url()->current(), 'status') == 1) active @endif" href="/status">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-calendar-grid-58 text-danger text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1">Status Penggantian Sampel</span>
                            </a>
                        </li>
                        @endhasrole

                        @hasrole('admin|pml')
                        <li class="nav-item">
                            <a class="nav-link @if((substr_count(url()->current(), 'recommendation') == 1) || (substr_count(url()->current(), 'status') == 1)) active @endif" href="#navbar-components" data-toggle="collapse" role="button" aria-expanded="@if((substr_count(url()->current(), 'recommendation') == 1) || (substr_count(url()->current(), 'status') == 1)) true @else false @endif" aria-controls="navbar-components">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-calendar-grid-58 text-danger text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text">Ganti Sampel</span>
                            </a>
                            <div class="collapse @if((substr_count(url()->current(), 'recommendation') == 1) || (substr_count(url()->current(), 'status') == 1)) show @endif" id="navbar-components">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link @if(substr_count(url()->current(), 'status') == 1) active @endif" href="/status">
                                            <span class="nav-link-text ms-1">Status Penggantian Sampel</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if(substr_count(url()->current(), 'recommendation') == 1) active @endif" href="/recommendation">
                                            <span class="nav-link-text ms-1">Pengajuan Penggantian Sampel</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endhasrole
                    </ul>

                    @hasrole('admin')
                    <hr class="my-3">
                    <h6 class="navbar-heading p-0 text-muted">Admin</h6>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link @if(substr_count(url()->current(), 'users') == 1) active @endif" href="/users">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1">Petugas</span>
                            </a>
                        </li>
                    </ul>
                    @endhasrole()
                </div>
            </div>
        </div>
    </nav>
    <!-- Main content -->
    <div class="main-content" id="panel">
        <!-- Topnav -->

        <!-- Page content -->
        <div class="main-content" id="panel">
            <!-- Navigation bar -->
            <nav class="navbar navbar-top navbar-expand navbar-dark bg-warning border-bottom">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Search form -->
                        <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                            <div class="form-group mb-0">
                                <div class="input-group input-group-alternative input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Sampelku" disabled type="text">
                                </div>
                            </div>
                            <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </form>
                        <!-- Navbar links -->
                        <ul class="navbar-nav align-items-center ml-md-auto">
                            <li class="nav-item d-xl-none">
                                <!-- Sidenav toggler -->
                                <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                                    <i class="ni ni-zoom-split-in"></i>
                                </a>
                                <!-- <img src="/assets/img/brand/blue.png" width="50%"/> -->
                            </li>

                        </ul>
                        <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="media align-items-center">
                                        <span class="avatar avatar-sm rounded-circle">
                                            <img alt="" @if(Auth::user()->avatar) src="{{asset('storage/'.Auth::user()->avatar)}}" @else src="/assets/img/brand/favicon.png" @endif>
                                        </span>
                                        <div class="media-body ml-2 d-none d-lg-block">
                                            <span class="mb-0 text-sm  font-weight-bold">{{ Auth::user()->name }}</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Welcome!</h6>
                                    </div>
                                    <a href="#!" class="dropdown-item">
                                        <i class="ni ni-single-02"></i>
                                        <span>Profile</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="ni ni-user-run"></i>
                                        <span>Logout</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            @yield('container')
            <!-- Footer -->
            <div class="container-fluid">
                <footer class="footer pt-0">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6">
                            <div class="copyright text-center text-lg-left text-muted">
                                &copy; 2025 <a href="#" class="font-weight-bold ml-1" target="_blank">BPS Kota Surabaya</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <div class="sk-cube-background" id="loading-background" style="display: none;">
                <div class="sk-cube-grid">
                    <div class="sk-cube sk-cube1"></div>
                    <div class="sk-cube sk-cube2"></div>
                    <div class="sk-cube sk-cube3"></div>
                    <div class="sk-cube sk-cube4"></div>
                    <div class="sk-cube sk-cube5"></div>
                    <div class="sk-cube sk-cube6"></div>
                    <div class="sk-cube sk-cube7"></div>
                    <div class="sk-cube sk-cube8"></div>
                    <div class="sk-cube sk-cube9"></div>
                </div>
            </div>
        </div>



    </div>

    <!-- Argon Scripts -->
    <!-- Core -->
    <script src="/assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendor/js-cookie/js.cookie.js"></script>
    <script src="/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
    <!-- Optional JS -->
    @yield('optionaljs')
    <!-- Argon JS -->
    <script src="/assets/js/argon.js?v=1.1.0"></script>
</body>

</html>