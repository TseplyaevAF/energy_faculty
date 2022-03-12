@extends('personal.layouts.main')

@section('content')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
    <link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet"/>

    <style>
        .block {
            display: block;
            padding: 3px 6px 3px 6px;
            border-radius: 2px;

            height: 20px;
            background: green;
            margin: 10px;
            color: white;
            animation: error .4s;
        }

        .inner {
            position: absolute;
            bottom: 0;
        }

        @keyframes error {
            0% {
                height: 0px;
                margin-top: -16px;
            }
            100% {
                height: 20px;
                margin-bottom: '';
            }
        }
    </style>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Ведомость № {{ $statement->id  }}</h1>
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
                            Наименование дисциплины: {{ $statement->lesson->discipline->title }}
                        </div>
                        <div class="form-group">
                            Дата экзамена: {{ date('d.m.Y', strtotime($statement->start_date) ) }}
                        </div>
                        <div class="form-group">
                            Дата сдачи ведомости: {{ date('d.m.Y', strtotime($statement->finish_date)) }}
                        </div>
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
                                            <form id="postForm" name="postForm" action="" enctype="multipart/form-data" method="POST">
                                                @csrf
                                                Подписать ведомость для следующих студентов:
                                                <ul class="studentsList" id="studentsList"></ul>
                                                <input type="hidden" class="individualsList" name="individuals[]">
                                                <h5>Выберите файл с Вашим секретным ключом:</h5>
                                                <div class="input-group mb-2 w-50">
                                                    <div class="custom-file">
                                                        <input type="file" id="file" class="custom-file-input" name="private_key"
                                                               accept=".key">
                                                        <label class="custom-file-label" for="exampleInputFile">Выберите
                                                            файл</label>
                                                    </div>
                                                </div>
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
                            <div class="inner"></div>
                            <div class="form-group mr-3">
                                <a href="javascript:void(0)"
                                   class="btn btn-success btn-sm mb-3" id="saveStatement">
                                    Сохранить
                                </a>
                                <a href="javascript:void(0)"
                                   class="btn btn-success btn-sm mb-3" id="signStatement">
                                    Сохранить и подписать
                                </a>
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
            getIndividuals();

            // GET ALL INDIVIDUALS
            function getIndividuals() {
                table = $('#individuals-table').DataTable({
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
                            data: "eval", name: "eval",
                            render: function (data, type, row) {
                                return '<input class="form-control evalInput" id="eval" name="eval" type="text"' +
                                    'value = ' + row.eval + '>';
                            }
                        },
                        {
                            defaultContent: "",
                            // 02: SETUP CHECKBOX IN THE HEADER
                            sTitle: '<input class="select-checkbox" type="checkbox" id="selectAll"></input>'
                        }
                    ],
                    "drawCallback": function (settings) {
                        $(".evalInput").on("change", function () {
                            var $row = $(this).parents("tr");
                            var rowData = table.row($row).data();

                            rowData.eval = $(this).val();
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

            document.addEventListener('click', function (e) {
                let table = $('#individuals-table').DataTable();
                table.on("click", "th.select-checkbox", function () {
                    if ($("th.select-checkbox").hasClass("selected")) {
                        table.rows().deselect();
                        $("th.select-checkbox").removeClass("selected");
                    } else {
                        table.rows().select();
                        $("th.select-checkbox").addClass("selected");
                    }
                }).on("select deselect", function () {
                    ("Some selection or deselection going on")
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
                var table = $('#individuals-table').DataTable();
                let token = $("input[name='_token']").val();
                var data = table
                    .rows()
                    .data();

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
                        $('.inner').append(`<div class="block">${response}`);
                        setTimeout()
                    }
                });
            });

            $('#signStatement').on('click', function () {
                let table = $('#individuals-table').DataTable();
                var data = table.rows('.selected').data();
                const button = document.querySelector('.signData');
                let students = [];
                data.each(function (value, index) {
                    $('.studentsList').append(
                        '<li class="list-student-item d-flex justify-content-between align-items-center">\
                                <div class="studentFIO">' + value.studentFIO + '</div>\
                        <span class="badge badge-primary badge-pill">' + value.eval + '</span>\
                        </li>');
                    console.log(value);
                    students.push(value);
                });

                if (data.length !== 0) {
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
                        $('#ajaxModal').modal('hide');
                        let table = $('#individuals-table').DataTable();
                        table.draw();
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
