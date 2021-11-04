  @extends('admin.layouts.main')

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/groups/news/style.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6 d-flex align-items-center">
            <div class="mr-3">
              <a href="{{ route('admin.group.news.index') }}" class="btn btn-block btn-secondary">Вернуться</a>
            </div>
            <a href="{{ route('admin.group.news.edit', $news->id) }}" class="text-success"><i class="far fa-edit"></i></a>
            <form action="{{ route('admin.group.news.delete', $news->id) }}" method="post">
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
            <div class="card w-25">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <tbody>
                    <tr>
                      <td>ID новости</td>
                      <td>{{ $news->id }}</td>
                    </tr>
                    <tr>
                      <td>Группа</td>
                      <td>{{ $news->group->title }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group w-50">
          <h3 class="m-0 mr-2 mb-4">{{ $news->title }}</h3>
          {!! $news->content !!}
        </div>
        @if (isset($images))
        @foreach ($images as $image)
        <div class="row">
          <div class="col-md-4 mb-3">
            <img src="{{ asset('storage/' . $image) }}" alt="image" class="thumbnail img-responsive">
          </div>
        </div>
        @endforeach
        @endif

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection