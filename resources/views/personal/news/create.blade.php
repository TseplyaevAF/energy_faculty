  @extends('personal.layouts.main')

  @section('title-block')Добавление новости группы@endsection

  @section('content')
      <link rel="stylesheet" href="{{ asset('css/news/style.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
              <h1 class="m-0">
                  <a href="{{ route('personal.news.index', $group) }}"><i class="fas fa-chevron-left"></i></a>
                  Новая запись
              </h1>
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
        <div class="col-3 alert alert-warning" role="alert">{!! session('error') !!}</div>
        @endif

        <div class="row">
          <div class="col-12">
            <form action="{{ route('personal.news.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="form-group w-50">
                <textarea id="summernote" name="content">{{ old('content') }}</textarea>
                @error('content')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div class="form-group w-25">
                <label for="exampleInputFile">Добавьте изображения</label>
                <div class="input-group mb-2">
                  <div class="custom-file">
                    <!-- multiple -->
                    <input type="file" class="custom-file-input" id="imageFiles" name="images[]" accept=".jpg,.jpeg,.png,.bmp,.svg" multiple>
                    <label class="custom-file-label" for="exampleInputFile">Выберите изображение</label>
                  </div>
                </div>

                <div class="form-group">
                  <ul id="load-img-list" class="load-img-list row">
                    <li class="load-img-item d-flex align-items-stretch col-sm-8 mb-2">
                      <img src="#" alt="image" class="prevImage thumb w-25" id="prevImage" mr-3>
                      <p class="mr-2 ml-2">image.jpg</p>
                      <div class="load-img-item__delete">
                        <i data-id="" class="far fa-times-circle text-danger mt-1"></i>
                      </div>
                    </li>
                  </ul>
                </div>

                @error('image')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
                <input type="hidden" name="group_id" value="{{ $group->id }}">
              <div class="form-group">
                <input type="submit" id="submitNews" class="btn btn-primary" value="Добавить">
              </div>
            </form>
          </div>
        </div>


      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('js/news/loadingImages.js') }}"></script>

  <!-- /.content-wrapper -->
  @endsection
