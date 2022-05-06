@extends('personal.layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/marks/style.css') }}">
    <input type="hidden" name="group_id" value="{{ $group->id }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Домашние задания</h1>
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
                        <div class=" col-md-6 mb-2">
                            <h6>Семестр</h6>
                            <div class="form-s2 selectSemester">
                                <select class="form-control formselect required" id="semester_name">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <h6>Дисциплина<span class="gcolor"></span></h6>
                            <div class="form-s2 selectDiscipline">
                                <select class="form-control formselect" id="discipline_name">
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
        let studentGroup = $("input[name='group_id']").val()

        // переменные для фильтров
        let choiceDiscipline = $("#discipline_name").val();
        let choiceSemester;
        let appUrl = '{{ getenv('APP_URL') }}';

        let tabId; // выбранная вкладка (задания либо уч. материалы)
        let taskId; // выбранное задание
        let homeworkId; // выбранная работа студента

        let callAjaxForm;
        let eduMaterialId;

        $('.tabs').hide();
        getSemesters(studentGroup)

        function getSemesters(studentGroup) {
            let $semesterName = $('#semester_name')
            $semesterName.append(`<option value="0" disabled selected>Поиск...</option>`);
            $.ajax({
                type: 'GET',
                url: appUrl + 'api/lessons/get-semesters?group_id=' + studentGroup,
                success: function (response) {
                    $semesterName.empty();
                    response.forEach(element => {
                        $semesterName.append(`<option value="${element}">${element}</option>`);
                    });
                    $semesterName.on('change', changeSelect);
                    $("#semester_name").change();
                }
            });
        }

        function changeSelect() {
            choiceSemester = $(this).find(":selected").val();
            let $disciplineName = $('#discipline_name');
            $disciplineName.empty();
            $disciplineName.append(`<option value="0" disabled selected>Поиск...</option>`);
            $.ajax({
                type: 'GET',
                url: appUrl + 'api/lessons/get-disciplines',
                data: { 'semester': choiceSemester, 'group_id': studentGroup },
                success: function (response) {
                    $disciplineName.empty();
                    $disciplineName.append(`<option value="">-- Не выбрана</option>`);
                    response.forEach(element => {
                        $disciplineName.append(`<option value="${element['id']}">${element['title']}</option>`);
                    });
                }
            });
        }

        $('ul.tabs__caption').on('click', 'li:not(.active)', function() {
            $(this).closest('div.tabs').find('div.tabs__content').children('.row').hide();
            tabId = $(this).attr('data-id');
            $(this)
                .addClass('active').siblings().removeClass('active')
                .closest('div.tabs').find('div.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
        });

        $('.content')
            .on('click', '.homeworkStore', function () {
                const file = $('#file')[0].files[0];
                if (file === undefined) {
                    alert('Необходимо выбрать файл с заданием');
                    return;
                }
                let formData = new FormData()
                formData.append('_token', $("input[name='_token']").val());
                formData.append('homework', file);
                formData.append('task_id', taskId)
                $.ajax({
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    url: "{{ route('personal.homework.store') }}",
                    datatype: 'json',
                    success: function (response) {
                        $('#homeworkCreateModal').modal('hide');
                        getTasksTable(studentGroup, choiceDiscipline, choiceSemester)
                    },
                    error: function (response) {
                        alert(response.responseJSON.errors['homework']);
                    }
                });
        })
            .on('click', '.homeworkCreate', function () {
                taskId = $(this).attr('id').split('_')[1];
                $('#homeworkCreateModal').modal("show");
        })
            .on('click', '.homeworkLoad', function () {
                homeworkId = $(this).attr('id').split('_')[1];
                $.ajax({
                    type: 'GET',
                    url:  'homework/load-homework/' + homeworkId,
                    success: function(response) {
                        $('#homeworkLoadModal').modal("show");
                        $('#homeworkLoadModalBody').html(response).show();
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
            .on('click', '.taskFile', function () {
                const filePath = $(this).children('input[name="task_path"]').val();
                download(filePath.split('/'), 'tasks')
            })
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
            .on('click', '.homeworkDelete', function () {
                let id = $(this).attr('id').split('_')[1];
                if (confirm('Вы уверены, что хотите удалить файл?')) {
                    $.ajax({
                        url: 'homework/' + id,
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: { '_token': $("input[name='_token']").val() },
                        complete: function() {
                            getTasksTable(studentGroup, choiceDiscipline, choiceSemester);
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
                url: category + '/' +filePath[0]+'/'+filePath[2]+'/filename',
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

        function isset(obj) {
            return !(typeof obj === 'undefined' || obj === null  || obj === '');
        }

        function getTasksTable(studentGroup, choiceDiscipline, choiceSemester) {
            if (!isset(choiceDiscipline) || !isset(studentGroup) || !isset(choiceSemester)) {
                alert('Не все параметры заданы! (семестр, дисциплина)');
                return -1;
            }
            $('#lesson-filter').attr('disabled', true);
            let url = 'homework/get-tasks';
            $.ajax({
                type: 'GET',
                url:  url,
                data: {
                    'discipline_id': choiceDiscipline,
                    'group_id': studentGroup,
                    'semester': choiceSemester,
                },
                success: function(result) {
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

        function getEduMaterials(choiceGroup, choiceDiscipline, choiceSemester) {
            $('#lesson-filter').attr('disabled', true);
            let url = 'homework/get-edu-materials';
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
            let res = getTasksTable(studentGroup, choiceDiscipline, choiceSemester);
            if (res !== -1) {
                getEduMaterials(studentGroup, choiceDiscipline, choiceSemester);
            }
        })

        $('#discipline_name').on('change', function () {
            choiceDiscipline = $(this).val();
        });

        $('#semester_name').on('change', function () {
            choiceSemester = $(this).val();
        })
    });
    </script>
@endsection
