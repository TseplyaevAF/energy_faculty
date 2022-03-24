<table>
    <thead>
    <tr>
        <th>Экзаменационная ведомость №: {{ $statement->id }}</th>
        <th></th>
    </tr>
    </thead>
</table>

<table>
    <thead>
    <tr>
        <th>Группа: {{ $statement->lesson->group->title  }},
            семестр: {{ $statement->lesson->semester  }},
            учебный год: {{ $statement->lesson->year->start_year  }}
            -{{ $statement->lesson->year->end_year  }}</th>
    </tr>
    </thead>
</table>

<table>
    <thead>
    <tr>
        <th>Контроль:
            {{ $statement->lesson->discipline->title }}, {{ $controlForms[$statement->control_form] }}</th>
    </tr>
    </thead>
</table>

<table>
    <thead>
    <tr>
        <th>Дата проведения экзамена: {{ date('d.m.Y', strtotime($statement->start_date) ) }}</th>
        <th></th>
    </tr>
    </thead>
</table>


<table>
    <thead>
    <tr>
        <th><b>ФИО студента</b></th>
        <th><b>№ зачетной книжки</b></th>
        <th><b>Результат</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach( $individuals as $individual)
        <tr>
            <td style="width: 250px;">{{ $individual['studentFIO'] }}</td>
            <td style="width: 150px;">{{ $individual['student_id_number'] }}</td>
            <td style="width: 90px;">{{ $evalTypes[$individual['evaluation']] }}</td>
        </tr>
    @endforeach
</table>
