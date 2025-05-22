<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="user-id" content="{{ auth()->id() }}">

<title> @yield('title') | SIMEVA</title>
{{-- @vite(['resources/js/app.js']) --}}
<link rel="icon" type="image/png" href="{{ asset('assets/images/logo_polinema.png') }}" sizes="16x16">
<!-- BootStrap css -->
<link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
<!-- select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<!-- Slick -->
<link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
<!-- Slick -->
<link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
<!-- Data Table css -->
<link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}">
<!-- jquery-ui -->
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
<!-- plyr Css -->
<link rel="stylesheet" href="{{ asset('assets/css/plyr.css') }}">
<!-- animate -->
<link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
<!-- Date picker css -->
<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr.js/flatpickr.min.css') }}">
<!-- Calendar css -->
<link rel="stylesheet" href="{{ asset('assets/css/lib/full-calendar.css') }}">
<!-- Main css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<!-- Notify-->
<link rel="stylesheet" href="{{ asset('assets/css/notyf.min.css') }}">

@yield('css')

<style>
    /* Menghilangkan panah pada input number */
    input[type=number] {
        -moz-appearance: textfield;
        /* Firefox */
        appearance: textfield;
        /* Modern Browsers */
    }

    /* Untuk WebKit-based browsers (Chrome, Safari, Edge) */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
