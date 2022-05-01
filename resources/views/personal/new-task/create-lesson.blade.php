<input type="hidden" name="teacher_id" value="{{ auth()->user()->teacher->id }}">
<div class="mb-2">
    <h6>Дисциплина<span class="gcolor"></span></h6>
    <div class="form-s2 selectDiscipline">
        <select class="form-control formselect" id="add_discipline_name">
            @foreach($disciplines as $discipline)
                <option value="{{ $discipline->id }}">
                    {{ $discipline->title }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-2">
    <h6>Группа</h6>
    <div class="form-s2 selectGroup">
        <select class="form-control formselect required" id="add_group_name">
        </select>
    </div>
</div>
<div class="mb-2">
    <h6>Семестр</h6>
    <div class="form-s2 selectSemester">
        <select class="form-control formselect required" id="add_semester_name">
        </select>
    </div>
</div>

<button type="button" id="add-lesson" class="btn btn-info mb-3">
    Сохранить
</button>

<script>
    $(document).ready(function () {
        // переменные для фильтров
        let choiceAddDiscipline = $("#add_discipline_name").val();
        let choiceAddGroup, choiceAddSemester;
        getGroups(choiceAddDiscipline);

        function getGroups(choiceDiscipline) {
            let $addSemesterName = $('#add_semester_name');
            let $addGroupName = $('#add_group_name');
            $addSemesterName.empty();
            $addGroupName.empty();
            $addGroupName.append(`<option value="0" disabled selected>Поиск...</option>`);
            $.ajax({
                type: 'GET',
                url: window.appUrl + 'api/lessons/get-groups?discipline_id=' + choiceDiscipline,
                data: {'teacher_id': null},
                success: function (response) {
                    $addGroupName.empty();
                    $addGroupName.append(`<option value="" disabled selected>-- Не выбрана</option>`);
                    response.forEach(element => {
                        $addGroupName.append(`<option value="${element['id']}">${element['title']}</option>`);
                    });
                    $addGroupName.on('change', changeSelectGroup);
                }
            });
        }

        function changeSelectGroup() {
            choiceAddGroup = $(this).find(":selected").val();
            let $addSemesterName = $('#add_semester_name');
            $addSemesterName.empty();
            $addSemesterName.append(`<option value="0" disabled selected>Поиск...</option>`);
            $.ajax({
                type: 'GET',
                url: window.appUrl + 'api/lessons/get-semesters',
                data: { 'group_id': choiceAddGroup, 'discipline_id': choiceAddDiscipline },
                success: function (response) {
                    $addSemesterName.empty();
                    response.forEach(element => {
                        $addSemesterName.append(`<option value="${element}">${element}</option>`);
                    });
                    choiceAddSemester = $addSemesterName.find(":selected").val();
                }
            });
        }

        // загрузить список учебных групп, у которых преподается выбранная дисциплина
        $('#add_discipline_name').on('change', function () {
            choiceAddDiscipline = $(this).val();
            choiceAddSemester = null;
            getGroups(choiceAddDiscipline)
        });

        function isset(obj) {
            return !(typeof obj === 'undefined' || obj === null);
        }

        // добавить нагрузку преподавателю
        $('#add-lesson').click(function () {
            if (!isset(choiceAddDiscipline) || !isset(choiceAddGroup) || !isset(choiceAddSemester)) {
                alert('Нагрузка не выбрана! (дисциплина, группа, семестр)');
                return -1;
            }
            console.log($("input[name='add_discipline_id']").val());
            $.ajax({
                method: 'PATCH',
                data: {
                    '_token': $("input[name='_token']").val(),
                    'discipline_id': choiceAddDiscipline,
                    'group_id': choiceAddGroup,
                    'semester': choiceAddSemester,
                    'teacher_id': $("input[name='teacher_id']").val(),
                },
                url: 'tasks/create/lesson',
                success: function (response) {
                    alert(response);
                    $('#addLessonModal').modal('hide');
                    location.reload();
                },
                error: function (response, error) {
                    if (error === 'Forbidden') {
                        alert('При попытке добавления нагрузки произошла ошибка');
                        console.log(response.responseText);
                    } else {
                        alert(response.responseText);
                    }
                }
            });
        })

        $('#add_semester_name').on('change', function () {
            choiceAddSemester = $(this).val();
        })
    });
</script>
