<meta charset="utf-8" />
<title>@yield('title', 'Dashboard')</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<script src="https://cdn.bootcss.com/webfont/1.6.16/webfontloader.js"></script>
<script>
    WebFont.load({
        google: { families: ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"] },
        active: function () {
            sessionStorage.fonts = true;
        }
    });
</script>

<link href="{{ asset('metronic/assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet">
<link href="{{ asset('metronic/assets/demo/default/base/style.bundle.css') }}" rel="stylesheet">
<link rel="shortcut icon" href="{{ asset('metronic/assets/demo/default/media/img/logo/favicon.ico') }}">