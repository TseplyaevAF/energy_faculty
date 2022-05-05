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

        {{--Модальное окно для добавления учебной нагрузки--}}
        <div class="modal fade" id="addLessonModal" tabindex="-1" role="dialog"
             aria-labelledby="addLessonModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title">Добавление учебной нагрузки</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="addLessonModalBody">
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
                    <div class="mb-3">
                        <a type="button"
                           class="btn btn-outline-info btn-sm" id="addLesson">
                            Добавить нагрузку
                        </a>
                    </div>
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
        let appUrl = '{{ getenv('APP_URL') }}';
        window.appUrl = appUrl;
        let teacherId = {{$teacher->id}};

        let tabId; // выбранная вкладка (задания либо уч. материалы)
        let homeworkId; // выбранная работа студента

        let callAjaxForm;
        let eduMaterialId;

        getGroups(choiceDiscipline);

        $('#addLesson').on('click', function () {
            $.ajax({
                method: 'GET',
                url: 'tasks/create-lesson',
                datatype: 'json',
                success: function (response) {
                    $('#addLessonModal').modal('show');
                    $('#addLessonModalBody').html(response);
                }
            });
        });

        $('.tabs').hide();

        $('ul.tabs__caption').on('click', 'li:not(.active)', function() {
            $(this).closest('div.tabs').find('div.tabs__content').children('.row').hide();
            tabId = $(this).attr('data-id');
            $(this)
                .addClass('active').siblings().removeClass('active')
                .closest('div.tabs').find('div.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
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
                    $('#createTaskModal').modal('hide');
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
            download(filePath, 'homework')
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
                        if (error === 'Forbidden') {
                            alert(jqXHR.responseText);
                        } else {
                            alert('Произошла ошибка');
                        }
                    },
                    timeout: 8000
                });
            })
            .on('click', '.createEdu', function () {
                let bar = $('.bar');
                let percent = $('.percent');
                callAjaxForm = $('#fileUploadForm').ajaxForm({
                    beforeSend: function () {
                        let percentVal = '0%';
                        bar.width(percentVal)
                        percent.html(percentVal);
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        let percentVal = percentComplete + '%';
                        bar.width(percentVal)
                        percent.html(percentVal);
                    },
                    success: function (response) {
                        $('#createEduModal').modal('hide');
                        getEduMaterials(choiceDiscipline, choiceGroup, choiceSemester)
                    },
                    complete: function (xhr) {
                        let percentVal = '0%';
                        bar.width(percentVal)
                        percent.html(percentVal);
                    },
                    error: function (response) {
                        alert(response.responseText);
                    }
                });
            })
            .on('click','.stopVideo', function(e){})
            .on('click', '.eduMaterialFile', function () {
                const filePath = $(this).text().split('/');
                if (filePath[3].includes('mp4')) {
                    let videoId = $(this).attr('id').split('_')[1];
                    // если видео уже было открыто ранее, то продолжать его просмотр
                    if (videoId === eduMaterialId) {
                        $('#loadEduMaterialModal').modal("show");
                    } else {
                        // иначе загрузить новое видео
                        eduMaterialId = $(this).attr('id').split('_')[1];
                        $.ajax({
                            type: 'GET',
                            url:  'tasks/load-edu/' + eduMaterialId,
                            success: function(response) {
                                $('#loadEduMaterialModal').modal("show");
                                $('#loadEduMaterialModalBody').html(response).show();
                            },
                            error: function(jqXHR, status, error) {
                                alert('Невозможно загрузить видео');
                                console.log(jqXHR.responseText);
                            },
                            timeout: 8000
                        });
                    }

                } else {
                    download(filePath, 'tasks')
                }
            })
            .on('click', '.eduDelete', function () {
                let id = $(this).attr('id').split('_')[1];
                if (confirm('Вы уверены, что хотите удалить файл?')) {
                    $.ajax({
                        url: 'tasks/' + id,
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: { '_token': $("input[name='_token']").val() },
                        complete: function() {
                            getEduMaterials(choiceDiscipline, choiceGroup, choiceSemester);
                        },
                    });
                }
            })

        function download(filePath, category) {
            $.ajax({
                type: 'GET',
                xhrFields: {
                    responseType: 'blob',
                },
                url: category + '/' +filePath[0]+'/'+filePath[2]+'/'+filePath[3],
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
                    alert('Невозможно получить информацию о файле');
                    console.log(jqXHR.responseText);
                },
                timeout: 8000
            });
        }

        function getGroups(choiceDiscipline) {
            $('#semester_name').empty();
            $('#group_name').empty();
            $('#group_name').append(`<option value="0" disabled selected>Поиск...</option>`);
            $.ajax({
                type: 'GET',
                url: appUrl + 'api/lessons/get-groups?discipline_id=' + choiceDiscipline,
                data: {'teacher_id': teacherId},
                success: function (response) {
                    $('#group_name').empty();
                    $('#group_name').append(`<option value="" disabled selected>-- Не выбрана</option>`);
                    response.forEach(element => {
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
                url: appUrl + 'api/lessons/get-semesters',
                data: { 'group_id': choiceGroup, 'discipline_id': choiceDiscipline, 'teacher_id': teacherId },
                success: function (response) {
                    $('#semester_name').empty();
                    response.forEach(element => {
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
                return -1;
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
            let res = getTasksTable(choiceDiscipline, choiceGroup, choiceSemester);
            if (res !== -1) {
                getEduMaterials(choiceDiscipline, choiceGroup, choiceSemester);
            }
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
