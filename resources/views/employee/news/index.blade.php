  @extends('employee.layouts.main')

  @section('title-block')Новости групп@endsection

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/news/filter_news.css') }}">
  <link rel="stylesheet" href="{{ asset('css/datepicker/cssworld.ru-xcal.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Новости</h1>
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
            <a href="{{ route('employee.news.create') }}" class="btn btn-block btn-primary">Создать</a>
          </div>
        </div>

        <div class="row">
          <div class="filters-block col-2">
            <label for="exampleFormControlInput1" class="form-label">Фильтры</label>
            <form action="{{route('employee.news.index')}}" method="GET">
              <div class="form-group">
                <input @if(isset($_GET['content'])) value="{{$_GET['content']}}" @endif type="text" class="form-control" name="content" placeholder="Поиск по содержимому...">
              </div>
              <div class="form-group">
                <select name="category_id" class="form-control">
                  <option value="">Все категории</option>
                  @foreach($categories as $category)
                  <option value="{{ $category->id }}" @if(isset($_GET['category_id'])) @if($_GET['category_id']==$category->id) selected @endif @endif>{{ $category->title }}</option>
                  @endforeach
                </select>
              </div>
              <label>Дата:</label>
              <div class="form-group">
                <div class="col">
                  <h6 class="text-muted">с </h6>
                  <input @if(isset($_GET['date'][0])) value="{{$_GET['date'][0]}}" @endif
                  autocomplete="off" type="text" class="form-control" name="date[]" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                </div>
              </div>

              <div class="form-group">
                <div class="col">
                  <h6 class="text-muted">по </h6>
                  <input @if(isset($_GET['date'][1])) value="{{$_GET['date'][1]}}" @endif
                  autocomplete="off" type="text" class="form-control" name="date[]" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                </div>
              </div>
              <button type="submit" class="btn btn-success mb-2">Применить</button>
            </form>
            <form action="{{ route('employee.news.index') }}" method="GET">
              <input value="" type="hidden" name="content">
              <input value="" type="hidden" name="category_id">
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
                      <th>ID</th>
                      <th>Заголовок</th>
                      <th>Категория</th>
                      <th style="width: 30%;">Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($all_news as $news)
                    <tr>
                      <td>{{ $news->id }}</td>
                      <td>{{ $news->title }}</td>
                      @if ($categories->find($news->category_id)['title'])
                      <td>{!! $categories->find($news->category_id)['title'] !!}</td>
                      @else
                      <td>Категория не указана</td>
                      @endif
                      <!-- <td><a href="{{ route('employee.news.show', $news->id) }}"><i class="far fa-eye"></i></a></td> -->
                      <td class="project-actions text-left">
                        <a class="btn btn-info btn-sm mr-1" href="{{ route('employee.news.edit', $news->id) }}">
                          <i class="fas fa-pencil-alt"></i>
                          Редактировать
                        </a>
                        <form action="{{ route('employee.news.delete', $news->id) }}" method="post"
                        style="display: inline-block">
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
            <div class="mt-3">
              {{ $all_news->withQueryString()->links() }}
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
