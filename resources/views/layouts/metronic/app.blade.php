<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.metronic.head')

    <style>
        /* Loader de página completo */
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease-out, visibility 0.5s;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3699ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        body.loaded #page-loader {
            opacity: 0;
            visibility: hidden;
        }

        .custom-file-upload {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .custom-file-upload .btn {
            display: flex;
            align-items: center;
        }

        .selected-files {
            font-size: 13px;
        }

        .file-status {
            display: flex;
            gap: 12px;
            font-size: 13px;
        }

        .status-item {
            padding: 4px 8px;
            border-radius: 4px;
            background: #f5f8fa;
            color: #7e8299;
            font-weight: 500;
        }

        .status-item.ok {
            background: #e8fff3;
            color: #0bb783;
        }

        .sub-site-card {
            display: flex;
            align-items: center;
            padding: 12px 14px;
            border: 1px solid #e4e6ef;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            background-color: #fff;
        }

        .sub-site-card:hover {
            background-color: #f5f8fa;
            border-color: #3699ff;
        }

        .sub-site-card input[type="checkbox"] {
            margin-right: 12px;
            transform: scale(1.1);
        }

        /* Estilos de Validación */
        .is-invalid {
            border-color: #f64e60 !important;
            padding-right: calc(1.5em + 1.3rem) !important;
            background-repeat: no-repeat !important;
            background-position: right calc(0.375em + 0.325rem) center !important;
            background-size: calc(0.75em + 0.65rem) calc(0.75em + 0.65rem) !important;
        }

        .invalid-feedback {
            color: #f64e60 !important;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .border-danger {
            border-color: #f64e60 !important;
        }

        .text-danger {
            color: #f64e60 !important;
        }

        .sub-site-card.border-danger {
            background-color: #fff5f8;
        }

        .sub-site-card input[type="checkbox"]:checked+.sub-site-content {
            color: #3699ff;
        }
    </style>

    <script>
        // Función para ocultar el loader
        function hideLoader() {
            document.body.classList.add('loaded');
        }

        // Ocultar cuando todo esté listo
        window.addEventListener('load', hideLoader);

        // Fail-safe: si tarda mucho (3s), mostrar la página igualmente
        setTimeout(hideLoader, 3000);
    </script>
</head>

<body
    class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    {{-- Loader HTML --}}
    <div id="page-loader">
        <div class="loader-spinner"></div>
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/47d7c4b9a5.js" crossorigin="anonymous"></script>
    @stack('scripts')

</body>

</html>
