  @extends('employee.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <link rel="stylesheet" href="{{ asset('css\schedule\style.css') }}">
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
          <div class="schedules__chairs-col col-md-6" style="padding-left: 15px;">
              <div class="schedule__export-template">
                  <h6>
                      <a type="button" class="scheduleTemplateDownload mb-2">
                          Скачать шаблон расписания
                      </a>
                  </h6>
              </div>
            <div class="schedule__chairs-result">
                <div class="schedule__chair js-schedule-chair mb-2" id="chair-{{ $chair->id }}">
                  <div class="schedule__chair-courses">
                    <div class="row">
                        @foreach($arrayGroupsByYear as $year => $groupsByYear)
                      <div class="schedule__chair-course col-2 mb-4">
                        <p class="font-weight-bold" style="margin: 0">{{$year}}</p>
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
          $('.scheduleTemplateDownload').on('click', function () {
              $.ajax({
                  type: 'GET',
                  url: '{{ route('employee.schedule.exportTemplate') }}',
                  success: function(response) {
                      downloadFile(response);
                  }
              });
          });

          function downloadFile(response) {
              var a = document.createElement("a");
              a.href = response.file;
              a.download = response.file_name;
              document.body.appendChild(a);
              a.click();
              a.remove();
          }
      });
  </script>

  <!-- /.content-wrapper -->
  @endsection
