<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.metronic.head')
</head>

<body
    class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    <div class="m-grid m-grid--hor m-grid--root m-page">

        @include('layouts.metronic.header')

        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

            <button class="m-aside-left-close m-aside-left-close--skin-dark" id="m_aside_left_close_btn">
                <i class="la la-close"></i>
            </button>

            @include('layouts.metronic.sidebar')

            <div class="m-grid__item m-grid__item--fluid m-wrapper">

                <div class="m-subheader">
                    <div class="d-flex align-items-center">
                        <div class="mr-auto">
                            <h3 class="m-subheader__title">@yield('title')</h3>
                        </div>
                    </div>
                </div>

                <div class="m-content">
                    @yield('content')
                </div>

            </div>
        </div>

        @include('layouts.metronic.footer')

    </div>

    <script src="{{ asset('metronic/assets/vendors/base/vendors.bundle.js') }}"></script>
    <script src="{{ asset('metronic/assets/demo/default/base/scripts.bundle.js') }}"></script>

    @stack('scripts')

</body>

</html>