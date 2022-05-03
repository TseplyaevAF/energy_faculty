<style>
    .table {
        border-collapse: collapse;
        text-align: center;
    }
    /*.table th {*/
    /*    background-color: #4a8bdb;*/
    /*    color: #fff;*/
    /*}*/

    /*.table tr:first-child {*/
    /*    background-color: rgba(236, 198, 63, 0.84);*/
    /*    font-weight: bold;*/
    /*}*/
</style>

<div class="table-responsive">
    @if (count($statements) !== 0)
    <h4>Ведомость сдачи зачетов и экзаменов сессии
        {{$statements->first()->lesson->year->start_year}}-{{$statements->first()->lesson->year->end_year}}
        учебного года {{$statements->first()->lesson->semester}} семестра
    </h4>
    <button type="button" id="semester-statement-download"
            class="btn btn-outline-success btn-sm mb-3">
        Скачать в excel
    </button>
    @endif
    <div class="form-group scroll-table-body">
        <table class="table table-bordered table-hover" id="statements-table">
            <thead>
            <tr>
                <th rowspan="2">№ п/п</th>
                <th rowspan="2">ФИО</th>
                <th colspan="{{ count($statements) }}">Оценки по дисциплинам</th>
            </tr>
            </thead>
            <tbody class="group-semester-statements">
            <tr>
                <td></td>
                <td></td>
                @foreach($statements as $statement)
                    <td>
                        {{$statement->lesson->discipline->title}}
                    </td>
                @endforeach
            </tr>
            @php
                $count = 1;
            @endphp
            @foreach($studentsMarks as $studentFIO => $studentsMark)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$studentFIO}}</td>
                    @foreach($studentsMark as $mark)
                        <td>{{$mark}}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
