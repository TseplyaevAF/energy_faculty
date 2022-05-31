@if (session('theme') === null)
    <link id="theme-link" rel="stylesheet" href="{{ asset('css/theme_light.css') }}">
    <input type="hidden" value="light" name="theme">
@else
    <link id="theme-link" rel="stylesheet" href="{{ asset('css/theme_' . session('theme') . '.css') }}">
    <input type="hidden" value="{{ session('theme') }}" name="theme">
@endif
<div class="wrapper">

    <!-- Preloader -->
    {{--    <div class="preloader flex-column justify-content-center align-items-center">--}}
    {{--      <img class="animation__shake" src="{{ asset('assets/default/logo.png')}}" alt="AdminLTELogo" height="60" width="60">--}}
    {{--    </div>--}}

    <input type="hidden" value="{{ asset('css/theme_dark.css') }}" name="dark_theme_path">
    <input type="hidden" value="{{ asset('css/theme_light.css') }}" name="light_theme_path">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <div class="col-12 d-flex justify-content-between">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <input class="btn btn-outline-primary" type="submit" value="Выйти">
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->

    @include($sidebar)
    @yield('content')

    <footer class="main-footer">
        <strong>Энергетический факультет ЗабГУ</strong>
        <div class="float-right d-none d-sm-inline-block">
            <b>Версия сайта</b> 1.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<script>
    $(".nav .nav-link").click(function() {
        if ($(this).hasClass("active")) {
            return false;
        }
    });
</script>
