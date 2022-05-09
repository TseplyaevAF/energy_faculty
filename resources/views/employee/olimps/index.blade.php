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
            <h1 class="m-0">Олимпиады и конференции</h1>
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
          <div class="col-md-9 col-sm-9">
            <div class="card">
              <div class="card-body table-responsive">
                <table class="table table-hover text-wrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Заголовок</th>
                      <th style="width: 30%;">Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($olimps as $olimp)
                    <tr>
                      <td>{{ $olimp->news->id }}</td>
                      <td>{{ $olimp->news->title }}</td>
                      <!-- <td><a href="{{ route('employee.news.show', $olimp->news->id) }}"><i class="far fa-eye"></i></a></td> -->
                      <td class="project-actions text-left">
                          <div class="col-xs-1">
                              <a class="btn btn-info btn-sm mr-1 mb-1" href="{{ route('employee.news.edit', $olimp->news->id) }}">
                                  <i class="fas fa-pencil-alt"></i>
                                  Редактировать
                              </a>
                              <form action="{{ route('employee.news.destroy', $olimp->news->id) }}" method="post"
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
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
{{--            <div class="mt-3">--}}
{{--              {{ $all_news->withQueryString()->links() }}--}}
{{--            </div>--}}
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script>
      $(document).ready(function () {
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
          })
      });
  </script>
  <!-- /.content-wrapper -->
  @endsection
