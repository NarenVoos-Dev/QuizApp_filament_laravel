<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Quiz App')</title>

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
<link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

<!-- Fonts and icons -->
<link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
<link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

<!-- Soft UI Dashboard CSS -->
<link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />

<!-- Optional: Nepcha Analytics (puedes eliminar si no lo usas) -->
<script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
<style>
    .navbar-vertical .navbar-brand>img, .navbar-vertical .navbar-brand-img {
        max-width: 100px;
        max-height: 100px;
    }
</style>
@stack('styles')
