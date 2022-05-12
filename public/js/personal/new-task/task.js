//# sourceURL=js/personal/new-task/task.js

$('#storeTask').on('click', function () {
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
        url: 'tasks',
        datatype: 'json',
        success: function (response) {
            $('#storeTaskModal').modal('hide');
            getTasksTable()
        },
        error: function (response) {
            alert(response.responseJSON.errors['task']);
        }
    });
});

$('.homeworkLoad').on('click', function () {
    homeworkId = $(this).attr('id').split('_')[1];
    $.ajax({
        type: 'GET',
        url: 'tasks/load-homework/' + homeworkId,
        success: function (response) {
            $('#loadHomeworkModal').modal("show");
            $('#loadHomeworkModalBody').html(response).show();
        },
        error: function (jqXHR, status, error) {
            alert('Невозможно получить информацию о задании');
        }
    });
});

$('.taskFile').on('click', function () {
    const filePath = $(this).children('input[name="task_path"]').val();
    download(filePath.split('/'), 'tasks')
});

$('.taskDelete').on('click', function () {
    let id = $(this).attr('id').split('_')[1];
    if (confirm('Вы уверены, что хотите удалить файл?')) {
        $.ajax({
            url: 'tasks/' + id,
            type: 'DELETE',
            dataType: 'JSON',
            data: {'_token': $("input[name='_token']").val()},
            complete: function () {
                getTasksTable();
            },
            error: function (response) {
                alert(response.responseText);
            }
        });
    }
})
