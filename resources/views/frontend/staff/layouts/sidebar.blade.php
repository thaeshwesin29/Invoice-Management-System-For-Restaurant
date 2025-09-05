<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
            <h5>Waiter Panel</h5>
            <h6 class="text-muted" style="width: 23ch; white-space: break-spaces;">{{ config('app.name') }}</h6>
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-dismiss="offcanvas" data-coreui-theme="dark"
            aria-label="Close"
            onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-speedometer') }}"></use>
                </svg>
                {{ __('message.dashboard') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('category.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-fastfood') }}"></use>
                </svg>
                {{ __('message.category') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('product.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-restaurant') }}"></use>
                </svg>
                {{ __('message.product') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('order.*') ? 'active' : '' }}" href="{{ route('order.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-dinner') }}"></use>
                </svg>
                {{ __('message.order') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('invoice.*') ? 'active' : '' }}" href="{{ route('invoice.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('coreui_icons/sprites/free.svg#cil-description') }}"></use>
                </svg>
                {{ __('message.invoice') }}
            </a>
        </li>
    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>
