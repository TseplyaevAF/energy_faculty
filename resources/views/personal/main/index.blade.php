  @extends('personal.layouts.main')

  @section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 mb-2">Личный кабинет</h1>
          <h5>{{ auth()->user()->surname }} {{ auth()->user()->name }} {{ auth()->user()->patronymic }}</h5>
            @if (auth()->user()->role_id == 2)
              <h6><b>Учебная группа:</b> {{ auth()->user()->student->group->title }}</h6>
              <h6><b>Номер студенческого билета:</b> {{ auth()->user()->student->student_id_number }}</h6>
            @elseif (auth()->user()->role_id == 3)
              <h6><b>Должность:</b> {{ auth()->user()->teacher->post }}</h6>
              <h6><b>Преподаваемые дисциплины:</b>
                @foreach (auth()->user()->teacher->disciplines->unique('id') as $item)
                  {{ $item->title }},
                @endforeach
              </h6>
              <h6><b>Кафедра:</b>
                  <a href="{{route('employee.main.index')}}">{{ auth()->user()->teacher->chair->title  }}</a>
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
