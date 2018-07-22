<!-- Navigation Bar-->
<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">

            <!-- Logo container-->
            <div class="logo">
                <!-- Text Logo -->
                <!--<a href="index.html" class="logo">-->
                <!--UBold-->
                <!--</a>-->
                <!-- Image Logo -->
                <a href="/" class="logo">
                    <img src="{{URL::asset('/images/Archeryosa.jpg')}}" alt="" height="28" class="logo-lg">
                    <img src="{{URL::asset('/images/Archeryosa.jpg')}}" alt="" height="20" class="logo-sm">
                </a>

            </div>
            <!-- End Logo container-->


            <div class="menu-extras topbar-custom">

                <ul class="list-inline float-right mb-0">

                    <li class="menu-item list-inline-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>
                    {{--<li class="list-inline-item dropdown notification-list">--}}
                        {{--<a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"--}}
                           {{--aria-haspopup="false" aria-expanded="false">--}}
                            {{--<i class="dripicons-bell noti-icon"></i>--}}
                            {{--<span class="badge badge-pink noti-icon-badge">4</span>--}}
                        {{--</a>--}}
                        {{--<div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-lg" aria-labelledby="Preview">--}}
                            {{--<!-- item-->--}}
                            {{--<div class="dropdown-item noti-title">--}}
                                {{--<h5><span class="badge badge-danger float-right">5</span>Notification</h5>--}}
                            {{--</div>--}}

                            {{--<!-- item-->--}}
                            {{--<a href="javascript:void(0);" class="dropdown-item notify-item">--}}
                                {{--<div class="notify-icon bg-success"><i class="icon-bubble"></i></div>--}}
                                {{--<p class="notify-details">Robert S. Taylor commented on Admin<small class="text-muted">1 min ago</small></p>--}}
                            {{--</a>--}}

                            {{--<!-- item-->--}}
                            {{--<a href="javascript:void(0);" class="dropdown-item notify-item">--}}
                                {{--<div class="notify-icon bg-info"><i class="icon-user"></i></div>--}}
                                {{--<p class="notify-details">New user registered.<small class="text-muted">1 min ago</small></p>--}}
                            {{--</a>--}}

                            {{--<!-- item-->--}}
                            {{--<a href="javascript:void(0);" class="dropdown-item notify-item">--}}
                                {{--<div class="notify-icon bg-danger"><i class="icon-like"></i></div>--}}
                                {{--<p class="notify-details">Carlos Crouch liked <b>Admin</b><small class="text-muted">1 min ago</small></p>--}}
                            {{--</a>--}}

                            {{--<!-- All-->--}}
                            {{--<a href="javascript:void(0);" class="dropdown-item notify-item notify-all">--}}
                                {{--View All--}}
                            {{--</a>--}}

                        {{--</div>--}}
                    {{--</li>--}}

                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src="{{URL::asset('/images/avatargrey.png')}}" alt="user" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">

                            @if(!Auth::check())
                                <a href="/login" class="dropdown-item notify-item">
                                    <i class="md md-account-circle"></i> <span>Login</span>
                                </a>
                                <a href="/register" class="dropdown-item notify-item">
                                    <i class="md  md-face-unlock"></i> <span>Register</span>
                                </a>
                            @else
                                <a href="/logout" class="dropdown-item notify-item">
                                    <i class="md md-settings-power"></i> <span>Logout</span>
                                </a>
                            @endif
                        </div>
                    </li>

                </ul>
            </div>
            <!-- end menu-extras -->

            <div class="clearfix"></div>

        </div> <!-- end container -->
    </div>
    <!-- end topbar-main -->

    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                    @if (Auth::check())
                        <li class="has-submenu">
                            <a href="javascript:;"><i class="md md-account-box"></i>My Account</a>
                            <ul class="submenu">
                                <li>
                                    <a href="/profile">Profile</a>
                                </li>
                                <li>
                                    <a href="/myevents">My Events</a>
                                </li>
                                <li>
                                    <a href="javascript:;">My Results</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    {{--<li class="has-submenu">--}}
                        {{--<a href="javascript:;"><i class="md md-dashboard"></i>Results</a>--}}
                        {{--<ul class="submenu">--}}
                            {{--<li>--}}
                                {{--<a href="/profile">Profile</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="/myevents">My Events</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;">My Results</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}

                    <li class="has-submenu">
                        <a href="javascript:;"><i class="md md-color-lens"></i>Events</a>
                        <ul class="submenu">
                            <li><a href="/upcomingevents">Upcoming Events</a></li>
                            <li><a href="/previousevents">Event Results</a></li>
                        </ul>
                    </li>

                    @if(Auth::check() && Auth::user()->roleid <= 3)
                        <li class="has-submenu">
                            <a href="jacascript:;"><i class="md md-layers"></i>Admin</a>
                            <ul class="submenu megamenu">

                                @if(Auth::user()->roleid == 1)
                                    <li>
                                        <ul>
                                            <li>
                                                <span>Admin</span>
                                            </li>
                                            <li><a href="/admin/users">User Management</a></li>
                                        </ul>
                                    </li>
                                @endif

                                @if(Auth::user()->roleid <=2)
                                    <li>
                                    <ul>
                                        <li>
                                            <span>Setup</span>
                                        </li>
                                        <li><a href="/admin/clubs">Clubs</a></li>
                                        <li><a href="/admin/divisions">Divisions</a></li>
                                        <li><a href="/admin/organisations">Organisations</a></li>
                                        <li><a href="/admin/rounds">Rounds</a></li>
                                        <li><a href="/admin/competitions">Competitions</a></li>
                                    </ul>
                                </li>
                                @endif

                                <li>
                                    <ul>
                                        <li>
                                            <span>Events</span>
                                        </li>
                                        <li><a href="form-elements.html">Manage Events</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </li>
                    @endif

                </ul>
                <!-- End navigation menu -->
            </div> <!-- end #navigation -->
        </div> <!-- end container -->
    </div> <!-- end navbar-custom -->
</header>
<!-- End Navigation Bar-->