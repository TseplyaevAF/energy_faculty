<table>
    <thead>
    <tr>
        <th>ФИО</th>
        <th>Кол-во пропусков</th>
        <th >Оценка за месяц</th>
    </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
            <tr>
                <td style="width: 260px;">{{ $student->user->fullName() }}</td>
                <td style="width: 120px; text-align: center">1</td>
                <td style="width: 120px; text-align: center">5</td>
            </tr>
        @endforeach
    </tbody>
</table>
