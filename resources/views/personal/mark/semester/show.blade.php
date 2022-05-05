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

<div class="modal fade bd-example" id="semesterStatementByStudentModal" tabindex="-1" role="dialog"
     aria-labelledby="semesterStatementByStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">Получить семестровый отчёт по студенту</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <h6>Студент</h6>
                    <div class="form-s2">
                        <select class="form-control formselect required" id="semester-statement-student">
                        </select>
                    </div>
                </div>
                <button type="button" id="semester-statement-student-download" class="btn btn-info mb-3">
                    Скачать отчёт
                </button>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    @if (count($statements) !== 0)
    <h4>Ведомость сдачи зачетов и экзаменов сессии
        {{$statements->first()->lesson->year->start_year}}-{{$statements->first()->lesson->year->end_year}}
        учебного года {{$statements->first()->lesson->semester}} семестра
    </h4>
    <button type="button" id="semester-statement-download"
            class="btn btn-outline-success btn-sm mb-3">
        Скачать полный отчёт
    </button>
    <button type="button" id="semester-statement-by-student-modal"
            class="btn btn-outline-primary btn-sm mb-3">
        Скачать отчёт по студенту
    </button>
    @endif
    <div class="form-group scroll-table-body">
        <table class="table table-bordered table-hover tableAdaptive">
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
