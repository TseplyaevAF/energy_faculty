@extends('employee.layouts.main')

@section('content')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<input type="hidden" value="{{ $chair->id }}" name="chair_id">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Учебные группы</h1>
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
      <div class="row">
        <div class="card-body col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="groups-table">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Группа</th>
                        <th>Староста</th>
                        <th>Куратор</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
      </div>

        {{--Модальное окно для назначения нового куратора группы--}}
        <div class="modal fade" id="newCuratorModal" tabindex="-1" role="dialog"
             aria-labelledby="newCuratorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title">Назначение нового куратора</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="newCuratorModalBody">

                    </div>
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
        let choiceGroup;
        let chairId = $("input[name='chair_id']").val();
        let groupsTable = getGroups();

        function getGroups() {
            return $('#groups-table').DataTable({
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
                    url: "{{ route('employee.group.index') }}"
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'group', name: 'group'},
                    {data: 'headman', name: 'headman'},
                    {
                        data: "curator", name: "curator",
                        render: function (data, type, row) {
                            if (row.curator === null) {
                                return '<button type="button" ' +
                                    'class="btn btn-primary btn-sm loadTeachers" ' +
                                    'id="group_' + row.id + '">Назначить</button>';
                            } else {
                                return '<p class="mr-2">' + row.curator + '</p>' + '<button type="button" ' +
                                    'class="btn btn-default btn-sm loadTeachers" ' +
                                    'id="group_' + row.id + '">Заменить</button>';
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

        // назначить новую старосту группы
        $("#groups-table").on('click', '.loadTeachers', function() {
            choiceGroup = $(this).attr('id').split('_')[1];
            $.ajax({
                type: 'GET',
                url:  'groups/load-teachers/' + chairId + '/' + choiceGroup,
                success: function(response) {
                    $('#newCuratorModal').modal("show");
                    $('#newCuratorModalBody').html(response).show();
                },
                error: function(jqXHR, status, error) {
                    alert('Невозможно получить информацию о преподавателях');
                    console.log(jqXHR.responseText);
                },
                timeout: 8000
            });
        });

        $('.content').on('click', '.setNewCurator', function () {
            let choiceTeacher = $('#choiceTeacher').val();
            if (choiceTeacher === -1) {
                alert('Пожалуйста, выберите преподавателя');
                return;
            }
            $.ajax({
                type: 'PATCH',
                url:  'groups/set-new-curator/' + choiceGroup,
                data: {
                    '_token': $("input[name='_token']").val(),
                    'teacher_id': choiceTeacher
                },
                success: function(response) {
                    alert(response);
                    $('#newCuratorModal').modal('hide');
                    groupsTable.draw();
                },
                error: function(jqXHR, status, error) {
                    alert('Произошла ошибка');
                    console.log(jqXHR.responseText);
                },
                timeout: 8000
            });
        })
    })
</script>
@endsection
