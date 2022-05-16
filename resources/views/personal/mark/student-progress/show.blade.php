<style>
    .titles {
        font-weight: bold;
        background-color: #86baa1;
        color: #043204;
    }
</style>

<div class="table-responsive">
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
                <tr>
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
