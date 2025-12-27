<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ allsettings('site.title') }}</title>
    <!-- Standard Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="favicon/favicon.svg" />
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="Yashi Associates" />
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}" />


    <!-- Fonts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}">

    <!-- JS -->
    <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/invoice.js') }}"></script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin" crossorigin="anonymous">
    </script>


    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased bg-white dark:bg-gray-800" data-user-id="{{ auth()->id() }}">


    <div class="min-h-screen bg-white dark:bg-gray-800">

        <x-navigation-menu />

        <!-- Page Heading -->


        <!-- Page Content -->
        <main class="mt-[60px] z-48 bg-white dark:bg-gray-800">
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>
