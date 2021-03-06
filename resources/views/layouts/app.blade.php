<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('total_album_counts_report') }}">Total Albums Report</a></li>
                        <li><a href="{{ route('total_report') }}">Total Images Status</a></li>
                        <li><a href="{{ route('album_report') }}">Album Images Status</a></li>
                        <li><a href="{{ route('standalone_report') }}">Stand Alone Images Status</a></li>
                        <li><a href="{{ route('album_images_not_migrated_report') }}">Album Images Migration Status</a></li>
                        <li><a href="{{ route('standalone_images_not_migrated_report') }}">Standalone Images Migration Status</a></li>
                        <li><a href="{{ route('acms_albums_migration_report') }}">ACMS Albums Migration Status</a></li>
                        <li><a href="{{ route('millenium_albums_migration_report') }}">Millenium Albums Migration Status</a></li>

                      </ul>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">SIP Generation <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('sip_generate_standalone') }}">Standalone Images</a></li>
                        <li><a href="{{ route('sip_generate_album') }}">Album Images</a></li>
                      </ul>
                    </li>
                    <li><a href="{{ route('get_ingest_qa') }}">Ingest QA</a></li>
                    <li><a href="{{ route('details_report') }}">Search</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
