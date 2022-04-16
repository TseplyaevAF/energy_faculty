@extends('personal.layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/marks/style.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Панель управления куратора</h1>
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
                    <div class="form-group selectGroup">
                        <h6>Мои группы<span class="gcolor"></span></h6>
                        <div>
                            <select class="col-md-2 form-control formselect group_name"
                                    id="group_name">
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="tabs vertical">
                            <ul class="tabs__caption">
                                <li data-id="about-group">О группе</li>
                                <li data-id="statements">Ведомости</li>
                                <li data-id="semester-statements">Семестровки</li>
                                <li data-id="tasks">Задания</li>
                            </ul>
                            <div class="tabs__content">
                                <div id="about_group_preloader">
                                    <img src="{{ asset('storage/loading.gif') }}"
                                         alt="AJAX loader" title="AJAX loader"/>
                                </div>
                                <div class="row filters">

                                </div>
                                <h5>Староста группы:</h5>
                                <div class="group-headman-info mb-3">
                                    Ларионова Мария Анатольева, 89121212214
                                </div>
                                <h5>Студенты группы:</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="students-table">
                                        <thead>
                                        <tr>
                                            <th>№</th>
                                            <th>ФИО</th>
                                            <th>№ зач. книжки</th>
                                            <th>Телефон</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                        <tbody class="group-students"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tabs__content">
                                <div id="preloader">
                                    <img src="{{ asset('storage/loading.gif') }}"
                                        alt="AJAX loader" title="AJAX loader"/>
                                </div>
                                <div class="row filters">
                                    <div class=" col-md-6 mb-2" id="control_forms">
                                        <h6>Форма контроля<span class="gcolor"></span></h6>
                                        <div class="form-s2 selectControlForm">
                                            <div>
                                                <select class="form-control formselect required"
                                                        id="statement_control_form">
                                                    <option value="reset_filter_control_form">Все</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" col-md-6 mb-2">
                                        <h6>Семестр</h6>
                                        <select class="form-control formselect required" id="statement-semester">
                                            <option value="">-- Не выбрано</option>
                                            @for($i=1; $i<=8; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <button type="button" id="statements-filter" class="btn btn-info mb-3">
                                    Показать
                                </button>
                                <h5>Экзаменационные ведомости:</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="statements-table">
                                        <thead>
                                        <tr>
                                            <th>№ ведомости</th>
                                            <th>Группа</th>
                                            <th>Дисциплина</th>
                                            <th>Форма контроля</th>
                                            <th>Семестр</th>
                                            <th>Учебный год</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                        <tbody class="group-statements"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tabs__content">
                                <div id="semester_statements_preloader">
                                    <img src="{{ asset('storage/loading.gif') }}"
                                         alt="AJAX loader" title="AJAX loader"/>
                                </div>
                                <div class="row filters">
                                    <div class=" col-md-6 mb-2">
                                        <h6>Семестр</h6>
                                        <select class="form-control formselect required" id="semester-statements-semester">
                                            @for($i=1; $i<=8; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <button type="button" id="semester-statements-filter" class="btn btn-info mb-3">
                                    Показать
                                </button>
                                <div class="form-group" id="semesterStatementsBody">
                                    <div></div>
                                </div>
                            </div>
                            <div class="tabs__content">
                                <div id="tasks_preloader">
                                    <img src="{{ asset('storage/loading.gif') }}"
                                         alt="AJAX loader" title="AJAX loader"/>
                                </div>
                                <div class="row filters">
                                    <div class=" col-md-6 mb-2">
                                        <h6>Дисциплины</h6>
                                        <div class="form-s2 selectDiscipline">
                                            <select class="form-control formselect required"
                                                    id="tasks_discipline">
                                                <option value="reset_filter_control_form">Все</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class=" col-md-6 mb-2">
                                        <h6>Год обучения</h6>
                                        <div class="form-s2 selectYear">
                                            <select class="form-control formselect required"
                                                    id="tasks_year">
                                                <option value="reset_filter_control_form">Весь период обучения</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="tasks-filter" class="btn btn-info mb-3">
                                    Показать
                                </button>
                                <div class="form-group" id="tasksBody">
                                    <div></div>
                                </div>
                            </div>
                        </div><!-- .tabs-->
                    </div>
            </div>
        </section>
    </div>
    <div class="modal fade bd-example-modal-xl" id="statementModal" tabindex="-1" role="dialog"
         aria-labelledby="statementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content modal-xl">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="mediumBody">
                    <div></div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/personal/mark/statements.js') }}"></script>
    <script src="{{ asset('js/personal/mark/semester_statements.js') }}"></script>
    <script src="{{ asset('js/personal/mark/about_group.js') }}"></script>
    <script src="{{ asset('js/personal/mark/tasks.js') }}"></script>
    <script>
    $(document).ready(function () {
        let choiceGroup = $("#group_name").val();
        let tabId;

        $('ul.tabs__caption').on('click', 'li:not(.active)', function() {
            $(this).closest('div.tabs').find('div.tabs__content').children('.row').hide();
            tabId = $(this).attr('data-id');
            $(this)
                .addClass('active').siblings().removeClass('active')
                .closest('div.tabs').find('div.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
            if (tabId === 'statements') {
                showStatementsTab(this);
            } else if (tabId === 'semester-statements') {
                showSemesterStatementsTab(this);
            } else if (tabId === 'about-group') {
                showAboutGroupTab(this);
            } else if (tabId === 'tasks') {
                showTasksTab(this);
            }
        });

        // отфильтровать таблицу с ведомостями
        $('#statements-filter').click(function () {
            $(this).attr('disabled', true);
            let filterControlForm = $('#statement_control_form').val();
            let filterSemester = $('#statement-semester').val();
            getStatements(choiceGroup, '{{ getenv('APP_URL') }}api/statements',  filterControlForm, filterSemester);
        })

        // отфильтровать таблицу с семестровками
        $('#semester-statements-filter').click(function () {
            $(this).attr('disabled', true);
            getSemesterStatementsTable(choiceGroup);
        })

        //отфильтровать таблицу с заданиями
        $('#tasks-filter').click(function () {
            $(this).attr('disabled', true);
            getTasksTable(choiceGroup);
        })

        // показать контент вкладки "Ведомости"
        function showStatementsTab(el) {
            getControlForms(el, '{{ getenv('APP_URL') }}api/control-forms');
            getStatements(choiceGroup, '{{ getenv('APP_URL') }}api/statements');
            $(el).closest('div.tabs').find('div.tabs__content').children('.row').show();
        }

        // показать контент вкладки "Семестровки"
        function showSemesterStatementsTab(el) {
            getSemesterStatementsTable(choiceGroup);
            $(el).closest('div.tabs').find('div.tabs__content').children('.row').show();
        }

        // показать контент вкладки "О группе"
        function showAboutGroupTab(el) {
            getStudentsTable(choiceGroup);
            $(el).closest('div.tabs').find('div.tabs__content').children('.row').show();
        }

        // показать контент вкладки "Задания"
        function showTasksTab(el) {
            $('#tasks_preloader').show();
            getDisciplines(el, choiceGroup);
            $(el).closest('div.tabs').find('div.tabs__content').children('.row').show();
        }

        // загрузить отчёт по ведомости
        $("#statements-table").on('click', '.showStatement', function() {
            getStatementReport('{{ route('personal.mark.getStatementInfo', ':id') }}', $(this).attr('id').split('_')[1])
        });

        // назначить новую старосту группы
        $("#students-table").on('click', '.setNewHeadman', function() {
            setNewHeadman(choiceGroup, $(this).attr('id').split('_')[1]);
        });

        // Загрузить контент соответствующей вкладки при смене учебной группы
        $('#group_name').on('change', function () {
            choiceGroup = $(this).val();
            if (tabId === 'statements') {
                getStatements(choiceGroup, '{{ getenv('APP_URL') }}api/statements');
            } else if (tabId === 'semester-statements') {
                getSemesterStatementsTable(choiceGroup);
            } else if (tabId === 'about-group') {
                getStudentsTable(choiceGroup);
            } else if (tabId === 'tasks') {
                $('#tasks_year').empty();
                $('.group-tasks').find('tr').remove();
                showTasksTab($('li:not(.active)'))
            }
        });
    });
    </script>
@endsection
