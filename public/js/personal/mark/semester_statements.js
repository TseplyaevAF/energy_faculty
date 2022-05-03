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

function downloadSemesterStatements(choiceGroup) {
    let choiceSemester = $('#semester-statements-semester').val();
    $.ajax({
        xhrFields: {
            responseType: 'blob',
        },
        type: 'GET',
        url: 'marks/download-semesters-statements/'+ choiceGroup +'/' + choiceSemester,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        success: function(data) {
            const blob = new Blob([data], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = `Ведомость сдачи зачетов и экзаменов ${choiceSemester} семестра группы ${choiceGroup}.xlsx`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    });
}
