  @extends('personal.layouts.main')

  @section('title-block')Заявки на добавление в группу@endsection

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/datepicker/cssworld.ru-xcal.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Заявки на добавление в группу</h1>
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
          <div class="col-6">
            <div class="card">
              <div class="card-body table-responsive">
                <table class="table table-hover text-wrap">
                  <thead>
                    <tr>
                      <th>ФИО</th>
                      <th>Номер студенческого</th>
                      <th style="width: 30%;">Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($applications as $item)
                    <tr>
                      <td>
                        {{ $item->user->surname }}
                        {{ $item->user->name }}
                        {{ $item->user->patronymic }}
                      </td>
                      <td>{{ $item->student_id_number }}</td>
                      @if (isset($item->user->email_verified_at))
                      <td class="project-actions text-left">
                        <a class="btn btn-info btn-sm btn-accept" href="{{ route('personal.application.accept', $item->id) }}">
                          <i class="fas fa-check-square"></i>
                          Принять
                        </a>
                        <a class="btn btn-danger btn-sm btn-reject" href="{{ route('personal.application.reject', $item->id) }}">
                          <i class="fas fa-minus-square"></i>
                          Отклонить
                        </a>
                      </td>
                      @else
                        <td><i>Пользователь еще не подтвердил свою учетную запись</i></td>
                      @endif
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

  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/personal/application/complete.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/news/cssworld.ru-xcal-en.js') }}"></script>
  <!-- /.content-wrapper -->
  @endsection
