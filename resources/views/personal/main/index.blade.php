  @extends('personal.layouts.main')
  
  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            @if (auth()->user()->role_id == 2)
              <h1 class="m-0 mb-2">Личный кабинет студента</h1>
              <h6>{{ auth()->user()->student->group->title }}</h6>
            @elseif (auth()->user()->role_id == 3)
              <h1 class="m-0 mb-2">Личный кабинет преподавателя</h1>
              <h6>{{ auth()->user()->teacher->post }}</h6>
            @endif
            
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            @if (auth()->user()->role_id == 2)
              <li class="breadcrumb-item active">Личный кабинет студента</li>
            @elseif (auth()->user()->role_id == 3)
              <li class="breadcrumb-item active">Личный кабинет преподавателя</li>
            @endif
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection