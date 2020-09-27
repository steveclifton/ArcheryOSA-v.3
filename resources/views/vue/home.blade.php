<!DOCTYPE html>
<html>
    @include('vue.template.head')

    <body class="loading"
          data-layout-mode="detached"
          data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": true}, "topbar": {"color": "dark"}}'>

        @if(getenv('APP_LIVE'))
            <noscript>
                <iframe src="https://www.googletagmanager.com/ns.html?id={{getenv('GOOGLE_GTM')}}"
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
                                <img src="{{URL::asset('/images/avatargrey.png')}}" alt="user-image" class="rounded-circle">
                                <span class="pro-user-name ml-1">
                                    {{ Auth::check() ? ucfirst(Auth::user()->firstname ?? '') : 'Welcome!' }}
                                </span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">

                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">{{ Auth::check() ? 'Welcome ' . ucfirst(Auth::user()->firstname ?? '') : 'Login' }}</h6>
                                </div>

                                @auth()
                                    <a href="/profile" class="dropdown-item notify-item">
                                        <i class="fe-user"></i>
                                        <span>My Account</span>
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a href="/logout" class="dropdown-item notify-item">
                                        <i class="fe-log-out"></i>
                                        <span>Logout</span>
                                    </a>
                                @elseauth()
                                    <a href="/login" class="dropdown-item notify-item">
                                        <i class="fe-user"></i>
                                        <span>Login</span>
                                    </a>
                                @endauth

                            </div>
                        </li>
                    </ul>

                    <div class="logo-box">
                        <a href="/" class="logo logo-light text-center">
                            <span class="logo-sm" style="padding-left: 15px">
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
                            <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="left-side-menu">
                <div class="h-100" data-simplebar>
                    <div class="user-box text-center">
                        <img src="{{URL::asset('/images/avatargrey.png')}}" alt="user-img" class="rounded-circle avatar-md">
                        @auth()
                            <div class="dropdown">
                                <a href="javascript: void(0);" class="text-dark font-weight-normal  h5 mt-2 mb-1 d-block"
                                   data-toggle="dropdown">{{ ucwords(Auth::user()->full_name) }}</a>
                            </div>
                            <p class="text-muted">{{ Auth::user()->getUserType() }}</p>
                        @endauth()
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
                                            <router-link :to="{name: 'Admin-CreateEvent'}">Create New</router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'Admin-Eventlistings'}">Manage Events</router-link>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#reportingSidebar" data-toggle="collapse">
                                    <i data-feather="shopping-cart"></i>
                                    <span> Reporting </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="reportingSidebar">
                                    <ul class="nav-second-level">
                                        <li>
                                            <router-link :to="{name: 'Admin-CreateEvent'}" >Create New</router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'Admin-Eventlistings'}">Manage Events</router-link>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            @if (Auth::check() && Auth::user()->isSuperAdmin())
                                <li>
                                    <a href="#reportingSidebar" data-toggle="collapse">
                                        <i data-feather="shopping-cart"></i>
                                        <span> Site Config </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="reportingSidebar">
                                        <ul class="nav-second-level">
                                            <li>
                                                <router-link :to="{name: 'Admin-Clubs'}">Clubs</router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="content-page">

                <div class="content" >
                    {{-- Main Content--}}
                    <router-view></router-view>

                </div>

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                               ArcheryOSA {{date('Y')}}
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-right footer-links d-none d-sm-block">
                                    <a href="/privacy">Privacy</a>
                                    <a href="mailto:info@archeryosa.com">Contact</a>
                                    <a href="https://github.com/steveclifton/ArcheryOSA-v.3">Github</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>

            </div>
        </div>
    </div>

    <script src="/vue/js/app.js"></script>

    </body>
</html>