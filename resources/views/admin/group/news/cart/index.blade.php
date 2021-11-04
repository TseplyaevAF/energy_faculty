  @extends('admin.layouts.main')

  @section('title-block')Новости групп@endsection

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Новости групп</h1>
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
            <a href="{{ route('admin.group.news.index') }}" class="btn btn-block btn-primary">Выйти из корзины</a>
          </div>
        </div>

        <div class="row">
          <div class="col-8">
            <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Название</th>
                      <th>Группа</th>
                      <th colspan="3">Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($group_news as $news)
                    <tr>
                      <td>{{ $news->id }}</td>
                      <td>{{ $news->title }}</td>
                      @if ($groups->find($news->group_id)['title'])
                      <td><b><a href="{{ route('admin.group.show', $news->group_id) }}">{!! $groups->find($news->group_id)['title'] !!}</b></a></td>
                      @else
                      <td>Группа не найдена</td>
                      @endif
                      <td><a href="{{ route('admin.group.news.cart.show', $news->id) }}"><i class="far fa-eye"></i></a></td>
                      <td><a href="{{ route('admin.group.news.cart.restore', $news->id) }}" class="text-success"><i class="fas fa-trash-restore"></i></a></td>
                      <td>
                        <form action="{{ route('admin.group.news.cart.delete', $news->id) }}" method="post">
                          @csrf
                          @method('delete')
                          <button type="submit" class="border-0 bg-transparent">
                            <i class="far fa-trash-alt text-danger" role="button"></i>
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
  <!-- /.content-wrapper -->
  @endsection