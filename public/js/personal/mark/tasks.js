function getSemesters(el, choiceGroup) {
    $.ajax({
        type: 'GET',
        url: 'marks/get-semesters/' + choiceGroup,
        success: function (response) {
            let select = createTaskSelect(JSON.parse(response).sort(), 'tasks_semester');
            $(el).closest('div.tabs').find('.selectSemester').replaceWith(select);
            $('#tasks_semester').on('change', changeSelect);
            $('#tasks_preloader').hide();
        }
    });
}

function createTaskSelect(data, id) {
    let select = '<div class="form-s2 selectSemester">';
    select += '<select class="form-control" type="text" id=' + id + '>';
    select += '<option value="">-- Не выбрано</option>';
    for (let i = 0; i < Object.keys(data).length; i++) {

        select += '<option value="' + data[i] +'">' + data[i] + '</option>';
    }
    select += '</select>'
    return select += '</div>';
}

function changeSelect() {
    let choiceSemester = $('#tasks_semester').val();
    let tasksDisciplineSelect = $('#tasks_discipline');
    if (choiceSemester === '') {
        tasksDisciplineSelect.empty();
        return;
    }
    tasksDisciplineSelect.empty();
    tasksDisciplineSelect.append(`<option value="0" disabled selected>Поиск...</option>`);
    $.ajax({
        type: 'GET',
        url: 'marks/get-disciplines/' + window.choiceGroup + '/' + choiceSemester,
        success: function (response) {
            tasksDisciplineSelect.empty();
            JSON.parse(response).forEach(element => {
                tasksDisciplineSelect.append(`<option value="${element['id']}">${element['title']}</option>`);
            });
        },
        complete: function () {
            $('#tasks_preloader').hide();
        }
    });
}

function getTasksTable(choiceGroup) {
    const semester = $('#tasks_semester').val();
    if (semester === '') {
        alert('Фильтры не заданы');
        $('#tasks-filter').attr('disabled', false);
        return;
    }
    let url = 'marks/get-tasks/';
    const discipline = $('#tasks_discipline option:selected').val();
    $.ajax({
        type: 'GET',
        url:  url,
        data: {
            'semester': semester,
            'group_id': choiceGroup,
            'discipline_id': discipline,
        },
        beforeSend: function() {
            $('#tasks_preloader').show();
            $('#tasksBody').html('').show();
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
        // timeout: 8000
    });
}
