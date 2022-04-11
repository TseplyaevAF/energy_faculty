@extends('dekanat.layouts.main')

@section('content')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
    <style>
        .finish-date {
            background-color: rgba(17, 163, 32, 0.27) !important;
        }

        .not-finish-date {
            background-color: rgba(12, 97, 187, 0.27) !important;
        }
    </style>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Ведомости</h1>
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
                    <div class="row w-25">
                        <div class="form-group col-md-6">
                            <h6>Группы<span class="gcolor"></span></h6>
                            <div class="form-s2">
                                <div>
                                    <select class="form-control formselect required" placeholder="Группы"
                                            id="group_name">
                                        <option value="reset_filter_group">Все группы</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}">
                                                {{ $group->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <h6>Учебный год</h6>
                            <select class="form-control formselect required" placeholder="Select Sub Category"
                                    id="year"></select>
                        </div>
                    </div>
                    <div class="card w-75">
                        <div class="card-header"><b>Экзаменационные ведомости ЭФ</b></div>
                        <div class="card-body ">
                            <a href="javascript:void(0)"
                               class="btn btn-success btn-sm mb-3" id="addNewStatement">
                                Добавить ведомость
                            </a>
                            <table class="table table-bordered table-striped" id="statements-table">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Группа</th>
                                    <th>Учебный год</th>
                                    <th>Семестр</th>
                                    <th>Дисциплина</th>
                                    <th>Форма контроля</th>
                                    <th>Дата сдачи ведомости</th>
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
                                            <h5 class="modal-title" id="exampleModalLabel">Добавление ведомости</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <select class="form-control formselect required" placeholder="Группы"
                                                    id="group_name_add">
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}">
                                                        {{ $group->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                            <button type="button" id="goToFormAddStatement" class="btn btn-primary">Продолжить</button>
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
            let filter_group = '';
            getStatements();

            // GET ALL STATEMENTS
            function getStatements(filter_group = '', filter_year = '') {
                $('#statements-table').DataTable({
                    language: {
                        processing: "Подождите...",
                        search: "Поиск:",
                        lengthMenu: "Показать _MENU_ записей",
                        info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                        infoEmpty: "Записи с 0 до 0 из 0 записей",
                        infoFiltered: "(отфильтровано из _MAX_ записей)",
                        loadingRecords: "Загрузка...",
                        zeroRecords: "Записи отсутствуют.",
                        emptyTable: "Ведомости не найдены",
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
                    ajax    : {
                        url: "{{ route('dekanat.statement.index')  }}",
                        data: {group: filter_group, year: filter_year}
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'group.title', name: 'group.title'},
                        {data: 'year', name: 'year'},
                        {data: 'semester', name: 'semester'},
                        {data: 'discipline.title', name: 'discipline.title'},
                        {data: 'control_form', name: 'control_form'},
                        {data: 'finish_date', name: 'finish_date'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    columnDefs: [
                        {width: '5%', targets: 3},
                        {width: '35%', targets: 4}
                    ],
                    "createdRow": function( row, data){
                        let now = new Date();
                        let pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
                        let finish_date = new Date(data.finish_date.replace(pattern,'$3-$2-$1'));
                        if( finish_date <  now){
                            $(row).addClass('finish-date');
                        } else {
                            $(row).addClass('not-finish-date');
                        }
                    }
                });
            }

            $('#group_name').on('change', function () {
                let id = $(this).val();
                $('#statements-table').DataTable().destroy();
                if (id === 'reset_filter_group') {
                    filter_group = '';
                    $('#year').empty();
                    getStatements();
                    return;
                }
                filter_group = id;
                getStatements(id, '');
                $('#year').empty();
                $('#year').append(`<option value="0" disabled selected>Поиск...</option>`);
                $.ajax({
                    type: 'GET',
                    url: 'statements/getYears/' + id,
                    success: function (response) {
                        var response = JSON.parse(response);
                        console.log(response);
                        $('#year').empty();
                        $('#year').append(`<option value="reset_filter_year">Весь период обучения</option>`);
                        response.forEach(element => {
                            $('#year').append(`<option value="${element['id']}">${element['start_year']}-${element['end_year']}</option>`);
                        });
                    }
                });
            });

            $('#year').on('change', function () {
                let id = $(this).val();
                $('#statements-table').DataTable().destroy();
                if (id === 'reset_filter_year') {
                    getStatements(filter_group);
                    return;
                }
                getStatements(filter_group, id);
            });

            $('#addNewStatement').on('click', function () {
                $('#ajaxModal').modal('show');
            });

            $('#goToFormAddStatement').on('click', function () {
                let groupId = $('#group_name_add').val();
                window.location = 'statements/create/' + groupId;
            });
        });
    </script>
@endsection
