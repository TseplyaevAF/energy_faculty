//# sourceURL=js/personal/new-task/studentProgress.js

$('.importStudentsProgress').on('click', function () {
    const file = $('#student_progress_file')[0].files[0];
    if (file === undefined) {
        alert('Необходимо выбрать файл с заданием');
        return;
    }
    let formData = new FormData()
    formData.append('_token', $("input[name='_token']").val());
    formData.append('student_progress', file);
    formData.append('monthNumber', $('#month_title').find(":selected").val());
    formData.append('lesson_id', $("input[name='lesson_id']").val())
    $.ajax({
        method: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        url: 'student-progress',
        datatype: 'json',
        success: function () {
            $('#importStudentsProgressModal').modal('hide');
            getStudentsProgress();
        },
        error: function (response) {
            if (response.responseJSON !== undefined) {
                alert(response.responseJSON.errors['student_progress']);
            } else {
                alert(response.responseText);
            }
        }
    });
});
