  @extends('admin.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Занятия</h1>
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

        <div class="row">
          <div class="col-1 mb-3">
            <a href="{{ route('admin.lesson.create') }}" class="btn btn-block btn-primary">Создать</a>
          </div>
        </div>

        <div class="row">
          <div class="col-7">
            <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Год обучения</th>
                      <th>Группа</th>
                      <th>Дисциплина</th>
                      <th>Преподаватель</th>
                      <th colspan="3">Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($lessons as $lesson)
                    <tr>
                        <td>{{ $lesson->year->start_year }}-{{ $lesson->year->end_year }}</td>
                        <td>{{ $lesson->group->title  }}</td>
                        <td>{{ $lesson->discipline->title }}</td>
                        <td>{{ $lesson->teacher->user->surname }}</td>
                      <td><a href="{{ route('admin.lesson.edit', $lesson->id) }}" class="text-success"><i class="far fa-edit"></i></a></td>
                      <td>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
