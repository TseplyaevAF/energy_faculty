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
              Добавление пользователя
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
            <form action="{{ route('admin.user.store') }}" method="POST">
              @csrf
              <label for="exampleInputFile">Личные данные</label>
              <div class="form-group w-25">
                <input value="{{ old('surname') }}" type="text" class="form-control" name="surname" placeholder="Фамилия">
                @error('surname')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group w-25">
                <input value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Имя">
                @error('name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group w-25">
                <input value="{{ old('patronymic') }}" type="text" class="form-control" name="patronymic" placeholder="Отчество">
                @error('patronymic')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="input-group w-25 mb-2">
                <div class="custom-file">
                  <!-- multiple -->
                  <input type="file" class="custom-file-input" name="avatar" accept=".jpg,.jpeg,.png,.bmp,.svg">
                  <label class="custom-file-label" for="exampleInputFile">Выберите аватар</label>
                </div>
              </div>
              @error('avatar')
              <p class="text-danger">{{ $message }}</p>
              @enderror

              <label for="exampleInputFile" class="mt-4">Контактные данные</label>
              <div class="input-group w-25 mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-phone"></i></span>
                </div>
                <input value="{{ old('phone_number') }}" class="form-control" id="phone" type="tel" name="phone_number">
              </div>
              @error('phone_number')
              <p class="text-danger">{{ $message }}</p>
              @enderror
              <div class="input-group w-25 mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope-square"></i></span>
                </div>
                <input value="{{ old('email') }}" class="form-control" type="email" name="email">
              </div>
              @error('email')
              <p class="text-danger">{{ $message }}</p>
              @enderror

              <label for="exampleInputFile" class="mt-4">Пароль</label>
              <div class="form-group w-25">
                <input value="{{ old('password') }}" type="password" class="form-control" name="password" placeholder="Пароль">
                @error('password')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div class="form-group w-25 ">
                <label>Выберите роль пользователя</label>
                <select name="role_id" class="form-control">
                  @foreach($roles as $id => $role)
                  <option value="{{ $id }}" {{$id == old('role_id') ? 'selected' : ''}}>{{ $role }}</option>
                  @endforeach
                </select>
              </div>

              @include('admin.includes.users.create_student')
              @include('admin.includes.users.create_teacher')
              @include('admin.includes.users.create_employee')

              <input type="submit" class="btn btn-primary mb-2" value="Добавить">
            </form>
          </div>
        </div>


      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('js/users/selectRole.js') }}"></script>
  <!-- /.content-wrapper -->
  @endsection