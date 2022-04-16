function getStudentsTable(choiceGroup) {
    $('.group-headman-info').empty();
    let url = 'marks/group-students/'+ choiceGroup;
    $.ajax({
        url:  url,
        beforeSend: function() {
            $('#about_group_preloader').show();
        },
        success: function(response) {
            $('.group-students').find('tr').remove();

            let number = 1;
            $.each(JSON.parse(response), function (key, item) {
                const groupStudents = $('.group-students');
                if (item.headman) {
                    $('.group-headman-info').empty().append(item.FIO + ', ' + item.phone);
                    groupStudents.append('<tr>\
                            <td>' + number++ + '</td>\
                            <td>' + item.FIO + '</td>\
                            <td>' + item.student_id_number + '</td>\
                            <td>' + item.phone + '</td>\
                            <td><i>Староста</i></td>\
                            </tr>');
                } else {
                    groupStudents.append('<tr>\
                            <td>' + number++ + '</td>\
                            <td>' + item.FIO + '</td>\
                            <td>' + item.student_id_number + '</td>\
                            <td>' + item.phone + '</td>\
                            <td><button type="button"class="btn btn-primary setNewHeadman" id="student_' + item.id +'"\
                                >Назначить старостой\
                            </button></td>\
                            </tr>');
                }
            })
            $('#about_group_preloader').hide();
        },
        error: function(jqXHR, status, error) {
            alert('Невозможно получить информацию о группе');
            console.log(jqXHR.responseText);
        },
        timeout: 8000
    });
}

function setNewHeadman(choiceGroup, headmanId) {
    if(confirm('Назначить старостой?')){
        $.ajax({
            type: 'GET',
            url:  'marks/set-new-headman/' + choiceGroup + '/' + headmanId,
            success: function(response) {
                alert(response);
                getStudentsTable(choiceGroup);
            },
            error: function (errorMessage) {
                alert('Произошла ошибка!');
                console.log(errorMessage)
            }
        });
    }
}
