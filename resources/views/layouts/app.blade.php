<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.head')
    @yield('head')
</head>

<body>
    @include('includes.header')
    <div id="app">
        <main class="container py-4">
            @yield('main_content')
        </main>
    </div>
    @yield('additional_scripts')
</body>

</html>