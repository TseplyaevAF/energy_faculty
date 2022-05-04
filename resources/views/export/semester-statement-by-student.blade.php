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
<table>
    <thead>
    <tr>
        <th>
            <b>Студент:</b> {{ key($studentMarks) }}
        </th>
    </tr>
    </thead>
</table>
<table class="table table-bordered table-hover" id="statements-table">
    <thead>
    <tr>
        <th style="width: 300px; height: 30px; text-align: center; font-weight: bold; background-color: #86baa1; color: #043204">
            Название дисциплины
        </th>
        <th style="width: 300px; height: 30px; text-align: center; font-weight: bold; background-color: #86baa1; color: #043204">
            Форма контроля
        </th>
        <th style="width: 300px; height: 30px; text-align: center; font-weight: bold; background-color: #86baa1; color: #043204">
            Оценка
        </th>
    </tr>
    </thead>
    <tbody class="group-semester-statements">
    @php
      $count = 0;
    @endphp
        @foreach(current($studentMarks) as $mark)
            <tr>
            <td style="width: 125px; background-color: #eecf49; color: #8e4b36">
                {{ $statements[$count]->lesson->discipline->title }}
            </td>
            <td style="width: 125px; background-color: #eecf49; color: #8e4b36">
                {{ $control_forms[$statements[$count]->control_form] }}
            </td>
            <td style="width: 125px; background-color: #eecf49; color: #8e4b36">
                {{ $mark }}
            </td>
            {{ $count++ }}
            </tr>
        @endforeach
    </tbody>
</table>
