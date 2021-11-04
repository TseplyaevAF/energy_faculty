  @extends('admin.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6 d-flex align-items-center">
            <h1 class="m-0 mr-2">{{ $news->title }}</h1>
            <form action="{{ route('admin.group.news.cart.delete', $news->id) }}" method="post">
              @csrf
              @method('delete')
              <button type="submit" class="border-0 bg-transparent">
                <i class="far fa-trash-alt text-danger" role="button"></i>
              </button>

            </form>
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
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <tbody>
                    <tr>
                      <td>ID</td>
                      <td>{{ $news->id }}</td>
                    </tr>
                    <tr>
                      <td>Название</td>
                      <td>{{ $news->title }}</td>
                    </tr>
                    <tr>
                      <td>Текст</td>
                      <td>{{ $news->content }}</td>
                    </tr>
                    <tr>
                      <td>Изображение</td>
                      <td>{{ $news->image }}</td>
                    </tr>
                    <tr>
                      <td>Группа</td>
                      <td>{{ $news->group_id }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-1 mb-3">
            <a href="{{ route('admin.group.news.index') }}" class="btn btn-block btn-secondary">Вернуться</a>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection