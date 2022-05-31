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

  @include('includes.wrapper', ['sidebar' => 'personal.includes.sidebar'])

  <script src="{{ asset('js/app.js') }}"></script>

  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.min.js" type="text/javascript"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>

  <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

  <script src="{{asset('plugins/summernote/lang/summernote-ru-RU.js')}}"></script>

  <script src="{{asset('plugins/summernote/summernote-cleaner.js')}}"></script>

  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <script src="{{ asset('dist/js/adminlte.js') }}"></script>
  <script src="{{ asset('js/admin.js') }}"></script>
  <script src="{{ asset('js/personal/group_news/notifications.js') }}"></script>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script src="{{ asset('js/changeTheme.js') }}"></script>
  <script>
    $(document).ready(function() {
        loadUnreadPostsCount("{{ route('personal.news.getUnreadPostsCount') }}");

        Echo.channel('count-group-post-channel')
            .listen('.count-group-post-event',(e) => {
                loadUnreadPostsCount("{{ route('personal.news.getUnreadPostsCount') }}");
            })

      $('#summernote').summernote({
        lang: 'ru-RU',
          height: 300,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['height', ['height']],
          ['insert', [ 'link']],
        ], disableDragAndDrop: true,
          contents: '<div style= "width: 200px;"><h1></h1></div>',
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
    $('.phoneMask').inputmask("+9-(999)-999-99-99");
    $('.pasport').inputmask({
        mask: "9{2}-9{2}-9{6}",
    });
    $('.mask-inn-individual').inputmask('999999999999');
    $('.mask-snils').inputmask('999-999-999 99');
    $.mask.definitions['h'] = "[A-Za-z0-9|_]";
    $('.tg-username').mask('@hhhhh?hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh', {"placeholder": ""});
  </script>

</body>

</html>
