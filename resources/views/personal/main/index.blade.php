  @extends('personal.layouts.main')
  
  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            @if (isset(auth()->user()->role->student_id))
              <h1 class="m-0 mb-2">Личный кабинет студента</h1>
              <h6>{{ auth()->user()->role->student->group->title }}</h6>
            @elseif (isset(auth()->user()->role->teacher_id))
              <h1 class="m-0 mb-2">Личный кабинет преподавателя</h1>
              <h6>{{ auth()->user()->role->teacher->post }}</h6>
            @endif
            
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            @if (isset(auth()->user()->role->student_id))
              <li class="breadcrumb-item active">Личный кабинет студента</li>
            @elseif (isset(auth()->user()->role->teacher_id))
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