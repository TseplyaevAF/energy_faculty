function getSemesterStatementsTable(choiceGroup) {
    let url = 'marks/semesters-report/'+ choiceGroup +'/' + $('#semester-statements-semester').val();
    $.ajax({
        type: 'GET',
        url:  url,
        beforeSend: function() {
            $('#semester_statements_preloader').show();
        },
        success: function(result) {
            $('#semesterStatementsBody').html(result).show();
        },
        complete: function() {
            $('#semester_statements_preloader').hide();
            $('#semester-statements-filter').attr('disabled', false);
        }
    });
}

function downloadSemesterStatements(choiceGroup, choiceStudent = null) {
    let choiceSemester = $('#semester-statements-semester').val();
    $.ajax({
        type: 'GET',
        url: `marks/download-semester-statements/${choiceGroup}/${choiceSemester}`,
        data: {
            'student_id': choiceStudent
        },
        success: function(response) {
            downloadFile(response);
        }
    });
}

function downloadFile(response) {
    var a = document.createElement("a");
    a.href = response.file;
    a.download = response.file_name;
    document.body.appendChild(a);
    a.click();
    a.remove();
}
