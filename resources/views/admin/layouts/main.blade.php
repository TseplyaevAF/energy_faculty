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

  <link rel="stylesheet" href="{{ asset('css/layouts.css') }}">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">

  @include('includes.wrapper', ['sidebar' => 'admin.includes.sidebar'])

  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>


  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <script src="{{ asset('dist/js/adminlte.js') }}"></script>
  <script src="{{ asset('js/admin.js') }}"></script>
  <script src="{{ asset('js/changeTheme.js') }}"></script>

  <script>
    $(function() {
      bsCustomFileInput.init();
    });

    $('.select2').select2()

    $('#phone').inputmask("+8-(999)-999-99-99");

    $('#group_title').inputmask({
        mask: "A{1,15}-99",
        definitions: {'5': {validator: "[0-5]"}}
    });

    $('#year').inputmask({
        mask: "9999-9999"
    });

    $('#classroom').inputmask({
        mask: "09-999"
    });

    let classTypes = ['практика', 'лабораторная работа', 'лекция'];

    $('#class_type').autocomplete({source: classTypes});
  </script>

</body>

</html>
