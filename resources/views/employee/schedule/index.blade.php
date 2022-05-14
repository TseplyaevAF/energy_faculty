  @extends('employee.layouts.main')
  <link rel="stylesheet" href="{{ asset('css\employee\schedule\style.css') }}">

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="padding-left: 8px;">Расписание занятий</h1>
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
        <div class="row">
          <div class="schedules__chairs-col col-6" style="padding-left: 0;">
            <div class="schedule__chairs-result">
                <div class="schedule__chair js-schedule-chair mb-3" id="chair-{{ $chair->id }}">
                  <div class="schedule__chair-courses">
                    <div class="row">
                        @foreach($arrayGroupsByYear as $year => $groupsByYear)
                      <div class="schedule__chair-course col-2">
                        <p class="font-weight-bold">{{$year}}</p>
                          <div class="schedule__chair-groups">
                              @foreach($groupsByYear as $group)
                            <a href="{{ route('employee.schedule.group.show', $group['id']) }}"
                               class="schedule__chairs-groups__item">
                                {{$group['title'] }}
                            </a>
                              @endforeach
                          </div>
                      </div>
                        @endforeach
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script>
      $(document).ready(function () {

      });
  </script>

  <!-- /.content-wrapper -->
  @endsection
