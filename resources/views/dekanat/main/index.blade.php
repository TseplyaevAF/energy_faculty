  @extends('dekanat.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 mb-2">Деканат энергетического факультета ЗабГУ</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $statementsCount }}</h3>
                <p>Непроверенные ведомости</p>
              </div>
              <div class="icon">
                <i class="far fa-list-alt"></i>
              </div>
              <a href="{{ route('dekanat.statement.index') }}" class="small-box-footer">Перейти...<i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ $examSheetsCount }}</h3>
                <p>Заявки на пересдачу</p>
              </div>
              <div class="icon">
                <i class="fas fa-list-ol"></i>
              </div>
              <a href="{{ route('dekanat.exam_sheet.index') }}" class="small-box-footer">Перейти...<i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
