function getSemesterStatementsTable(choiceGroup) {
    let url = 'marks/semesters-report/'+ choiceGroup +'/' + $('#semester-statements-semester').val();
    $.ajax({
        url:  url,
        beforeSend: function() {
            $('#semester_statements_preloader').show();
        },
        success: function(result) {
            if (result === undefined) {
                alert('Отчет по данной ведомости еще не готов');
                return;
            }
            $('#semesterStatementsBody').html(result).show();
        },
        complete: function() {
            $('#semester_statements_preloader').hide();
            $('#semester-statements-filter').attr('disabled', false);
        },
        error: function(jqXHR, status, error) {
            alert('Невозможно получить отчёт');
            console.log(jqXHR.responseText);
        },
        timeout: 8000
    });
}
