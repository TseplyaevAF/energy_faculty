<table>
    <thead>
    <tr>
        <th>Ведомость сдачи зачетов и экзаменов сессии
            {{$statements->first()->lesson->year->start_year}}-{{$statements->first()->lesson->year->end_year}}
            учебного года {{$statements->first()->lesson->semester}} семестра
            группы {{$statements->first()->lesson->group->title }}
        </th>
    </tr>
    </thead>
</table>
<table class="table table-bordered table-hover" id="statements-table">
    <thead>
    <tr>
        <th rowspan="2" style="font-weight: bold; background-color: #86baa1; color: #043204">№ п/п</th>
        <th rowspan="2" style="font-weight: bold; background-color: #86baa1; color: #043204">ФИО</th>
        <th style="width: 300px; height: 50px;
        text-align: center; font-weight: bold;
        background-color: #86baa1; color: #043204" colspan="{{ count($statements) }}">Оценки по дисциплинам</th>
    </tr>
    </thead>
    <tbody class="group-semester-statements">
    <tr>
        @foreach($statements as $statement)
            <td style="width: 125px; background-color: #e2b51f; color: #8e4b36">
                {{$statement->lesson->discipline->title}}
            </td>
        @endforeach
    </tr>
    @php
        $count = 1;
    @endphp
    @foreach($studentsMarks as $studentFIO => $studentsMark)
        <tr>
            <td style="text-align: center;">{{$count++}}</td>
            <td style="width: 250px;">{{$studentFIO}}</td>
            @foreach($studentsMark as $mark)
                <td>{{$mark}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

