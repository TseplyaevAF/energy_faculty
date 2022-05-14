<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title-block')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

  <link rel="stylesheet" href="{{ asset('css/layouts.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Preloader -->
{{--    <div class="preloader flex-column justify-content-center align-items-center">--}}
{{--      <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">--}}
{{--    </div>--}}

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

    @include('employee.includes.sidebar')
    @yield('content')

    <footer class="main-footer">
      <strong>Энергетический факультет</strong>
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 0.0.1
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

  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

  <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

  <script src="{{asset('plugins/summernote/lang/summernote-ru-RU.js')}}"></script>

  <script src="{{asset('plugins/summernote/summernote-cleaner.js')}}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <script src="{{ asset('dist/js/adminlte.js') }}"></script>
  <script src="{{ asset('js/admin.js') }}"></script>

  <script>
      $(document).ready(function() {
          $('#summernote').summernote({
              lang: 'ru-RU',
              height: 300,
              toolbar: [
                  ['style', ['bold', 'italic', 'underline', 'clear']],
                  ['fontsize', ['fontsize']],
                  ['para', ['ul', 'ol', 'paragraph']],
                  ['height', ['height']],
                  ['insert', [ 'link']],
              ], disableDragAndDrop: true,
          })
      });

      $summernote = $("#summernote");
      $summernote.on("summernote.enter", function(we, e) {
          $(this).summernote("pasteHTML", "<br><br>");
          e.preventDefault();
      });

    $(function() {
      bsCustomFileInput.init();

    });

    $('.select2').select2()
    $('#phone').inputmask("+8-(999)-999-99-99");

    $('#classroom').inputmask({ mask: "09-999" });

    let classTypes = ['практика', 'лабораторная работа', 'лекция'];
    $('#class_type').autocomplete({source: classTypes});
  </script>

</body>

</html>
