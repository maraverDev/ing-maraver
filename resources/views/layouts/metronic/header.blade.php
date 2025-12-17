<header class="m-grid__item m-header" data-minimize-offset="200" data-minimize-mobile-offset="200">
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">

            {{-- BRAND (barra izquierda) --}}
            <div class="m-stack__item m-brand m-brand--skin-dark">
                <div class="m-stack m-stack--ver m-stack--general">

                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="{{ route('dashboard') }}" class="m-brand__logo-wrapper">
                            <img alt="Logo"
                                src="{{ asset('metronic/assets/demo/default/media/img/logo/logo_default_dark.png') }}">
                        </a>
                    </div>

                    <div class="m-stack__item m-stack__item--middle m-brand__tools">
                        {{-- Minimize (desktop) --}}
                        <a href="javascript:;" id="m_aside_left_minimize_toggle"
                            class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block">
                            <span></span>
                        </a>

                        {{-- Offcanvas (mobile) --}}
                        <a href="javascript:;" id="m_aside_left_offcanvas_toggle"
                            class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>
                    </div>

                </div>
            </div>

            {{-- HEADER HEAD (barra superior derecha, aunque esté vacío) --}}
            <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                <div id="m_header_topbar" class="m-topbar m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-topbar__nav-wrapper">
                        <ul class="m-topbar__nav m-nav m-nav--inline">
                            {{-- vacío por ahora --}}
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>