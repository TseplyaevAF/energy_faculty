  @extends('admin.layouts.main')

  @section('content')
  <link rel="stylesheet" href="{{ asset('css/users/style.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
            <a href="{{ route('admin.user.index') }}"><i class="fas fa-chevron-left"></i></a>
              Редактирование пользователя
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
        <div class="col-3 alert alert-warning" role="alert">{{ session('error') }}</div>
        @endif

        <div class="row">
          <div class="col-12">
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
              @csrf
              @method('PATCH')
              <label for="exampleInputFile">Личные данные</label>
              <div class="form-group w-25">
                <input value="{{ $user->surname }}" type="text" class="form-control" name="surname" placeholder="Фамилия">
                @error('surname')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group w-25">
                <input value="{{ $user->name }}" type="text" class="form-control" name="name" placeholder="Имя">
                @error('name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group w-25">
                <input value="{{ $user->patronymic }}" type="text" class="form-control" name="patronymic" placeholder="Отчество">
                @error('patronymic')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <label for="exampleInputFile" class="mt-2">Контактные данные</label>
              <div class="input-group mb-2 w-25">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-phone"></i></span>
                </div>
                <input value="{{ $user->phone_number }}" class="form-control" id="phone" type="tel" name="phone_number">
              </div>
              @error('phone_number')
              <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="input-group mb-2 w-25">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope-square"></i></span>
                </div>
                <input value="{{ $user->email }}" class="form-control" type="email" name="email">
              </div>
              @error('email')
              <p class="text-danger">{{ $message }}</p>
              @enderror

              @if ($user->role_id === 2)
                @include('admin.includes.users.edit_student')
              @elseif ($user->role_id === 3)
                @include('admin.includes.users.edit_teacher')
              @endif
              <input value="{{ $user->id }}" class="form-control" type="hidden" name="user_id">

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
