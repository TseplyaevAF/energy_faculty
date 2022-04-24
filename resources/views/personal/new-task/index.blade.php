@extends('personal.layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/marks/style.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Задания для учебных групп</h1>
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
                <div class="form-group w-50">
                    <div class="row filters">
                        <div class="col-md-6 mb-2">
                            <h6>Дисциплина<span class="gcolor"></span></h6>
                            <div class="form-s2 selectDiscipline">
                                <select class="form-control formselect" id="discipline_name">
                                    @foreach($disciplines as $discipline)
                                        <option value="{{ $discipline->id }}">
                                            {{ $discipline->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=" col-md-6 mb-2">
                            <h6>Группа</h6>
                            <div class="form-s2 selectGroup">
                                <select class="form-control formselect required" id="group_name">
                                </select>
                            </div>
                        </div>
                        <div class=" col-md-6 mb-2">
                            <h6>Семестр</h6>
                            <div class="form-s2 selectSemester">
                                <select class="form-control formselect required" id="semester_name">
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="lesson-filter" class="btn btn-info mb-3">
                        Показать
                    </button>
                </div>

                <div class="row">
                        <div class="tabs">
                            <ul class="tabs__caption">
                                <li data-id="tasks" class="active">Задания</li>
                                <li data-id="edu-materials">Учебные материалы</li>
                            </ul>
                            <div class="tabs__content active">
                                <div class="form-group" id="tasksBody"></div>
                            </div>
                            <div class="tabs__content">
                                <div class="form-group" id="eduMaterialsBody"></div>
                            </div>
                        </div><!-- .tabs-->
                    </div>
            </div>
        </section>
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script>
    $(document).ready(function () {
        // переменные для фильтров
        let choiceDiscipline = $("#discipline_name").val();
        let choiceGroup, choiceSemester;
        let tasks = null, eduMaterials = null;

        let tabId; // выбранная вкладка (задания либо уч. материалы)
        let homeworkId; // выбранная работа студента

        getGroups(choiceDiscipline);

        $('.tabs').hide();

        $('ul.tabs__caption').on('click', 'li:not(.active)', function() {
            $(this).closest('div.tabs').find('div.tabs__content').children('.row').hide();
            tabId = $(this).attr('data-id');
            $(this)
                .addClass('active').siblings().removeClass('active')
                .closest('div.tabs').find('div.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
            if (tabId === 'tasks') {
                if (!isset(tasks)) {
                    getTasksTable(choiceDiscipline, choiceGroup, choiceSemester)
                }
            } else if (tabId === 'edu-materials') {
                if (!isset(eduMaterials)) {
                    getEduMaterials(choiceDiscipline, choiceGroup, choiceSemester);
                }
            }
        });

        $('.content')
            .on('click', '.createTask', function () {
            const file = $('#file')[0].files[0];
            if (file === undefined) {
                alert('Необходимо выбрать файл с заданием');
                return;
            }
            let formData = new FormData()
            formData.append('_token', $("input[name='_token']").val());
            formData.append('task', file);
            formData.append('lesson_id', $("input[name='lesson_id']").val())
            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                url: "{{ route('personal.task.store') }}",
                datatype: 'json',
                success: function (response) {
                    alert(response);
                    $('#createTask').modal('hide');
                    getTasksTable(choiceDiscipline, choiceGroup, choiceSemester)
                },
                error: function (response) {
                    alert(response.responseText);
                }
            });
        })
            .on('click', '.homeworkLoad', function () {
            homeworkId = $(this).attr('id').split('_')[1];
            $.ajax({
                type: 'GET',
                url:  'tasks/load-homework/' + homeworkId,
                success: function(response) {
                    $('#loadHomeworkModal').modal("show");
                    $('#loadHomeworkModalBody').html(response).show();
                },
                error: function(jqXHR, status, error) {
                    alert('Невозможно получить информацию о задании');
                    console.log(jqXHR.responseText);
                },
                timeout: 8000
            });
        })
            .on('click', '.workFile', function () {
            const filePath = $(this).text().split('/');
            $.ajax({
                type: 'GET',
                xhrFields: {
                    responseType: 'blob',
                },
                url:  'homework/'+filePath[0]+'/'+filePath[2]+'/'+filePath[3],
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                success: function(response) {
                    const blob = new Blob([response], {type: 'application/pdf'});
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filePath[3];
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function(jqXHR, status, error) {
                    alert('Невозможно получить информацию о задании');
                    console.log(jqXHR.responseText);
                },
                timeout: 8000
            });
        })
            .on('click', '.checkHomework', function () {
                const grade = $('#grade').val();
                if (grade === '') {
                    alert('Пожалуйста оставьте комментарий');
                    return;
                }
                $.ajax({
                    type: 'PATCH',
                    url:  'homework/' + homeworkId + '/feedback',
                    data: {
                        '_token': $("input[name='_token']").val(),
                        'grade': grade,
                        'task_id': $("input[name='task_id']").val()
                    },
                    success: function(response) {
                        $('#loadHomeworkModal').modal('hide');
                        getTasksTable(choiceDiscipline, choiceGroup, choiceSemester)
                    },
                    error: function(jqXHR, status, error) {
                        alert('Произошла ошибка');
                        console.log(jqXHR.responseText);
                    },
                    timeout: 8000
                });
            });

        function getGroups(choiceDiscipline) {
            $('#semester_name').empty();
            $('#group_name').empty();
            $('#group_name').append(`<option value="0" disabled selected>Поиск...</option>`);
            $.ajax({
                type: 'GET',
                url: 'tasks/get-groups/' + choiceDiscipline,
                success: function (response) {
                    $('#group_name').empty();
                    $('#group_name').append(`<option value="" disabled selected>-- Не выбрана</option>`);
                    JSON.parse(response).forEach(element => {
                        $('#group_name').append(`<option value="${element['id']}">${element['title']}</option>`);
                    });
                    $('#group_name').on('change', changeSelect);
                }
            });
        }

        function changeSelect() {
            choiceGroup = $(this).find(":selected").val();
            $('#semester_name').empty();
            $('#semester_name').append(`<option value="0" disabled selected>Поиск...</option>`);
            $.ajax({
                type: 'GET',
                url: 'tasks/get/semesters/' + choiceDiscipline + '/' + choiceGroup,
                success: function (response) {
                    $('#semester_name').empty();
                    JSON.parse(response).forEach(element => {
                        $('#semester_name').append(`<option value="${element}">${element}</option>`);
                    });
                    choiceSemester = $('#semester_name').find(":selected").val();
                }
            });
        }

        function isset(obj) {
            return !(typeof obj === 'undefined' || obj === null);
        }

        function getTasksTable(choiceDiscipline, choiceGroup, choiceSemester) {
            if (!isset(choiceDiscipline) || !isset(choiceGroup) || !isset(choiceSemester)) {
                alert('Нагрузка не выбрана! (дисциплина, группа, семестр)');
                return;
            }
            $('#lesson-filter').attr('disabled', true);
            let url = 'tasks/get-tasks';
            $.ajax({
                type: 'GET',
                url:  url,
                data: {
                    'discipline_id': choiceDiscipline,
                    'group_id': choiceGroup,
                    'semester': choiceSemester,
                },
                success: function(result) {
                    tasks = result;
                    $('#tasksBody').html(result).show();
                },
                complete: function() {
                    $('#lesson-filter').attr('disabled', false);
                    $('.tabs').show();
                },
                error: function(jqXHR, status, error) {
                    if (jqXHR.status === 500) {
                        alert('При загрузке произошла ошибка');
                        console.log(error);
                    }
                },
                timeout: 8000
            });
        }

        function getEduMaterials(choiceDiscipline, choiceGroup, choiceSemester) {
            if (!isset(choiceDiscipline) || !isset(choiceGroup) || !isset(choiceSemester)) {
                alert('Нагрузка не выбрана! (дисциплина, группа, семестр)');
                return;
            }
            $('#lesson-filter').attr('disabled', true);
            let url = 'tasks/get-edu-materials';
            $.ajax({
                type: 'GET',
                url:  url,
                data: {
                    'discipline_id': choiceDiscipline,
                    'group_id': choiceGroup,
                    'semester': choiceSemester,
                },
                success: function(result) {
                    eduMaterials = result
                    $('#eduMaterialsBody').html(result).show();
                },
                complete: function() {
                    $('#lesson-filter').attr('disabled', false);
                    $('.tabs').show();
                },
                error: function(jqXHR, status, error) {
                    if (jqXHR.status === 500) {
                        alert('При загрузке произошла ошибка');
                        console.log(error);
                    }
                },
                timeout: 8000
            });
        }

        // вывести контент для выбранной нагрузки
        $('#lesson-filter').click(function () {
            getTasksTable(choiceDiscipline, choiceGroup, choiceSemester);
            getEduMaterials(choiceDiscipline, choiceGroup, choiceSemester);
        })

        // загрузить список учебных групп, у которых преподается выбранная дисциплина
        $('#discipline_name').on('change', function () {
            choiceDiscipline = $(this).val();
            choiceSemester = null;
            getGroups(choiceDiscipline)
        });

        $('#semester_name').on('change', function () {
            choiceSemester = $(this).val();
        })
    });
    </script>
@endsection
