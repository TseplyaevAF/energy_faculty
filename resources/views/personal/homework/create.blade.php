  @extends('personal.layouts.main')

  @section('title-block')Добавление решения@endsection

  @section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Добавить решение</h1>
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
            <form action="{{ route('personal.homework.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group w-25">
                <label for="exampleInputFile">Загрузите файл с Вашим решением</label>
                <div class="input-group mb-2">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="homework" accept=".docx,.pdf,.rar,.zip,.txt">
                    <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                  </div>
                </div>
                @error('homework')
                <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <input value="{{ $task->id }}" type="hidden" name="task_id">
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Добавить">
              </div>
            </form>
          </div>
        </div>


      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
  @endsection