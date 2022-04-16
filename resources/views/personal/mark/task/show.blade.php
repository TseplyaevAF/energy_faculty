<div class="table-responsive">
    <div class="form-group scroll-table-body">
        <table class="table table-bordered table-hover" id="tasks-table">
            <thead>
            <tr>
                <th rowspan="3">№ п/п</th>
                <th rowspan="3">ФИО</th>
                <th colspan="{{ $tasksCount }}">Учебные года</th>
            </tr>
            </thead>
            <tbody class="group-tasks">
            <tr>
                <td></td>
                <td></td>
                @foreach($arrayTasks as $year => $months)
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
                @foreach($arrayTasks as $year => $months)
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
            @foreach($arrayHomework as $student => $homework)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$student}}</td>
                    @foreach($arrayTasks as $year => $months)
                        @foreach($months as $month => $tasks)
                            @foreach($tasks as $taskId => $task)
                                @if (isset($arrayHomework[$student][$taskId]))
                                <td>
                                    {{ $homework[$taskId] }}
                                </td>
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
