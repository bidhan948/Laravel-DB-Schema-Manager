<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/bidhan/bhadhan/css/main.css') }}">
    <title>@yield('title', 'DB - SCHEMA')</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">{{ env('APP_NAME') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link f-w-b-1" href="{{ url('bhadhan/dashboard') }}">Dashboard </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link f-w-b-1 {{ Request::is('bhadhan/db-manager/schema') ? 'active-link' : '' }}"
                        href="{{ route('bhadhan-db-manager.schema') }}">Schema</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link f-w-b-1 {{ Request::is('bhadhan/db-manager/performance-metrics') ? 'active-link' : '' }}"
                        href="{{ route('bhadhan-db-manager.performance') }}">Performance Metrics</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link f-w-b-1 {{ Request::is('bhadhan/db-manager/sql') ? 'active-link' : '' }}"
                        href="{{ route('bhadhan-db-manager.sql') }}">SQL</a>
                </li>
            </ul>
        </div>
    </nav>

    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    @yield('scripts')
</body>

</html>
