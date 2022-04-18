<input value="{{ $data['lesson_id'] }}" type="hidden" name="lesson_id">

{{--Модальное окно для добавления нового задания--}}
<div class="modal fade" id="createTask" tabindex="-1" role="dialog"
     aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">Добавление нового задания</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="exampleInputFile">Выберите файл с заданием</label>
                <div class="input-group mb-2">
                    <div class="custom-file">
                        <input type="file" id="file" class="custom-file-input" name="task" accept=".pdf">
                        <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                    </div>
                </div>
                <button type="button" id="createTask" class="btn btn-primary createTask">
                    Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

{{--Модальное окно для проставления оценки студенту за его работу--}}
<div class="modal fade" id="loadHomework" tabindex="-1" role="dialog"
     aria-labelledby="loadHomeworkModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="loadHomeworkModalBody">
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <div class="mb-2">
        <a href="javascript:void(0)" data-toggle="modal"
           class="show btn btn-primary"
           data-target="#createTask">
            Добавить задание
        </a>
    </div>
    <div class="form-group scroll-table-body">
        <table class="table table-bordered table-hover" id="tasks-table">
            <thead>
            <tr>
                <th rowspan="3" style="width: 20px">№</th>
                <th rowspan="3" style="width: 25%">ФИО</th>
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
                                <td><a type="button" class="homeworkLoad"
                                       id="homework_{{$data['arrayHomework'][$student][$taskId]->id }}">
                                        {{ $data['arrayHomework'][$student][$taskId]->homework }}
                                    </a>
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
<script src="https://getbootstrap.com/docs/4.5/assets/js/docs.min.js"></script>