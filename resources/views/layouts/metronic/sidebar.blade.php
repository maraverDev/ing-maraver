<div id="m_aside_left" class="m-grid__item m-aside-left m-aside-left--skin-dark">
    <div id="m_ver_menu" class="m-aside-menu m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark"
        data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">

        <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow">

            <li class="m-menu__item {{ request()->routeIs('dashboard') ? 'm-menu__item--active' : '' }}">
                <a href="{{ route('dashboard') }}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-text">Dashboard</span>
                </a>
            </li>

            <li class="m-menu__item {{ request()->routeIs('places.*') ? 'm-menu__item--active' : '' }}">
                <a href="{{ route('places.index') }}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-map"></i>
                    <span class="m-menu__link-text">Lugares</span>
                </a>
            </li>

            <li class="m-menu__item {{ request()->routeIs('installations.*') ? 'm-menu__item--active' : '' }}">
                <a href="{{ route('installations.index') }}" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-settings"></i>
                    <span class="m-menu__link-text">Instalaciones</span>
                </a>
            </li>

        </ul>
    </div>
</div>