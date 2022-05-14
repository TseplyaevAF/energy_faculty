<table>
    <thead class="mt-3">
    <tr>
        <th scope="col" class="col-1">День</th>
        <th scope="col" class="col-1">Время</th>
        <th scope="col" class="col-4">Верхняя неделя</th>
        <th scope="col" class="col-4">Нижняя неделя</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($days as $day_id =>  $day)
        <tr>
            <th rowspan="7" style="text-align: center; font-size: 16px">
                <div>
                    <h2><strong>{{$day}}</strong></h2>
                </div>
            </th>
        </tr>
        @foreach ($class_times as $time)
        <tr>
            <td style="width: 120px">{{$time->getClassTime()}}</td>
            @if ($time->getClassTime() == '08:30:00-10:05:00' && $day == 'ПН')
            <td style="width: 150px">
                Администрирование вычислительных сетей ·
                Машкин Владимир Анатольевич ·
                <p class="text-muted">практика</p> ·
                03-404
            </td>
            @endif
            <td style="width: 150px"></td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
