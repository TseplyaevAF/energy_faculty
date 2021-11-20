  @extends('personal.layouts.main')

  @section('title-block')Домашние задания@endsection

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/personal/task/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/datepicker/cssworld.ru-xcal.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Домашние задания</h1>
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
          <div class="filters-block col-2">
            <h5>Фильтры</h5>
            <form action="{{route('personal.homework.index')}}" method="GET">
              <div class="form-group">
                <select name="discipline_id" class="form-control">
                  <option value="">Все дисциплины</option>
                  @foreach($disciplines as $discipline)
                  <option value="{{ $discipline->id }}" @if(isset($_GET['discipline_id'])) @if($_GET['discipline_id']==$discipline->id) selected @endif @endif>{{ $discipline->title }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Статус задания</label>
                <select name="status_id" class="form-control">
                  @foreach($statusVariants as $id => $status)
                  <option value="{{ $id }}" {{$id == old('status_id') ? 'selected' : ''}}>{{ $status }}</option>
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
            <form action="{{ route('personal.homework.index') }}" method="GET">
              <input value="" type="hidden" name="discipline_id">
              <input value="" type="hidden" name="status_id">
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
                      <th>Задание</th>
                      <th>Дисциплина</th>
                      <th>Статус</th>
                      <th style="width: 20%;">Решение</th>
                      <th>Результат</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tasks as $task)
                    <tr>
                      <td>
                        @include('personal.includes.homework.task_file')
                      </td>
                      <td><b><a href="#">{{ $task->discipline->title }}</a></b></td>
                      @php
                        $isWork = false;
                      @endphp
                      @foreach ($homework as $work)
                        <!-- Если студент добавлял решение к текущему заданию -->
                        @if ($work->task_id == $task->id)
                          @php
                            $isWork = true;
                          @endphp
                          @if ($work->grade != 'on check')
                            <!-- Если задание студента проверено -->
                            <td class="work-status__completed">{{ $statusVariants[1] }}</td>
                            @include('personal.includes.homework.complete')
                            @break
                          @else
                            @if ($task->status === 0)
                              <!-- Приём заданий еще не закрыт, можно отправить другой файл -->
                              <td class="work-status__active">{{ $statusVariants[0] }}</td>
                              @include('personal.includes.homework.active')
                              @break
                            @else
                              <!-- Приём заданий закрыт преподавателем, отправить другой файл нельзя -->
                              <td class="work-status__pending">Проверяется</td>
                              @include('personal.includes.homework.pending')
                              @break
                            @endif
                          @endif
                        @endif
                      @endforeach

                      <!-- Если студент не добавлял решение к текущему заданию -->
                      @if (!$isWork)
                        @if ($task->status === 0)
                          @include('personal.includes.homework.add')
                        @else
                          <td class="work-status__completed">{{ $statusVariants[1] }}</td>
                          <td>Добавить невозможно</td>
                        @endif
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

  <script type="text/javascript" src="{{ asset('js/news/cssworld.ru-xcal-en.js') }}"></script>
  <!-- /.content-wrapper -->
  @endsection