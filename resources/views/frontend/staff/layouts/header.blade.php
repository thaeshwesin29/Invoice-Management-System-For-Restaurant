<header class="header header-sticky p-0 mb-4">
    <div class="container-fluid border-bottom px-4">
        <button class="header-toggler" type="button"
            onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
            style="margin-inline-start: -14px;">
            <svg class="icon icon-lg">
                <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-menu') }}"></use>
            </svg>
        </button>
        <ul class="header-nav d-none d-lg-flex">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">{{ __('message.dashboard') }}</a></li>
        </ul>
        <ul class="header-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-user') }}"></use>
                    </svg>
                    <b>{{ auth()->guard('staff')->user()->name }}</b>
                </a>
            </li>
        </ul>
        <ul class="header-nav">
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button"
                    aria-expanded="false" data-coreui-toggle="dropdown">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-language') }}"></use>
                    </svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                    <li>
                        <a href="{{ route('language.switch', 'en') }}" class="dropdown-item d-flex align-items-center {{ session('locale') == 'en' ? 'active' : '' }}">
                            ENG - အင်္ဂလိပ်
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('language.switch', 'mm') }}" class="dropdown-item d-flex align-items-center {{ session('locale') == 'mm' ? 'active' : '' }}">
                            MM - မြန်မာ
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button"
                    aria-expanded="false" data-coreui-toggle="dropdown">
                    <svg class="icon icon-lg theme-icon-active">
                        <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-contrast') }}"></use>
                    </svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="light">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-sun') }}"></use>
                            </svg>Light
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="dark">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-moon') }}"></use>
                            </svg>Dark
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center active" type="button"
                            data-coreui-theme-value="auto">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-contrast') }}">
                                </use>
                            </svg>Auto
                        </button>
                    </li>
                </ul>
            </li>
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown"><a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md"><img class="rounded-full object-cover w-10 h-10" src="{{ auth()->guard('staff')->user()->profile_image_url }}" alt="{{  auth()->guard('staff')->user()->email }}">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                        {{ __('message.account') }}
                    </div>
                    <a class="dropdown-item" href="{{ route('staff.show', auth()->guard('staff')->user()->id) }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-user') }}"></use>
                        </svg> {{ __('message.profile') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-account-logout') }}">
                                </use>
                            </svg> {{ __('message.logout') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
    <div class="container-fluid px-4">
        @yield('breadcrumb')
    </div>
</header>
