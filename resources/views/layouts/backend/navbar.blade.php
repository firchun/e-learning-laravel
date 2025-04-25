<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        {{-- <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div> --}}


        <span class="p-1 border border-danger rounded text-danger font-weight-bold" style="width: 20vh;">
            <center>
                {{ $semester->semester ?? 'Semester' }} {{ $semester->tahun_ajaran ?? '' }}
            </center>
        </span>
    </div>
    <div class="header-right">
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>
        {{-- <div class="user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                    <i class="icon-copy dw dw-notification"></i>
                    <span class="badge notification-active"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="notification-list mx-h-350 customscroll">
                        <ul>
                            <li>
                                <a href="#">
                                    <img src="vendors/images/img.jpg" alt="" />
                                    <h3>John Doe</h3>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing
                                        elit, sed...
                                    </p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="vendors/images/photo1.jpg" alt="" />
                                    <h3>Lea R. Frith</h3>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing
                                        elit, sed...
                                    </p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="vendors/images/photo2.jpg" alt="" />
                                    <h3>Erik L. Richards</h3>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing
                                        elit, sed...
                                    </p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="vendors/images/photo3.jpg" alt="" />
                                    <h3>John Doe</h3>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing
                                        elit, sed...
                                    </p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="vendors/images/photo4.jpg" alt="" />
                                    <h3>Renee I. Hansen</h3>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing
                                        elit, sed...
                                    </p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="vendors/images/img.jpg" alt="" />
                                    <h3>Vicki M. Coleman</h3>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing
                                        elit, sed...
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="{{ Auth::user()->avatar == null ? asset('img/user.png') : Storage::url(Auth::user()->avatar) }}"
                            alt="" />
                    </span>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="{{ url('/profile') }}"><i class="dw dw-user1"></i> Profile</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                        <i class="dw dw-logout"></i> Log Out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
