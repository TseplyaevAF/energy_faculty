  @extends('personal.layouts.main')

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/groups/news/style.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 mb-2">Личный кабинет</h1>
          <h5>{{ auth()->user()->surname }} {{ auth()->user()->name }} {{ auth()->user()->patronymic }}</h5>
            @if (isset(auth()->user()->avatar))
              @php
                  $modelId = explode('/', auth()->user()->avatar)[0];
                  $mediaId = explode('/', auth()->user()->avatar)[2];
                  $filename = explode('/', auth()->user()->avatar)[3];
              @endphp
              <div class="row">
                <div class="col-md-4 mb-3">
                  <img src="{{ route('personal.settings.showImage', [$modelId, $mediaId, $filename]) }}" alt="image" class="thumbnail img-responsive">
                </div>
              </div>
            @endif
            @if (auth()->user()->role_id == 2)
              <h6><b>Учебная группа:</b> {{ auth()->user()->student->group->title }}</h6>
              <h6><b>Номер студенческого билета:</b> {{ auth()->user()->student->student_id_number }}</h6>
            @elseif (auth()->user()->role_id == 3)
              <h6><b>Должность:</b> {{ auth()->user()->teacher->post }}</h6>
              <h6><b>Преподаваемые дисциплины:</b>
                @foreach (auth()->user()->teacher->disciplines->unique('discipline') as $item)
                  {{ $item->title }},
                @endforeach
              </h6>
            @endif

          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            @if (auth()->user()->role_id == 2)
              <li class="breadcrumb-item active">Личный кабинет студента</li>
            @elseif (auth()->user()->role_id == 3)
              <li class="breadcrumb-item active">Личный кабинет преподавателя</li>
            @endif
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
