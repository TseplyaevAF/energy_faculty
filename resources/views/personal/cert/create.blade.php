  @extends('personal.layouts.main')

  @section('title-block')Отправка заявки на получение сертификата@endsection

  @section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" xmlns="http://www.w3.org/1999/html">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Заявка на получение электронной подписи</h1>
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
            <form action="{{ route('personal.cert.store') }}" method="POST">
              @csrf
                <label for="exampleInputFile">Личные данные</label>
                <div class="form-group w-25">
                    <input value="{{ auth()->user()->surname }}" type="text" class="form-control" placeholder="Фамилия" readonly>
                </div>
                <div class="form-group w-25">
                    <input value="{{ auth()->user()->name }}" type="text" class="form-control" placeholder="Имя" readonly>
                </div>
                <div class="form-group w-25">
                    <input value="{{ auth()->user()->patronymic }}" type="text" class="form-control" placeholder="Отчество" readonly>
                </div>
                <div class="form-group w-25">
                    <input value="{{ auth()->user()->email }}" type="text" class="form-control" placeholder="Email" readonly>
                </div>
                <input type="hidden" name="teacher_id" value="{{ auth()->user()->teacher->id }}">

              <div class="form-group">
                  <button type="submit" class="btn btn-warning btn-sm btn-complete">
                      Отправить заявку
                  </button>
              </div>
            </form>
          </div>
        </div>


      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/personal/cert/complete.js') }}"></script>
  @endsection
