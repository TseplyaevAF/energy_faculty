  @extends('employee.layouts.main')

  @section('title-block')Файлы сотрудника@endsection

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/employee/files/index/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/datepicker/cssworld.ru-xcal.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Файлы сотрудника</h1>
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
          <div class="col-12 mb-3">
            <form action="{{ route('employee.file.upload') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-2 mb-3 form-group form-group-lg inner-addon">
                  <i id="file-name" class="fas fa-upload glyphicon"> Выбрать файл...</i>
                  <input type="file" class="custom-file-input" name="file">
                  @error('file')
                  <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <input type="submit" class="btn btn-primary mb-2" value="Загрузить файл...">
            </form>
          </div>
        </div>

        <div class="row">
          <div class="filters-block col-2">
            <label for="exampleFormControlInput1" class="form-label">Фильтры</label>
            <form action="{{ route('employee.file.index') }}" method="GET">
              <div class="form-group">
                <input @if(isset($_GET['title'])) value="{{$_GET['title']}}" @endif type="text" class="form-control" name="title" placeholder="Поиск по названию файла">
              </div>
              <div class="form-group">
                <select name="category_id" class="form-control">
                  <option value="">Все типы файлов</option>
                  @foreach($categories as $category_id => $category)
                  <option value="{{ $category_id }}" @if(isset($_GET['category_id'])) @if($_GET['category_id']==$category_id) selected @endif @endif>{{ $category }}</option>
                  @endforeach
                </select>
              </div>
              <label>Дата загрузки:</label>
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
            <form action="{{ route('employee.news.index') }}" method="GET">
              <input value="" type="hidden" name="title">
              <input value="" type="hidden" name="category_id">
              <input value="" type="hidden" name="date[]">
              <button type="submit" class="btn btn-default">Сбросить</button>
            </form>
          </div>
          <div class="col-6">
            <div class="card">
              <div class="card-body table-responsive">
                <table class="table table-hover text-wrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Название</th>
                      <th>Дата добавления</th>
                      <th style="width: 20%;">Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($files as $file)
                    <tr>
                      <td>
                        {{ $file['id'] }}
                      </td>
                      <td>
                        <a href="{{ route('employee.file.show', [$file['model_id'], $file['collection_name'], $file['id'], $file['file_name']]) }}">
                          {{ $file['file_name'] }}
                        </a>
                      </td>
                      <td>{{ date('d.m.Y H:i', strtotime($file['created_at'])) }}</td>
                      <td class="project-actions text-right d-flex">
                        Получить URL
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