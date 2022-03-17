@extends('dekanat.layouts.main')

@section('content')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Задолженности</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="col-3 alert alert-success" role="alert">{!! session('success') !!}</div>
                @endif
                    <div class="card-body">
                        <div class="card w-75">
                            <div class="card-header"><b>Заявки студентов на пересдачу задолженностей</b></div>
                            <div class="card-body ">
                                <table class="table table-bordered table-striped" id="exam-sheets-table">
                                    <thead>
                                    <tr>
                                        <th>№ ведомости</th>
                                        <th>Студент</th>
                                        <th>Группа</th>
                                        <th>Дисциплина</th>
                                        <th>Прошлая оценка</th>
                                        <th>Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <!-- Modal -->
                                <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Выдача допуска</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="POST">
                                                    @csrf
                                                    <input type="hidden" id="sheet_id">
                                                    <div class="modal-body">
                                                        <h5>Подписать допуск</h5>
                                                        <div class="input-group mb-2 w-50">
                                                            <div class="custom-file">
                                                                <input type="file" id="file" class="custom-file-input" name="private_key" accept=".key">
                                                                <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                                        <button type="button" class="btn btn-primary" id="signExamSheet">Продолжить</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>
    <script>
        $(document).ready(function () {
            let $modal = $('#ajaxModal');

            let table = $('#exam-sheets-table').DataTable({
                language: {
                    processing: "Подождите...",
                    search: "Поиск:",
                    lengthMenu: "Показать _MENU_ записей",
                    info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                    infoEmpty: "Записи с 0 до 0 из 0 записей",
                    infoFiltered: "(отфильтровано из _MAX_ записей)",
                    loadingRecords: "Загрузка...",
                    zeroRecords: "Записи отсутствуют.",
                    emptyTable: "Заявки не найдены",
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
                    url: "{{ route('dekanat.exam_sheet.index')  }}"
                },
                columns: [
                    {data: 'statement_id', name: 'statement_id'},
                    {data: 'studentFIO', name: 'studentFIO'},
                    {data: 'group.title', name: 'group.title'},
                    {data: 'discipline.title', name: 'discipline.title'},
                    {data: 'old_eval', name: 'old_eval'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    {width: '23%', targets: 3},
                    {width: '13%', targets: 4},
                ]
            });

            table.on("click", ".issueExamSheet", function(e){
                e.preventDefault();
                $("#sheet_id").val($(this).attr('data-id'));
                $modal.modal('show');
            });

            $('#signExamSheet').on('click', function () {
                let sheet_id = $("#sheet_id").val();
                const formData = new FormData();
                formData.append('_token', $("input[name='_token']").val());
                formData.append('private_key', $('#file')[0].files[0]);
                $.ajax({
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    url: "exam_sheets/" + sheet_id,
                    datatype: 'json',
                    success: function (response) {
                        alert(response);
                        $('#ajaxModal').modal('hide');
                        table.draw();
                    },
                    error: function (response) {
                        alert(response.responseText);
                    }
                });
            });
        });
    </script>
@endsection
