  @extends('admin.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0">
            <a href="{{ route('admin.schedule.index') }}"><i class="fas fa-chevron-left"></i></a>
              {{ $chair->title }}
            </h4>
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
        <div class="col-6">
          <div class="schedule__chair js-schedule-chair" data-id="{{ $chair->id }}">
            <div class="schedule__chair-courses">
              <div class="row">
                <div class="schedule__chair-course col-2">
                  <p class="font-weight-bold">1 курс</p>
                    <div class="schedule__chair-groups">
                      @foreach($groups as $group)
                        @if (($group->course == 1) && ($group->chair_id == $chair->id))
                          <a href="{{ route('admin.schedule.chair.group.show', $group->id) }}">{{$group->title }}</a>
                        @endif 
                      @endforeach
                    </div>
                </div>
                <div class="schedule__chair-course col-2">
                  <p class="font-weight-bold">2 курс</p>
                    <div class="schedule__chair-groups">
                      @foreach($groups as $group)
                        @if (($group->course == 2) && ($group->chair_id == $chair->id))
                          <a href="{{ route('admin.schedule.chair.group.show', $group->id) }}">{{$group->title }}</a>
                        @endif 
                      @endforeach
                    </div>
                </div>
                <div class="schedule__chair-course col-2">
                  <p class="font-weight-bold">3 курс</p>
                    <div class="schedule__chair-groups">
                      @foreach($groups as $group)
                        @if (($group->course == 3) && ($group->chair_id == $chair->id))
                          <a href="{{ route('admin.schedule.chair.group.show', $group->id) }}">{{$group->title }}</a>
                        @endif 
                      @endforeach
                    </div>
                </div>
                <div class="schedule__chair-course col-2">
                  <p class="font-weight-bold">4 курс</p>
                    <div class="schedule__chair-groups">
                      @foreach($groups as $group)
                        @if (($group->course == 4) && ($group->chair_id == $chair->id))
                          <a href="{{ route('admin.schedule.chair.group.show', $group->id) }}">{{$group->title }}</a>
                        @endif 
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
  <script src="{{ asset('js/schedules/showGroups.js') }}"></script>
  <!-- /.content-wrapper -->
  @endsection