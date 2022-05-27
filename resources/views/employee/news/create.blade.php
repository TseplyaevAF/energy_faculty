  @extends('employee.layouts.main')

  @section('title-block')Добавление новости@endsection

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/news/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/datepicker/cssworld.ru-xcal.css') }}">
  <link rel="stylesheet" href="{{ asset('css/help/style.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
                <a href="{{ route('employee.news.index') }}"><i class="fas fa-chevron-left"></i></a>
                Добавление записи
            </h1>
              @if (isset($olimpType->title))
              <h6 class="m-0 mb-1 ml-4">Категория: {{ $category->title }}</h6>
              <h6 class="m-0 ml-4">Тип мероприятия: {{ $olimpType->title }}</h6>
              @endif
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
        <div class="col-md-4 alert alert-warning" role="alert">{!! session('error') !!}</div>
        @endif

        <div class="row">
          <div class="col-md-12">
            <form action="{{ route('employee.news.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input value="{{ $chair->id }}" type="hidden" name="chair_id">
              <input type="hidden" name="olimp_type" value="{{ $olimpType->id ?? null }}">
              <input type="hidden" name="category_id" value="{{ $category->id }}">
              <div class="form-group col-md-8">
                <h6 class="required">Заголовок</h6>
                <input value="@if($errors->any()){{ old('title') }}@else{{ $news->title ?? null }}@endif" type="text" class="form-control" name="title" placeholder="Заголовок новости">
                @error('title')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div class="form-group col-md-8">
                <h6 class="required">Текст новости</h6>
                <textarea id="summernote" name="content">
                    @if($errors->any()){{ old('content') }}@else{{ $news->content ?? null }}@endif
                </textarea>
                @error('content')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div class="form-group col-md-4">
                    <h6 class="required">Главное изображение</h6>
                    <div class="input-group">
                        <div class="custom-file">
                            <!-- multiple -->
                            <input type="file" class="custom-file-input" name="preview" accept=".jpg,.jpeg,.png">
                            <label class="custom-file-label" for="exampleInputFile">Выберите изображение</label>
                        </div>
                    </div>
                    <div class="img-holder"></div>
                    @error('preview')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

              <div class="form-group col-md-4">
                <h6>Картинки</h6>
                <div class="input-group mb-2">
                  <div class="custom-file">
                    <!-- multiple -->
                    <input type="file" class="custom-file-input" id="imageFiles" name="images[]" accept=".jpg,.jpeg,.png" multiple>
                    <label class="custom-file-label" for="exampleInputFile">Выберите картинки</label>
                  </div>
                </div>
                @include('employee.includes.news.input-images')
                @error('images.*')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div class="form-group col-md-4">
                  <div class="row" style="margin: 0">
                      <i class="far fa-calendar-alt mr-1"></i>
                      <h6>Начало события</h6>
                  </div>
                <input autocomplete="off" type="text" class="form-control"
                       value="{{ old('start_date') }}"name="start_date" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                  @error('start_date')
                  <p class="text-danger">{{ $message }}</p>
                  @enderror
              </div>

              <div class="form-group col-md-4">
                  <div class="row" style="margin: 0">
                      <i class="far fa-calendar-alt mr-1"></i>
                      <h6>Конец события</h6>
                  </div>
                <input autocomplete="off" type="text" class="form-control"
                       value="{{ old('finish_date') }}" name="finish_date" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                  @error('finish_date')
                  <p class="text-danger">{{ $message }}</p>
                  @enderror
              </div>

              <div class="form-group col-md-4">
                    <h6>Тэги</h6>
                    <select class="select2" name="tags_ids[]" multiple="multiple" style="width: 100%;">
                        @foreach ($tags as $tag)
                            <option {{ is_array(old('tags_ids'))
                    && in_array($tag->id, old('tags_ids'))
                    ? 'selected' : ''}} value="{{ $tag->id }}">{{ $tag->title }}</option>
                        @endforeach
                    </select>
                    @error('tags_ids')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

              <div class="form-group col-md-4">
                    <div class="row" style="margin: 0">
                        <h6>Кафедры</h6>
                        <span class="help ml-1" style="bottom: 2px"
                            data-help="Выберите кафедры, совместно с которыми проводится мероприятие">
                            <img class="help help-icon" src="{{ asset('assets/default/question-circle.png') }}">
                        </span>
                    </div>
                    <select class="select2" id="select2__chairs" name="chairs_ids[]" multiple="multiple" style="width: 100%;">
                        @foreach ($chairs as $chairItem)
                            <option {{ is_array(old('chairs_ids'))
                    && in_array($chairItem->id, old('chairs_ids'))
                    ? 'selected' : ''}} value="{{ $chairItem->id }}">{{ $chairItem->title }}</option>
                        @endforeach
                    </select>
                    @error('chairs_ids')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

              <div class="form-group col-md-8">
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
  <script type="text/javascript" src="{{ asset('js/news/cssworld.ru-xcal-en.js') }}"></script>
  <script src="{{ asset('js/news/loadingImages.js') }}"></script>
  <script>
      $(document).ready(function () {
          $('#select2__chairs').select2({
              theme: 'default select2__chairs'
          });
      })
  </script>

  <!-- /.content-wrapper -->
  @endsection
