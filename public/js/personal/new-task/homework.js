$('.checkHomework').on('click', function () {
    const grade = $('#grade').val();
    if (grade === '') {
        alert('Пожалуйста оставьте комментарий');
        return;
    }
    $.ajax({
        type: 'PATCH',
        url: 'homework/' + homeworkId + '/feedback',
        data: {
            '_token': $("input[name='_token']").val(),
            'grade': grade,
            'task_id': $("input[name='task_id']").val()
        },
        success: function (response) {
            $('#loadHomeworkModal').modal('hide');
            getTasksTable();
        },
        error: function (jqXHR, status, error) {
            if (error === 'Forbidden') {
                alert(jqXHR.responseText);
            } else {
                alert('Произошла ошибка');
            }
        }
    });
});

$('.homeworkFile').on('click', function () {
    download($(this).text().split('/'), 'homework')
});
