@extends('admin.layouts.main')

@section('content')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<input type="hidden" value="{{ $chair->id }}" name="chair_id">
<input type="hidden" value="{{ $year->id }}" name="year_id">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">
              <a href="{{ route('admin.lesson.index') }}"><i class="fas fa-chevron-left"></i></a>
              Учебная нагрузка кафедры {{ $chair->title }} на {{ $year->start_year }}-{{ $year->end_year }}
          </h1>
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
        @if (session('success'))
            <div class="col-3 alert alert-success" role="alert">{!! session('success') !!}</div>
        @endif
        @csrf
      <div class="row">
        <div class="card-body col-md-12">
            <div class="mb-2">
                <a href="{{ route('admin.lesson.create', [$chair->id, $year->id]) }}" class="show btn btn-primary">
                    Добавить нагрузку
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="lessons-table">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Группа</th>
                        <th>Семестр</th>
                        <th>Дисциплина</th>
                        <th>Преподаватели</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
      </div>

        {{-- Модальное окно для назначения преподавателя вести указанную нагрузку --}}
        <div class="modal fade" id="newTeacherModal" tabindex="-1" role="dialog"
             aria-labelledby="newTeacherModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title">Назначение преподавателя</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="newTeacherModalBody"></div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>
<script>
    $(document).ready(function () {
        let chairId = $("input[name='chair_id']").val();
        let yearId = $("input[name='year_id']").val();
        let choiceLesson;
        let table = getLessons();

        function getLessons() {
            return $('#lessons-table').DataTable({
                language: {
                    processing: "Подождите...",
                    search: "Поиск:",
                    lengthMenu: "Показать _MENU_ записей",
                    info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                    infoEmpty: "Записи с 0 до 0 из 0 записей",
                    infoFiltered: "(отфильтровано из _MAX_ записей)",
                    loadingRecords: "Загрузка...",
                    zeroRecords: "Записи отсутствуют.",
                    emptyTable: "Группы не найдены",
                    "paginate": {
                        first: "Первая",
                        previous: "Предыдущая",
                        next: "Следующая",
                        last: "Последняя"
                    },
                },
                processing: true,
                serverSide: true,
                info: true,
                ajax: {
                    url: `${yearId}`,
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'group', name: 'group'},
                    {data: 'semester', name: 'semester'},
                    {data: 'discipline', name: 'discipline'},
                    {
                        data: "teacher", name: "teacher",
                        render: function (data, type, row) {
                            if (row.curator === null) {
                                return '<button type="button" ' +
                                    'class="btn btn-primary btn-sm loadTeachers" ' +
                                    'id="lesson_' + row.id + '">Назначить</button>';
                            } else {
                                return '<p class="mr-2">' + row.teacher + '</p>' + '<button type="button" ' +
                                    'class="btn btn-default btn-sm loadTeachers" ' +
                                    'id="lesson_' + row.id + '">Заменить</button>';
                            }
                        }
                    }
                ],
                columnDefs: [
                    {width: '5%', targets: 0},
                    {width: '10%', targets: 1}
                ],
            });
        }

        // назначить нового преподавателя для выбранной нагрузки
        $("#lessons-table").on('click', '.loadTeachers', function() {
            choiceLesson = $(this).attr('id').split('_')[1];
            $.ajax({
                type: 'GET',
                url:  `${yearId}/load-teachers/${choiceLesson}`,
                success: function(response) {
                    $('#newTeacherModal').modal("show");
                    $('#newTeacherModalBody').html(response).show();
                },
                error: function(jqXHR, status, error) {
                    alert('Невозможно получить информацию о преподавателях');
                    console.log(jqXHR.responseText);
                },
                timeout: 8000
            });
        });

        $('.content').on('click', '.setNewTeacher', function () {
            let choiceTeacher = $('#choiceTeacher').val();
            if (choiceTeacher === -1) {
                alert('Пожалуйста, выберите преподавателя');
                return;
            }
            $.ajax({
                type: 'PATCH',
                url:  `${yearId}/${choiceLesson}`,
                data: {
                    '_token': $("input[name='_token']").val(),
                    'teacher_id': choiceTeacher
                },
                success: function(response) {
                    alert(response);
                    $('#newTeacherModal').modal('hide');
                    table.draw();
                },
                error: function(jqXHR, status, error) {
                    if (error === 'Forbidden') {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Произошла ошибка');
                    }
                },
                timeout: 8000
            });
        })
    })
</script>
@endsection
