<!DOCTYPE html>
<html>
<head>
    <title>Admin | Archery OSA</title>
    <link rel="stylesheet" href="/vue/css/app.css">
    <link href="/vue/css/all.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />

{{--    <link href="/vue/css/bootstrap-modern-dark.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />--}}
{{--    <link href="/vue/css/app-modern-dark.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />--}}

    <link href="/vue/css/icons.min.css" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>



<body class="loading"
      data-layout-mode="detached"
      data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": true}, "topbar": {"color": "dark"}}'>

@if(0)
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PL2ND3T"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
@endif
<div id="app">
    <div id="wrapper">

        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-right mb-0">
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="/images/users/steve.png" alt="user-image" class="rounded-circle">
                            <span class="pro-user-name ml-1">
                                Steve
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>My Account</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-settings"></i>
                                <span>Settings</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>

                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="/" class="logo logo-dark text-center">
                        <span class="logo-sm" style="padding-left: 5px">
    {{--                                <img src="/images/archeryosa-logo-white-small.png" alt="" height="22">--}}
    {{--                                <span class="logo-lg-text-light">UBold</span>--}}
                        </span>
                        <span class="logo-lg">
                            <img src="/images/archeryosa-logo-white.png" alt="" height="20">
                    <!-- <span class="logo-lg-text-light">U</span> -->
                        </span>
                    </a>

                    <a href="/" class="logo logo-light text-center">
                        <span class="logo-sm" style="padding-left: 5px">
    {{--                        <img src="/images/archeryosa-logo-white-small.png" alt="" height="22">--}}
                            <span class="logo-lg-text-light">AOSA</span>
                        </span>
                        <span class="logo-lg">
                            <img src="/images/archeryosa-logo-white.png" alt="" height="20">
                        </span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>

                    <li>
                        <!-- Mobile menu toggle (Horizontal Layout)-->
                        <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>

                </ul>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="left-side-menu">

            <div class="h-100" data-simplebar>
                <div class="user-box text-center">
                    <img src="/images/users/steve.png" alt="user-img" title="Mat Helme"
                         class="rounded-circle avatar-md">
                    <div class="dropdown">
                        <a href="javascript: void(0);" class="text-dark font-weight-normal dropdown-toggle h5 mt-2 mb-1 d-block"
                           data-toggle="dropdown">Stanley Parker</a>
                        <div class="dropdown-menu user-pro-dropdown">

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user mr-1"></i>
                                <span>My Account</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-settings mr-1"></i>
                                <span>Settings</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-lock mr-1"></i>
                                <span>Lock Screen</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-log-out mr-1"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </div>
                    <p class="text-muted">Admin</p>
                </div>

                <div id="sidebar-menu">
                    <ul id="side-menu">
                        <li class="menu-title">Navigation</li>

                        <li>
                            <router-link :to="{name: 'Admin-Home'}" exact>
                                <i data-feather="airplay"></i>
                                <span> Dashboard </span>
                            </router-link>
                        </li>

                        <li>
                            <a href="#eventsSidebar" data-toggle="collapse">
                                <i data-feather="shopping-cart"></i>
                                <span> Events </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="eventsSidebar">
                                <ul class="nav-second-level">
                                    <li>
                                        <router-link :to="{name: 'Admin-CreateEvent'}" >Create New</router-link>
                                    </li>
                                    <li>
                                        <router-link :to="{name: 'Admin-EventsList'}" >Manage Events</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li>
                            <a href="#sidebarCrm" data-toggle="collapse">
                                <i data-feather="users"></i>
                                <span> Reporting </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarCrm">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="crm-dashboard.html">Dashboard</a>
                                    </li>
                                    <li>
                                        <a href="crm-contacts.html">Contacts</a>
                                    </li>
                                    <li>
                                        <a href="crm-opportunities.html">Opportunities</a>
                                    </li>
                                    <li>
                                        <a href="crm-leads.html">Leads</a>
                                    </li>
                                    <li>
                                        <a href="crm-customers.html">Customers</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                    </ul>

                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>

        <div class="content-page">
            <div class="content" >
                <router-view></router-view>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                           ArcheryOSA
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="javascript:void(0);">About Us</a>
                                <a href="javascript:void(0);">Help</a>
                                <a href="javascript:void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>
</div>

<script src="/vue/js/app.js"></script>
<script src="/vue/js/plugins.js"></script>

</body>




@if(0)
    <div id="app" class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="">
                    <ul class="nav flex-column">
                        <li class="nav-item">

                        </li>
                        <li class="nav-item">
                            <router-link :to="{name: 'events'}" class="nav-link">Events</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{name: 'reports'}" class="nav-link">Reports</router-link>
                        </li>
                    </ul>
                </div>
            </nav>

        </div>
    </div>


@endif

</html>