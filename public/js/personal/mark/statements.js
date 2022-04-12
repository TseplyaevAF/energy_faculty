function createSelect(data, id) {
    let select = '<select class="form-control" type="text" id=' + id + '>';
    select += '<option value="">-- Не выбрано</option>';
    let count = 1;
    for (let i = 0; i < Object.keys(data).length; i++) {
        select += '<option value="' + count + '">' + data[count] + '</option>';
        count++;
    }
    return select += '</select>';
}


function getControlForms(el, url) {
    $.ajax({
        type: 'GET',
        url: url,
        success: function (response) {
            let select = createSelect(response, 'statement_control_form');
            $(el).closest('div.tabs').find('.selectControlForm').replaceWith(select);
        }
    });
}

function getStatements(choiceGroup, url, filterControlForm = '', filterSemester = '') {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            'semester': filterSemester,
            'group': choiceGroup,
            'control_form': filterControlForm
        },
        beforeSend: function() {
            $('#preloader').show();
        },
        success: function (response) {
            $('.group-statements').find('tr').remove();
            $.each(response, function (key, item) {
                $('.group-statements').append('<tr>\
                            <td>' + item.id + '</td>\
                            <td>' + item.lesson.group + '</td>\
                            <td>' + item.lesson.discipline + '</td>\
                            <td>' + item.control_form + '</td>\
                            <td>' + item.lesson.semester + '</td>\
                            <td>' + item.lesson.year + '</td>\
                            <td><a type="button" a-toggle="modal" id="statement_' + item.id +'"\
                                data-attr="" data-target="#smallModal" class="showStatement">\
                                <i class="fas fa-eye text-success fa-lg"></i>\
                            </a></td>\
                            </tr>');
            })
            $('#preloader').hide();
            $('#statements-filter').attr('disabled', false);
        }
    });
}

function getStatementReport(url, id) {
    $.ajax({
        url:  url.replace(':id', id),
        beforeSend: function() {
            $('#preloader').show();
        },
        success: function(result) {
            if (result === undefined) {
                alert('Отчет по данной ведомости еще не готов');
                return;
            }
            $('#statementModal').modal("show");
            $('#mediumBody').html(result).show();
        },
        complete: function() {
            $('#preloader').hide();
        },
        error: function(jqXHR, status, error) {
            alert('Невозможно получить отчёт');
            console.log(jqXHR.responseText);
            $('#preloader').hide();
        },
        timeout: 8000
    });
}
