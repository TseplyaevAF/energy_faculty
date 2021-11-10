  @extends('admin.layouts.main')
  <link rel="stylesheet" href="{{ asset('css\schedule\style.css') }}">

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
          <div class="schedules__chairs-col col-3" style="padding-right: 0;">
            <div class="schedule__chairs-items text-wrap">
              @foreach($chairs as $chair)
                <a
                  class="schedule__chairs-item"
                  style="display:block"
                  href="javascript:void(0);"
                  id="{{ $chair->id }}"
                  onclick="showDiv('{{ $chair->id }}', '{{ json_encode($chairsIds) }}');">
                  <div class="schedule__chairs-title">
                    <u>{{ $chair->title }}</u>
                  </div>
                </a>
              @endforeach
            </div>
          </div>
          <div class="schedules__chairs-col col-6" style="padding-left: 0;">
            <div class="schedule__chairs-result">
              @foreach($chairs as $chair)
                <div class="schedule__chair js-schedule-chair mb-3" id="chair-{{ $chair->id }}" style="display:none;">
                  <div class="schedule__chair-courses">
                    <div class="row">
                      <div class="schedule__chair-course col-2">
                        <p class="font-weight-bold">1 курс</p>
                          <div class="schedule__chair-groups">
                            @foreach($groups as $group)
                              @if (($group->course == 1) && ($group->chair_id == $chair->id))
                                <a
                                  class="schedule__chairs-groups__item"
                                  href="{{ route('admin.schedule.group.show', $group->id) }}">
                                  {{$group->title }}
                                </a>
                              @endif 
                            @endforeach
                          </div>
                      </div>
                      <div class="schedule__chair-course col-2">
                        <p class="font-weight-bold">2 курс</p>
                          <div class="schedule__chair-groups">
                            @foreach($groups as $group)
                              @if (($group->course == 2) && ($group->chair_id == $chair->id))
                                <a
                                  class="schedule__chairs-groups__item"
                                  href="{{ route('admin.schedule.group.show', $group->id) }}">{{$group->title }}</a>
                              @endif 
                            @endforeach
                          </div>
                      </div>
                      <div class="schedule__chair-course col-2">
                        <p class="font-weight-bold">3 курс</p>
                          <div class="schedule__chair-groups">
                            @foreach($groups as $group)
                              @if (($group->course == 3) && ($group->chair_id == $chair->id))
                                <a
                                  class="schedule__chairs-groups__item"
                                  href="{{ route('admin.schedule.group.show', $group->id) }}">{{$group->title }}</a>
                              @endif 
                            @endforeach
                          </div>
                      </div>
                      <div class="schedule__chair-course col-2">
                        <p class="font-weight-bold">4 курс</p>
                          <div class="schedule__chair-groups">
                            @foreach($groups as $group)
                              @if (($group->course == 4) && ($group->chair_id == $chair->id))
                                <a
                                  class="schedule__chairs-groups__item"
                                  href="{{ route('admin.schedule.group.show', $group->id) }}">{{$group->title }}</a>
                              @endif 
                            @endforeach
                          </div>
                      </div>
                    </div>
                  </div>
              </div>
              @endforeach  
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