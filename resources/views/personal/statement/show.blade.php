@extends('personal.layouts.main')

@section('content')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
    <link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet"/>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Ведомость № {{ $statement->id  }}</h1>
                        <input type="hidden" class="inputStatement" value="{{ $statement->id }}">
                        <input type="hidden" name="control_form" value="{{ $statement->control_form }}">
                        <input type="hidden" name="individuals_length" value="{{ $statement->individuals }}">
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
                <div class="card w-75">
                    <div class="card-header">
                        <div class="form-group">
                            Группа: {{ $statement->lesson->group->title  }},
                            семестр: {{ $statement->lesson->semester  }},
                            учебный год: {{ $statement->lesson->year->start_year  }}
                            -{{ $statement->lesson->year->end_year  }}
                        </div>
                        <div class="form-group">
                            Контроль: {{ $statement->lesson->discipline->title }}
                        </div>
                        <div class="form-group">
                            Форма контроля: {{ $controlForms[$statement->control_form] }}
                        </div>
                        @if (isset($statement->start_date))
                        <div class="form-group">
                            Дата экзамена: {{ date('d.m.Y', strtotime($statement->start_date) ) }}
                        </div>
                        @endif
                        @if (isset($statement->finish_date))
                        <div class="form-group">
                            Дата сдачи ведомости: {{ date('d.m.Y', strtotime($statement->finish_date)) }}
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            @csrf
                            <table class="table table-bordered table-striped" id="individuals-table">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>ФИО</th>
                                    <th>№ зачетной книжки</th>
                                    <th>Оценка</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </form>
                        <!-- Modal -->
                        <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Подписать ведомость</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="postForm" name="postForm" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            Подписать ведомость для следующих студентов:
                                            <ul class="studentsList" id="studentsList"></ul>
                                            <input type="hidden" class="individualsList" name="individuals[]">
                                            <h5>Выберите файл с Вашим ключом:</h5>
                                            <div class="input-group mb-2">
                                                <div class="custom-file">
                                                    <input type="file" id="file" class="custom-file-input"
                                                           name="private_key"
                                                           accept=".key">
                                                    <label class="custom-file-label" for="exampleInputFile">Выберите
                                                        файл</label>
                                                </div>
                                            </div>
                                            <b>
                                                Подпись должен поставить:
                                                {{ $statement->lesson->teacher->user->surname }}
                                                {{ mb_substr($statement->lesson->teacher->user->name, 0, 1) }}.
                                                {{ mb_substr($statement->lesson->teacher->user->patronymic, 0, 1) }}.
                                            </b>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="closeModal" class="btn btn-secondary"
                                                data-dismiss="modal">Закрыть
                                        </button>
                                        <button type="button" id="signData" class="btn btn-primary signData">
                                            Подписать
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mr-3">
                            <a href="javascript:void(0)"
                               class="btn btn-success btn-sm mb-3" id="saveStatement">
                                Сохранить
                            </a>
                            <a href="javascript:void(0)"
                               class="btn btn-success btn-sm mb-3" id="signStatement">
                                Подписать
                            </a>
                        </div>
                        <div class="form-group">
                            <h5>Результаты контроля:</h5>
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>№ записи</th>
                                    <th>ФИО</th>
                                    <th>№ зачетной книжки</th>
                                    <th>Оценка</th>
                                    <th>Дата сдачи</th>
                                </tr>
                                </thead>
                                <tbody class="completed-sheets"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js" defer></script>
    <script>
        $(document).ready(function () {
            let evalTypes;
            let table;
            let individualsLength = JSON.parse($("input[name='individuals_length']").val()).length;

            $.ajax({
                type: 'GET',
                url: '{{ route('personal.statement.getEvalTypes') }}',
                success: function (response) {
                    evalTypes = JSON.parse(response);
                    let controlForm = $("input[name='control_form']").val();
                    if (controlForm === "1") {
                        delete evalTypes[2];
                        delete evalTypes[3];
                        delete evalTypes[4];
                    } else {
                        delete evalTypes[1];
                    }
                    getCompletedSheets();
                    table = getTables();
                },
                error: function (response) {
                    console.log(response);
                }
            });

            function getTables() {
                return $('#individuals-table').DataTable({
                    language: {
                        processing: "Подождите...",
                        search: "Поиск:",
                        lengthMenu: "Показать _MENU_ записей",
                        info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                        infoFiltered: "(отфильтровано из _MAX_ записей)",
                        loadingRecords: "Загрузка...",
                        zeroRecords: "Записи отсутствуют.",
                        emptyTable: "Студенты не найдены"
                    },
                    processing: true,
                    serverSide: true,
                    info: true,
                    paging: false,
                    stateSave: true,
                    ajax: {
                        url: "{{ route('personal.statement.show', $statement->id ) }}"
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'studentFIO', name: 'studentFIO'},
                        {data: 'student_id_number', name: 'student_id_number'},
                        {
                            data: "evaluation", name: "evaluation",
                            render: function (data, type, row) {
                                var select = '<select class="form-control evalSelect" type="text" name="evaluation">';
                                select += '<option value="">-- Оценка не выбрана</option>';
                                for (const [key, value] of Object.entries(evalTypes)) {
                                    if (value === evalTypes[row.evaluation]) {
                                        select += '<option value="' + row.evaluation + '" selected="true">' + evalTypes[row.evaluation] + '</option>';
                                    } else {
                                        select += '<option value="' + key + '">' + value + '</option>';
                                    }
                                }
                                select += '</select>';
                                return select;
                            }
                        },
                        {
                            defaultContent: "",
                            // 02: SETUP CHECKBOX IN THE HEADER
                            sTitle: '<input class="select-checkbox" type="checkbox" id="selectAll"></input>'
                        }
                    ],
                    "drawCallback": function (settings) {
                        $(".evalSelect").on("change", function () {
                            var $row = $(this).parents("tr");
                            var rowData = table.row($row).data();

                            rowData.evaluation = $(this).val();
                        })
                    },
                    columnDefs: [
                        {
                            orderable: false,
                            className: 'select-checkbox',
                            targets: 4
                        }
                    ],
                    select: {
                        style: 'multi',
                        selector: 'td:last-child' // 01: ONLY CHECK ROW WHEN FIRST TD ROW IS CLICKED
                    },
                });
            }

            function getCompletedSheets() {
                let id = $('.inputStatement').val();
                $.ajax({
                    type: 'GET',
                    url: 'getCompletedSheets/' + id,
                    success: function (response) {
                        let data = JSON.parse(response);
                        $('.completed-sheets').find('tr').remove();
                        $.each(data, function (key, item) {
                            $('.completed-sheets').append('<tr>\
                            <td>' + key + '</td>\
                            <td>' + item.studentFIO + '</td>\
                            <td>' + item.student_id_number + '</td>\
                            <td>' + evalTypes[item.evaluation] + '</td>\
                            <td>' + item.exam_finish_date + '</td>\
                            </tr>');
                        })
                    }
                });
            }

            document.addEventListener('click', function (e) {
                table.on("click", "th.select-checkbox", function () {
                    if ($("th.select-checkbox").hasClass("selected")) {
                        table.rows().deselect();
                        $("th.select-checkbox").removeClass("selected");
                    } else {
                        table.rows().select();
                        $("th.select-checkbox").addClass("selected");
                    }
                }).on("select deselect", function () {
                    if (table.rows({
                        selected: true
                    }).count() !== table.rows().count()) {
                        $("th.select-checkbox").removeClass("selected");
                    } else {
                        $("th.select-checkbox").addClass("selected");
                    }
                });
            });

            $('#saveStatement').on('click', function () {
                let token = $("input[name='_token']").val();
                const data = table.rows().data();
                let requestRows = [];
                data.each(function (value) {
                    requestRows.push(value);
                });
                $.ajax({
                    type: 'POST',
                    data: {
                        '_token': token,
                        "rows": requestRows
                    },
                    url: "{{ route('personal.statement.saveData')  }}",
                    datatype: 'json',
                    success: function (response) {
                        // $('.inner').append(`<div class="block">${response}`);
                        alert(response);
                    }
                });
            });

            $('#signStatement').on('click', function () {
                const data = table.rows('.selected').data();
                const button = document.querySelector('.signData');
                let students = [];
                data.each(function (individual) {
                    let eval;
                    for (const [key, value] of Object.entries(evalTypes)) {
                        if (value === evalTypes[individual.evaluation]) {
                            eval = evalTypes[individual.evaluation];
                            break;
                        } else {
                            eval= "-1";
                        }
                    }
                    if (eval === "-1") {
                        $('.studentsList').append(
                            '<li class="list-student-item d-flex justify-content-between align-items-center">\
                                    <div class="studentFIO">' + individual.studentFIO + '</div>\
                        <span class="badge badge-warning badge-pill">-- Оценка не выбрана</span>\
                        </li>');
                    } else {
                        $('.studentsList').append(
                            '<li class="list-student-item d-flex justify-content-between align-items-center">\
                                    <div class="studentFIO">' + individual.studentFIO + '</div>\
                        <span class="badge badge-primary badge-pill">' + eval + '</span>\
                        </li>');
                    }
                    students.push(individual);
                });

                if (data.length === individualsLength) {
                    button.disabled = false;
                    document.getElementsByName('individuals[]').value = students;
                } else {
                    button.disabled = true;
                }

                $('#ajaxModal').modal('show');
            });

            $("#signData").on("click", function () {
                var formData = new FormData()
                formData.append('_token', $("input[name='_token']").val());
                formData.append('individuals', JSON.stringify(document.getElementsByName('individuals[]').value));
                formData.append('private_key', $('#file')[0].files[0]);
                $.ajax({
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    url: "{{ route('personal.statement.signStatement', $statement->id) }}",
                    datatype: 'json',
                    success: function (response) {
                        alert(response);
                        $('#ajaxModal').modal('hide');
                        table.draw();
                        getCompletedSheets();
                    },
                    error: function (response) {
                        alert(response.responseText);
                    }
                });
            })

            $("#ajaxModal").on("hidden.bs.modal", function () {
                function clearLi() {
                    document.getElementById("studentsList").innerHTML = "";
                }
                setTimeout(clearLi, 200);
            });
        });
    </script>
@endsection
