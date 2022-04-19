<style>
    .workIsDone {
        background-color: rgba(10, 147, 10, 0.6);
        color: white;
    }
    .workOnVerification {
        background-color: rgba(52, 93, 170, 0.56);
        color: white;
    }
</style>
<div class="table-responsive">
    <div class="form-group scroll-table-body">
        <table class="table table-bordered table-hover" id="tasks-table">
            <thead>
            <tr>
                <th rowspan="3" style="width: 20px">№</th>
                <th rowspan="3" style="width: 25%;">ФИО</th>
                <th colspan="{{ $data['tasksCount'] }}">Задания по месяцам</th>
            </tr>
            </thead>
            <tbody class="group-tasks">
            <tr>
                <td></td>
                <td></td>
                @foreach($data['arrayTasks'] as $year => $months)
                    @foreach($months as $month => $tasks)
                    <td colspan="{{count($tasks)}}">
                        {{ $month }}
                    </td>
                    @endforeach
                @endforeach

            </tr>
            <tr>
                <td></td>
                <td></td>
                @foreach($data['arrayTasks'] as $year => $months)
                    @foreach($months as $month => $tasks)
                        @foreach($tasks as $task)
                        <td>
                            {{ $task }}
                        </td>
                        @endforeach
                    @endforeach
                @endforeach
            </tr>
            @php
                $count = 1;
            @endphp
            @foreach($data['arrayHomework'] as $student => $homework)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$student}}</td>
                    @foreach($data['arrayTasks'] as $year => $months)
                        @foreach($months as $month => $tasks)
                            @foreach($tasks as $taskId => $task)
                                @if (isset($data['arrayHomework'][$student][$taskId]))
                                    @if ($data['arrayHomework'][$student][$taskId]->grade != 'on check')
                                        <td class="workIsDone">Проверено</td>
                                    @else
                                        <td class="workOnVerification">Работа на проверке</td>
                                    @endif
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
