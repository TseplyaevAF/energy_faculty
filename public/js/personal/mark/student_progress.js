//# sourceURL=js/personal/mark/student_progress.js

function getMontnTypes(semester = null) {
    if (semester === null) {
        $.ajax({
            type: 'GET',
            url: 'student-progress/get-month-types',
            beforeSend: function () {
                $('#student_progress_preloader').show();
            },
            success: function (response) {
                monthTypes = JSON.parse(response);
                $('#student_progress_preloader').hide();
                $('#student-progress-semester').trigger("change");
            }
        });
    } else {
        let $studentProgressMonth = $('#student-progress-month');
        $studentProgressMonth.empty();
        for (const [key, value] of Object.entries(monthTypes[semester])) {
            $studentProgressMonth.append(`<option value="${key}">${value}</option>`);
        }
    }
}

function getStudentProgressTable(choiceGroup) {
    let semester = $('#student-progress-semester').val();
    let month = $('#student-progress-month').val();
    $.ajax({
        type: 'GET',
        url:  `marks/get-student-progress/${month}`,
        data: {
            'semester': semester,
            'group_id': choiceGroup
        },
        beforeSend: function() {
            $('#student_progress_preloader').show();
            $('#studentProgressBody').html('').show();
        },
        success: function(result) {
            $('#studentProgressBody').html(result).show();
            studentProgress = JSON.parse($("input[name='student_progress']").val());
            studentsIds = JSON.parse($("input[name='student_ids']").val());
        },
        complete: function() {
            $('#student_progress_preloader').hide();
            $('#student-progress-filter').attr('disabled', false);
        },
        error: function(jqXHR, status, error) {
            if (jqXHR.status === 500) {
                alert('При загрузке произошла ошибка');
                console.log(error);
            }
        }
    });
}
