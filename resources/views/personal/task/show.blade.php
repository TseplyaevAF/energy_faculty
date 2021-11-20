  @extends('personal.layouts.main')

  @section('title-block')Задание для группы {{ $task->group->title }}@endsection

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/groups/news/style.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Задание для группы {{ $task->group->title }}</h1>
            <div class="task-file mt-2">
              <p>
                <a href="{{ route('personal.task.download', [explode('/', $task->task)[0], explode('/', $task->task)[2], explode('/', $task->task)[3]]) }}">
                  <i class="fas fa-file-word"></i>
                  {{ explode('/', $task->task)[3] }}
                </a>
              </p>
            </div>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        @if (session('error'))
        <div class="col-3 alert alert-warning" role="alert">{!! session('error') !!}</div>
        @endif

        @if (session('success'))
        <div class="col-3 alert alert-success" role="alert">{!! session('success') !!}</div>
        @endif
        <div class="row">
          <div class="col-6">
            <h4>Работы студентов</h4>
            @foreach ($homework as $work)
            <div class="post">
              <div class="user-block">
                <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                <span class="username">
                  <a href="#">
                    {{ $work->student->role->user->surname }}
                    {{ mb_substr($work->student->role->user->name, 0, 1) }}.
                    {{ mb_substr($work->student->role->user->patronymic, 0, 1)}}.
                  </a>
                </span>
                <span class="description">Загрузил своё решение {{ date('d.m.Y', strtotime($work->created_at)) }} в {{ date('H:i', strtotime($work->created_at)) }}</span>
              </div>
              <!-- /.user-block -->
              <!-- <p>
                Lorem ipsum represents a long-held tradition for designers,
                typographers and the like. Some people hate it and argue for
                its demise, but others ignore.
              </p> -->
              <p>
                @php
                  $modelId = explode('/', $work->homework)[0];
                  $mediaId = explode('/', $work->homework)[2];
                  $filename = explode('/', $work->homework)[3];
                @endphp
                <p class="text-sm" style="margin: 0">Скачать: </p><a href="{{ route('personal.homework.download', [$modelId, $mediaId, $filename]) }}" class="link-black text-sm"><b><i class="fas fa-link mr-1"></i>{{ $filename }}</b></a>
              </p>
            </div>
            @endforeach
          </div>
        </div>


      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection