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
              @csrf
              <div class="row">
                  <div class="form-group col-md-12">
                      <div class="col-md-3 mb-3">
                          <h6>Кафедра<span class="gcolor"></span></h6>
                          <div class="form-s2 selectChair">
                              <select class="form-control formselect" id="chair_name">
                                  @foreach($chairs as $chair)
                                      <option value="{{ $chair->id }}">
                                          {{ $chair->title }}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-md-3 mb-3">
                          <div class="row" style="margin-left: 0;">
                            <h6>Учебный год<span class="gcolor"></span></h6>
                              <a type="button" data-toggle="modal" class="addYear ml-1"
                                 style="top: 2px;" data-target="#addYearModal">
                                  <i class="fas fa-plus"></i>
                              </a>
                          </div>
                          <select class="form-control formselect required" id="year_name">
                          </select>
                      </div>
                      <div class="col-md-3">
                          <button type="button" id="show-lessons" class="btn btn-info mb-3">
                              Показать учебную нагрузку
                          </button>
                      </div>
                    </div>
              </div>
          </div><!-- /.container-fluid -->
      </section>

      <div class="modal fade" id="addYearModal" tabindex="-1" role="dialog"
           aria-labelledby="addYearModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="card-title">Добавление нового учебного года</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                          <label>Введите год обучения</label>
                          <input type="text" class="form-control" id="year" name="year" placeholder="2021-2022">
                      </div>
                      <button type="button" id="addYear" class="btn btn-primary addYear">
                          Сохранить
                      </button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                  </div>
              </div>
          </div>
      </div>
      <!-- /.content -->
  </div>
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script>
      $(document).ready(function () {
          getYears();

          function getYears() {
              $.ajax({
                  type: 'GET',
                  url: `lessons/get-years`,
                  success: function (response) {
                      let $yearName = $('#year_name');
                      $yearName.empty();
                      JSON.parse(response).forEach(element => {
                          $yearName.append(`<option value="${element['id']}">
                          ${element['start_year']}-${element['end_year']}</option>`);
                      });
                  }
              });
          }

          $('#show-lessons').on('click', function () {
              let choiceChair = $('#chair_name').val();
              let choiceYear = $('#year_name').val();
              location.href = `lessons/${choiceChair}/${choiceYear}`;
          });

          $('#addYear').on('click', function () {
              $.ajax({
                  type: 'POST',
                  url:  `lessons/add-year`,
                  data: {
                      '_token': $("input[name='_token']").val(),
                      'year': $("input[name='year']").val()
                  },
                  success: function() {
                      $('#addYearModal').modal('hide');
                      $("input[name='year']").val("");
                      getYears();
                  },
                  error: function (response) {
                    alert(response.responseText);
                  }
              });
          });
      });
  </script>
  <!-- /.content-wrapper -->
  @endsection
