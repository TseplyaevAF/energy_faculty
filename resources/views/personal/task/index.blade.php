  @extends('personal.layouts.main')

  @section('title-block')Задания для групп@endsection

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/datepicker/cssworld.ru-xcal.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Задания для групп</h1>
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
          <div class="col-2 mb-3">
            <a href="{{ route('personal.task.create') }}" class="btn btn-block btn-primary">Добавить задание</a>
          </div>
        </div>

        <div class="row">
          <div class="filters-block col-2">
            <label for="exampleFormControlInput1" class="form-label">Фильтры</label>
            <form action="{{route('personal.task.index')}}" method="GET">
              <div class="form-group">
                <input @if(isset($_GET['content'])) value="{{$_GET['content']}}" @endif type="text" class="form-control" name="content" placeholder="Поиск файла">
              </div>
              <div class="form-group">
                <select name="group_id" class="form-control">
                  <option value="">Все группы</option>
                  @foreach($groups as $group)
                  <option value="{{ $group->id }}" @if(isset($_GET['group_id'])) @if($_GET['group_id']==$group->id) selected @endif @endif>{{ $group->title }}</option>
                  @endforeach
                </select>
              </div>
              <label>Дата добавления задания:</label>
              <div class="form-group">
                <div class="col">
                  <h6 class="text-muted">с </h6>
                  <input @if(isset($_GET['date'][0])) value="{{$_GET['date'][0]}}" @endif autocomplete="off" type="text" class="form-control" name="date[]" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                </div>
              </div>

              <div class="form-group">
                <div class="col">
                  <h6 class="text-muted">по </h6>
                  <input @if(isset($_GET['date'][1])) value="{{$_GET['date'][1]}}" @endif autocomplete="off" type="text" class="form-control" name="date[]" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                </div>
              </div>
              <button type="submit" class="btn btn-success mb-2">Применить</button>
            </form>
            <form action="{{ route('personal.task.index') }}" method="GET">
              <input value="" type="hidden" name="content">
              <input value="" type="hidden" name="group_id">
              <input value="" type="hidden" name="date[]">
              <button type="submit" class="btn btn-default">Сбросить</button>
            </form>
          </div>
          <div class="col-8">
            <div class="card">
              <div class="card-body table-responsive">
                <table class="table table-hover text-wrap">
                  <thead>
                    <tr>
                      <th>Название файла</th>
                      <th>Дисциплина</th>
                      <th>Группа</th>
                      <th style="width: 20%;">Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tasks as $task)
                    <tr>
                      <td>
                        @php
                          $mediaId = explode('/', $task->task)[2];
                          $filename = explode('/', $task->task)[3];
                        @endphp
                        <a href="{{ route('personal.task.download', [$mediaId, $filename]) }}">{{ $filename }}</a>
                      </td>
                      <td><b><a href="#">{{ $task->discipline->title }}</a></b></td>
                      <td><b><a href="#">{!! $task->group->title !!}</a></b></td>
                      <!-- <td><a href="{{ route('personal.task.show', $task->id) }}"><i class="far fa-eye"></i></a></td> -->
                      <td class="project-actions text-right d-flex">
                        <a class="btn btn-info btn-sm mr-1" href="{{ route('personal.task.show', $task->id) }}">
                        <i class="far fa-eye"></i>
                          Посмотреть
                        </a>
                        <form action="{{ route('personal.task.delete', $task->id) }}" method="post">
                          @csrf
                          @method('delete')
                          <button type="submit" class="btn btn-danger btn-sm delete-btn" href="#">
                            <i class="fas fa-trash">
                            </i>
                            Удалить
                          </button>
                        </form>
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

  <script type="text/javascript" src="{{ asset('js/news/cssworld.ru-xcal-en.js') }}"></script>
  <!-- /.content-wrapper -->
  @endsection