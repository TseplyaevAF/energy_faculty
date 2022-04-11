  @extends('admin.layouts.main')

  @section('title-block')Группа {{ $group->title }}@endsection

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6 d-flex align-items-center">
            <h1 class="m-0 mr-2">{{ $group->title }}</h1>
            <td><a href="{{ route('admin.group.edit', $group->id) }}" class="text-success"><i class="far fa-edit"></i></a></td>
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

        <div class="row col-3 mb-3">
          <div class="btn-group">
            <a href="{{ route('admin.group.index') }}" class="btn btn-block btn-secondary">Ко всем группам</a>
          </div>
        </div>

        <div class="row">
          <div class="col-5">
            <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-wrap">
                  <tbody>
                    <tr>
                      <td>ID</td>
                      <td>{{ $group->id }}</td>
                    </tr>
                    <tr>
                      <td>Название</td>
                      <td>{{ $group->title }}</td>
                    </tr>
                    <tr>
                      <td>Курс</td>
                      <td>{{ $group->course }}</td>
                    </tr>
                    <tr>
                      <td>Семестр</td>
                      <td>{{ $group->semester }}</td>
                    </tr>
                    <tr>
                      <td>Выпускающая кафедра</td>
                      <td>{{ $group->chair['title'] }}</td>
                    </tr>
                    <tr>
                      <td>Староста группы</td>
                      @if (!empty($group->headman))
                        <td>
                          {{ $headman->user->surname }}
                          {{ $headman->user->name }}
                          {{ $headman->user->patronymic }}
                        </td>
                      @else <td>Не назначен</td>
                      @endif
                    </tr>
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
