function getDisciplines(el, choiceGroup) {
    $.ajax({
        type: 'GET',
        url: 'marks/get-disciplines/' + choiceGroup,
        success: function (response) {
            let select = createTaskSelect(JSON.parse(response), 'tasks_discipline', choiceGroup);
            $(el).closest('div.tabs').find('.selectDiscipline').replaceWith(select);
            $('#tasks_discipline').on('change', changeSelect);
            $('#tasks_preloader').hide();
        }
    });
}

function createTaskSelect(data, id, choiceGroup) {
    let select = '<div class="form-s2 selectDiscipline">';
    select += '<select class="form-control" type="text" id=' + id + '>';
    select += '<option value="">-- Не выбрано</option>';
    for (let i = 0; i < Object.keys(data).length; i++) {

        select += '<option value="' + choiceGroup + '_' + data[i].id +'">' + data[i].title + '</option>';
    }
    select += '</select>'
    return select += '</div>';
}

function changeSelect() {
    let taskInfo = $(this).find(":selected").val();
    if (taskInfo === '') {
        $('#tasks_year').empty();
        return;
    }
    taskInfo = taskInfo.split('_');
    $('#tasks_year').empty();
    $('#tasks_year').append(`<option value="0" disabled selected>Поиск...</option>`);
    $.ajax({
        type: 'GET',
        url: 'marks/get-years/' + taskInfo[0] + '/' + taskInfo[1],
        success: function (response) {
            $('#tasks_year').empty();
            $('#tasks_year').append(`<option value="">Весь период обучения</option>`);
            JSON.parse(response).forEach(element => {
                $('#tasks_year').append(`<option value="${element['id']}">${element['start_year']}-${element['end_year']}</option>`);
            });
        }
    });
}

function getTasksTable(choiceGroup) {
    const discipline = $('#tasks_discipline').val().split('_')[1];
    if (discipline === undefined) {
        alert('Обязательно выберите дисциплину');
        $('#tasks-filter').attr('disabled', false);
        return;
    }
    let url = 'marks/get-tasks/'+ choiceGroup +'/' + discipline;
    const year = $('#tasks_year').val() !== '' ? $('#tasks_year').find(":selected").val() : '';
    $.ajax({
        type: 'GET',
        url:  url,
        data: {filter_year: year},
        beforeSend: function() {
            $('#tasks_preloader').show();
        },
        success: function(result) {
            $('#tasksBody').html(result).show();
        },
        complete: function() {
            $('#tasks_preloader').hide();
            $('#tasks-filter').attr('disabled', false);
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
