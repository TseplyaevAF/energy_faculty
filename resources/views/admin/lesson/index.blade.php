  @extends('admin.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Учебная нагрузка</h1>
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
                  <div class="col-md-12">
                      <div class="col-md-3 mb-3">
                          <h6>Кафедра<span class="gcolor"></span></h6>
                          <div class="form-s2 selectChair">
                              <select class="form-control formselect" id="chair_name">
                                  <option value="">-- Не выбрана</option>
                                  @foreach($chairs as $chair)
                                      <option value="{{ $chair->id }}">
                                          {{ $chair->title }}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-md-6 mb-2">
                          <h6>Нагрузка на учебный год:<span class="gcolor"></span></h6>
                          <ul class="selectYear" id="year"></ul>
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
          let appUrl = '{{ getenv('APP_URL') }}';
          let chairId;

          $('#chair_name').on('change', function () {
              chairId = $(this).val();
              if (chairId === '') {
                  alert('Кафедра не выбрана')
                  return;
              }
              let $year = $('#year');
              $year.empty();
              $.ajax({
                  type: 'GET',
                  url: appUrl + 'api/lessons/get-years',
                  data: {'chair_id': chairId},
                  success: function (response) {
                      response.forEach(element => {
                          $year.append(`<li><a href="lessons/${chairId}/${element['id']}">${element['start_year']}-${element['end_year']}</a></li>`);
                      });
                  }
              });
          });

          // $('.selectYear').on('click', function () {
          //     let yearId = $(this).attr('id');
          //     console.log(yearId);
          // });
      });
  </script>
  <!-- /.content-wrapper -->
  @endsection
