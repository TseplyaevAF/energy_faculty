  @extends('admin.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Добавление дисциплины</h1>
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

        @if (session('error'))
        <div class="col-3 alert alert-warning" role="alert">{{ session('error') }}</div>
        @endif

        <div class="row">
          <div class="col-12">
            <form action="{{ route('admin.discipline.store') }}" method="POST" class="w-25">
              @csrf

              <div class="form-group">
                <input value="{{ old('title') }}" type="text" class="form-control" name="title" id="title" placeholder="Название дисциплины">
                @error('title')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <input type="submit" class="btn btn-primary" value="Добавить">
            </form>
          </div>
        </div>


      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection