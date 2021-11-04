  @extends('admin.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Добавление кафедры</h1>
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
            <form action="{{ route('admin.chair.store') }}" method="POST">
              @csrf
              <div class="form-group w-25">
                <input value="{{ old('title') }}" type="text" class="form-control" name="title" placeholder="Название кафедры">
                @error('title')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group w-25">
                <input value="{{ old('address') }}" type="text" class="form-control" name="address" placeholder="Адрес кафедры">
                @error('address')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <label for="exampleInputFile" class="mt-2">Контактные данные</label>
              <div class="input-group w-25 mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-phone"></i></span>
                </div>
                <input value="{{ old('phone_number') }}" class="form-control" id="phone" type="tel" name="phone_number">
              </div>
              @error('phone_number')
              <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="input-group w-25">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope-square"></i></span>
                </div>
                <input value="{{ old('email') }}" class="form-control" type="email" name="email">
              </div>
              @error('email')
              <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="form-group w-50 mb-4">
                <label>Описание кафедры</label>
                <textarea id="summernote" name="description">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <input type="submit" class="btn btn-primary mb-2" value="Добавить">
            </form>
          </div>
        </div>


      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection