<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">
            <div class="logo">
                <a href="/" class="logo">
                    <img src="{{URL::asset('/images/Archeryosa.jpg')}}" alt="" height="28" class="logo-lg">
                    <img src="{{URL::asset('/images/Archeryosa.jpg')}}" alt="" height="20" class="logo-sm">
                </a>
            </div>
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

                    @if (Auth::check() && !empty(Auth::user()->getcart()))

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" href="/checkout" role="button" >
                                <i class="dripicons-cart noti-icon"></i>
                                <span class="badge badge-pink noti-icon-badge">{{count(Auth::user()->getcartitems())}}</span>
                            </a>
                        </li>

                    @endif

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

                    @if (Auth::check() && Auth::user()->scoringEnabled())
                        <li >
                            <a href="/scoring"><i class=" md-star"></i>Submit Scores!</a>
                        </li>
                    @endif

                    @if (Auth::check())
                        <li class="has-submenu">
                            <a href="#"><i class="md md-account-box"></i>My Account</a>
                            <ul class="submenu">
                                <li>
                                    <a href="/profile">Profile</a>
                                </li>
                                <li>
                                    <a href="/profile/myevents">My Events</a>
                                </li>
                                <li>
                                    {{--<a href="/profile/myresults">My Results</a>--}}
                                </li>
                            </ul>
                        </li>
                    @endif

                    <li class="has-submenu">
                        <a href="#"><i class="ion-trophy"></i>Records</a>
                        <ul class="submenu">
                            <li><a href="/records/anz">ArcheryNZ Records</a></li>
                        </ul>
                    </li>


                    <li class="has-submenu">
                        <a href="#"><i class="ion-trophy"></i>Results</a>
                        <ul class="submenu">
                            @auth
                                <li><a href="{{route('publicprofile', ['username' => Auth()->user()->username])}}">My Profile!</a></li>
                            @endauth
                            <li><a href="/events/results">Event Results</a></li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="md-account-child"></i>Events</a>
                        <ul class="submenu">
                            {{--<li><a href="/events/create">Create an Event</a></li>--}}
                            <li><a href="/events">Event Registration</a></li>
                        </ul>
                    </li>

                    @if(Auth::check() && Auth::user()->roleid <= 3)
                        <li class="has-submenu">
                            <a href="#"><i class="md md-settings"></i>Admin</a>
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
                                            <li><a href="/admin/divisionages">Division Age Groups</a></li>
                                            <li><a href="/admin/organisations">Organisations</a></li>
                                            <li><a href="/admin/rounds">Rounds</a></li>
                                            <li><a href="/admin/schools">Schools</a></li>
                                        </ul>
                                    </li>

                                        <li>
                                            <ul>
                                                <li>
                                                    <span>Results</span>
                                                </li>
                                                <li><a href="#">Rankings</a></li>
                                                <li><a href="#">Results</a></li>
                                                <li><a href="/admin/reports">Reports</a></li>

                                            </ul>
                                        </li>
                                @endif

                                <li>
                                    <ul>
                                        <li>
                                            <span>Events</span>
                                        </li>
                                        <li><a href="/events/manage">Manage Events</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</header>
