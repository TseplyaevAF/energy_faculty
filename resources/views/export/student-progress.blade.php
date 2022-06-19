<table>
    <thead>
    <tr>
        <th>
            <span>Студент: {{ $studentFIO }}</span>
        </th>
    </tr>
    </thead>
</table>
<table>
    <thead>
    <tr>
        <th>
            <span>Успеваемость за {{ $month }}, {{ $semester }} семестр</span>
        </th>
    </tr>
    </thead>
</table>
<table>
    <thead>
    <tr>
        <th style="width: 300px; height: 30px; text-align: center; font-weight: bold; background-color: #86baa1; color: #043204">
            Название дисциплины
        </th>
        <th style="width: 300px; height: 30px; text-align: center; font-weight: bold; background-color: #86baa1; color: #043204">
            Пропуски
        </th>
        <th style="width: 300px; height: 30px; text-align: center; font-weight: bold; background-color: #86baa1; color: #043204">
            Оценка
        </th>
    </tr>
    </thead>
    <tbody>
        @foreach($studentProgress as $discipline => $marks)
            <tr>
                <td style="width: 125px;">
                    {{ $discipline }}
                </td>
                <td style="width: 125px;">
                    {{ $marks['number_of_passes'] }}
                </td>
                <td style="width: 125px;">
                    {{ $marks['mark'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
