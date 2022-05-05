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
                let mainColumns = '<td>' + number++ + '</td>\
                                   <td>' + item.FIO + '</td>\
                                   <td>' + item.student_id_number + '</td>\
                                   <td>' + item.phone + '</td>\
                                   <td><button ' + 'type="button" class="btn btn-outline-info btn-sm showParents" ' +
                                        'id="parents_' + item.id +'">Посмотреть</button>\
                                   </td>';
                const groupStudents = $('.group-students');
                if (item.headman) {
                    $('.group-headman-info').empty().append(item.FIO + ', ' + item.phone);
                    groupStudents.append('<tr>' + mainColumns + '<td><i>Староста</i></td></tr>');
                } else {
                    groupStudents.append(
                        '<tr>' + mainColumns + '<td><button ' +
                        'type="button" class="btn btn-outline-secondary btn-sm setNewHeadman" ' +
                        'id="student_' + item.id +'">Назначить старостой</button></td></tr>'
                    );
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
                getStudentsTable(choiceGroup);
            },
            error: function (errorMessage) {
                alert('Произошла ошибка!');
                console.log(errorMessage)
            }
        });
    }
}

function showParentsContacts(choiceStudent) {
    $.ajax({
        type: 'GET',
        url:  'marks/get-parents-contacts/' + choiceStudent,
        success: function(response) {
            $('#showParentsContactsModalBody').html(response).show();
            $('#showParentsContactsModal').modal('show');
        }
    });
}

function updateParentsContacts() {
    $('.parentsContactsErrors').html('');
    let choiceStudent = $("input[name='student_id']").val();
    let motherContact = {
        'FIO': $("input[name='parents[mother][FIO]']").val(),
        'phone': $("input[name='parents[mother][phone]']").val(),
        'email': $("input[name='parents[mother][email]']").val(),
    }
    let fatherContact = {
        'FIO': $("input[name='parents[father][FIO]']").val(),
        'phone': $("input[name='parents[father][phone]']").val(),
        'email': $("input[name='parents[father][email]']").val(),
    }
    $.ajax({
        type: 'PATCH',
        url: 'marks/update-parents-contacts/' + choiceStudent,
        data:{
            "_token": $("input[name='_token']").val(),
            'parents': {
                'mother': motherContact,
                'father': fatherContact,
            }
        },
        success:function(response){
            alert(response);
            $('.parentsContactsErrors').html('');
        },
        error: function (response) {
            let errors = response.responseJSON.errors;
            $('#phoneMotherError').text(errors['parents.mother.phone']);
            $('#phoneFatherError').text(errors['parents.father.phone']);
            $('#emailMotherError').text(errors['parents.mother.email']);
            $('#emailFatherError').text(errors['parents.father.email']);
        }
    });
}
