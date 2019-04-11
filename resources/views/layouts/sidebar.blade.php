
  <div class="side-content-wrap">
            <div class="sidebar-left open" data-perfect-scrollbar data-suppress-scroll-x="true">
                <ul class="navigation-left">
                <li class="nav-item {{ request()->is('dashboard/*') ? 'active' : '' }}" data-item="dashboard">
                        <a class="nav-item-hold" href="/home">
                            <i class="nav-icon i-Bar-Chart"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item {{ request()->is('dashboard/*') ? 'active' : '' }}" data-item="clinician">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Male"></i>
                            <span class="nav-text">Clinician</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item {{ request()->is('forms/*') ? 'active' : '' }}" data-item="caregivers">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Male"></i>
                            <span class="nav-text">Care Givers</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item {{ request()->is('datatables/*') ? 'active' : '' }}">
                        <a class="nav-item-hold" href="{{route('basic-tables')}}">
                            <i class="nav-icon i-File-Horizontal-Text"></i>
                            <span class="nav-text">Datatables</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item {{ request()->is('sessions/*') ? 'active' : '' }}" data-item="sessions">
                        <a class="nav-item-hold" href="/test.html">
                            <i class="nav-icon i-Administrator"></i>
                            <span class="nav-text">Sessions</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                   
                </ul>
            </div>

            <div class="sidebar-left-secondary" data-perfect-scrollbar data-suppress-scroll-x="true">
                    <ul class="childNav" data-parent="clinician">
                    <li class="nav-item ">
                            <a class="{{ Route::currentRouteName()=='addClinician' ? 'open' : '' }}" href="{{route('addClinician')}}">
                                <i class="nav-icon i-Add-User"></i>
                                <span class="item-name">Add Clinician</span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="{{ Route::currentRouteName()=='viewClinician' ? 'open' : '' }}" href="{{route('viewClinician')}}">
                                <i class="nav-icon i-Add-User"></i>
                                <span class="item-name">View Clinician</span>
                            </a>
                        </li>
                    </ul>
                <ul class="childNav" data-parent="caregivers">
                    <li class="nav-item ">
                        <a class="{{ Route::currentRouteName()=='viewCareGiver' ? 'open' : '' }}" href="{{route('viewCareGiver')}}">
                            <i class="nav-icon i-Add-User"></i>
                            <span class="item-name">View Care Giver</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ Route::currentRouteName()=='user-profile' ? 'open' : '' }}" href="{{route('user-profile')}}">
                            <i class="nav-icon i-Male"></i>
                            <span class="item-name">User Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ Route::currentRouteName()=='starter' ? 'open' : '' }}" href="{{route('starter')}}" class="open">
                            <i class="nav-icon i-File-Horizontal"></i>
                            <span class="item-name">Blank Page</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="sidebar-overlay"></div>
        </div>
        <!--=============== Left side End ================-->
