@extends('employee.layouts.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Информация о кафедре</h1>
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
        @if (session('success'))
            <div class="col-3 alert alert-success" role="alert">{!! session('success') !!}</div>
        @endif
      <div class="row">
        <div class="col-md-12">
          <form action="{{ route('employee.chair.update', $chair->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group col-md-8">
              <input value="{{ $chair->full_title }}" type="text" class="form-control" name="full_title" placeholder="Полное название кафедры">
              @error('full_title')
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <input value="{{ $chair->title }}" type="text" class="form-control" name="title" placeholder="Сокращенное название кафедры">
              @error('title')
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <input value="{{ $chair->address }}" type="text" class="form-control" name="address" placeholder="Адрес кафедры">
              @error('address')
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <input value="{{ $chair->cabinet }}" type="text" class="form-control" name="cabinet" placeholder="Кабинет зав. кафедрой">
              @error('cabinet')
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-md-6">
                  <h6>Контактные данные</h6>
                  <div class="input-group mb-2">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-phone"></i></span>
                      </div>
                      <input value="{{ $chair->phone_number }}" class="form-control" id="phone" type="tel" name="phone_number">
                      @error('phone_number')
                      <p class="text-danger">{{ $message }}</p>
                      @enderror
                  </div>
                  <div class="input-group mb-4">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-envelope-square"></i></span>
                      </div>
                      <input value="{{ $chair->email }}" class="form-control" type="email" name="email">
                      @error('email')
                      <p class="text-danger">{{ $message }}</p>
                      @enderror
                  </div>
              </div>
            <div class="form-group col-md-12">
              <h6>Направления подготовки</h6>
              <textarea id="summernote" name="description">{{ $chair->description }}</textarea>
              @error('description')
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <input value="{{ $chair->video }}" type="text" class="form-control" name="video" placeholder="Ссылка на видео о кафедре">
              @error('video')
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <input value="{{ $chair->id }}" type="hidden" name="chair_id">
            <input type="submit" class="btn btn-primary mb-2" value="Сохранить">
          </form>
        </div>
      </div>


    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
