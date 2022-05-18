  @extends('employee.layouts.main')

  @section('title-block')Новости групп@endsection

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/news/filter_news.css') }}">
  <link rel="stylesheet" href="{{ asset('css/datepicker/cssworld.ru-xcal.css') }}">
  <style>
      input[type=checkbox] {
          width: 6mm;
          height: 6mm;
          border: 0.1mm solid black;
      }
  </style>

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

      <div class="modal fade" id="createPostModal" tabindex="-1" role="dialog"
           aria-labelledby="createPostModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="card-title">Создание новой записи</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body" id="createPostModalBody">
                  </div>
              </div>
          </div>
      </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-sm-2 mb-3">
              <button type="button" id="createPost" class="btn btn-block btn-primary">Создать</button>
          </div>
        </div>

        <div class="row">
          <div class="filters-block col-sm-3 col-md-3 col-lg-3 mb-3">
            <label style="font-size: 18px" class="form-label">Фильтры</label>
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
              <span>Дата публикации:</span>
              <div class="form-group">
                <div class="mb-2">
                  <h6 class="text-muted">с </h6>
                  <input @if(isset($_GET['date'][0])) value="{{$_GET['date'][0]}}" @endif
                  autocomplete="off" type="text" class="form-control" name="date[]" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                </div>
              </div>

              <div class="form-group">
                <div class="mb-2">
                  <h6 class="text-muted">по </h6>
                  <input @if(isset($_GET['date'][1])) value="{{$_GET['date'][1]}}" @endif
                  autocomplete="off" type="text" class="form-control" name="date[]" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                </div>
              </div>

              <div class="form-group">
                  <div class="mb-2">
                      <div class="row" style="margin: 0">
                          <span class="mr-1">Показать новости в слайдере</span>
                          <div>
                              <input type="checkbox" value="1" name="is_slider_item"
                              {{ isset($_GET['is_slider_item']) ? 'checked' :''}}>
                          </div>
                      </div>

                  </div>
              </div>
              <button type="submit" class="btn btn-success mb-2">Применить</button>
            </form>
            <form action="{{ request()->url() }}" method="GET">
              <button type="submit" class="btn btn-default">Сбросить</button>
            </form>
          </div>
          <div class="col-md-9 col-sm-9">
            <div class="card">
              <div class="card-body table-responsive">
                <table class="table table-hover text-wrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Заголовок</th>
                      <th>Категория</th>
                      <th style="width: 30%;">Действия</th>
                      <th>Слайдер</th>
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
                      <td class="project-actions text-left">
                          <div class="col-xs-1">
                              <a class="btn btn-info btn-sm mr-1 mb-1" href="{{ route('employee.news.edit', $news->id) }}">
                                  <i class="fas fa-pencil-alt"></i>
                                  Редактировать
                              </a>
                              <form action="{{ route('employee.news.destroy', $news->id) }}" method="post"
                                    style="display: inline-block">
                                  @csrf
                                  @method('delete')
                                  <button type="submit" class="btn btn-danger btn-sm delete-btn mb-1" href="#">
                                      <i class="fas fa-trash">
                                      </i>
                                      Удалить
                                  </button>
                              </form>
                          </div>
                      </td>
                      <td>
                          <input type="checkbox" id="slider_{{ $news->id }}"
                              @if ($news->is_slider_item) checked @endif class="cb-slider">
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
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script>
      $('#createPost').on('click', function () {
          $.ajax({
              method: 'GET',
              url: 'olimps/create-olimp',
              datatype: 'json',
              success: function (response) {
                  $('#createPostModal').modal('show');
                  $('#createPostModalBody').html(response);
              }
          });
      });

      $('.table').on('click', ':checkbox', function () {
          let postId = $(this).attr('id').split('_')[1];
          $.ajax({
              method: 'PATCH',
              url: `news/add-to-slider/${postId}`,
              data: {
                  '_token': $("input[name='_token']").val()
              },
              beforeSend: function () {
                  $('.cb-slider').prop( "disabled", true);
              },
              success: function (response) {
                  $('.cb-slider').prop( "disabled", false);
              }
          });
      })
  </script>
  <!-- /.content-wrapper -->
  @endsection
