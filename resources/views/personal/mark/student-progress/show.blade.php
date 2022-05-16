<style>
    .titles {
        font-weight: bold;
        background-color: #86baa1;
        color: #043204;
    }
</style>

<input type="hidden" name="student_progress" value="{{ json_encode($data['arrayStudentsProgress']) }}">
<input type="hidden" name="student_ids" value="{{ json_encode($data['studentsIds']) }}">

{{--Модальное окно для отправки успеваемости родителям--}}
<div class="modal fade" id="sendStudentProgressToParentsModal" tabindex="-1" role="dialog"
     aria-labelledby="sendStudentProgressToParentsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">Отправка успеваемости родителям</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="sendStudentProgressToParentsModalBody">
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <button type="button" id="send-student-progress-modal"
            class="btn btn-outline-primary btn-sm mb-3">
        Отправить успеваемость родителям
    </button>
    <div class="form-group scroll-table-body">
        <table class="table table-bordered table-hover tableAdaptive">
            <thead>
            <tr>
                <td rowspan="3" style="width: 25%"></td>
                <td colspan="10" style="font-weight: bold; text-align: center">
                    Успеваемость по дисциплинам за {{ $monthName }}
                </td>
            </tr>
            </thead>
            <tbody class="student-progress-table">
                <tr>
                    <td></td>
                    @foreach($data['arrayDisciplines'] as $discipline)
                        <td colspan="2" class="titles">{{ $discipline }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="font-weight: bold; text-align: center">ФИО</td>
                    @for ($i=0; $i<count($data['arrayDisciplines']); $i++)
                        <td style="width: 125px; background-color: rgba(236,217,120,0.84); font-weight: bold; color: #7d4a39">Пропуски</td>
                        <td style="width: 125px; background-color: #e2b51f; font-weight: bold; color: #7d4a39">Оценка</td>
                    @endfor
                </tr>
                @foreach($data['arrayStudentsProgress'] as $student => $studentProgress)
                <tr id="studentProgress_{{ $data['studentsIds'][$student] }}">
                    <td>{{ $student }}</td>
                    @foreach($studentProgress as $marks)
                        <td>{{ $marks['number_of_passes'] }}</td>
                        <td>{{ $marks['mark'] }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="https://getbootstrap.com/docs/4.5/assets/js/docs.min.js"></script>
<script>
    $(document).ready(function () {
        $('#send-student-progress-modal').on('click', function () {
            $.ajax({
                type: 'GET',
                url:  'marks/get-parents-emails/',
                data: { 'students_ids': studentsIds },
                success: function(response) {
                    $('#sendStudentProgressToParentsModal').modal("show");
                    $('#sendStudentProgressToParentsModalBody').html(response).show();
                }
            });
        })
    });
</script>
