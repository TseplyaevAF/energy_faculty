<style>
    .workIsDone {
        background-color: rgba(10, 147, 10, 0.6);
    }
</style>

<link rel="stylesheet" href="{{ asset('css/personal/task/delete-style.css') }}">
<input value="{{ $data['lesson_id'] }}" type="hidden" name="lesson_id">

{{--Модальное окно для добавления нового задания--}}
<div class="modal fade" id="storeTaskModal" tabindex="-1" role="dialog"
     aria-labelledby="storeTaskModalLabel" aria-hidden="true">
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
                        <input type="file" id="file" class="custom-file-input" name="task" accept=".pdf,.docx">
                        <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                    </div>
                </div>
                <blockquote>
                    <span class="text-muted" style="font-size: 14px">
                        Поддерживаются следующие форматы файлов: <b>pdf и docx - </b>
                        <i>не более 10МБ</i>
                    </span>
                </blockquote>
                <button type="button" id="storeTask" class="btn btn-primary storeTask">
                    Сохранить
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

{{--Модальное окно для проставления оценки студенту за его работу--}}
<div class="modal fade" id="loadHomeworkModal" tabindex="-1" role="dialog"
     aria-labelledby="loadHomeworkModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">Проверка домашнего задания</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="loadHomeworkModalBody">
            </div>
        </div>
    </div>
</div>

<div class="table-responsive studentsWorks__content">
    <div class="form-group">
        <h5><b>{{$lesson->discipline->title}}</b></h5>
        <h6><b>Группа: </b>{{$lesson->group->title}}, {{$lesson->semester}} семестр</h6>
    </div>
    <div class="mb-2">
        <a href="javascript:void(0)" data-toggle="modal"
           class="show btn btn-primary"
           data-target="#storeTaskModal">
            Добавить задание
        </a>
    </div>

    <div class="form-group scroll-table-body">
        <table class="table table-bordered table-hover tableAdaptive">
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
                @foreach($data['arrayTasks'] as $month => $tasks)
                <td colspan="{{count($tasks)}}">
                    {{ $month }}
                </td>
                @endforeach
            </tr>
            <tr>
                <td></td>
                <td></td>
                @foreach($data['arrayTasks'] as $month => $tasks)
                    @foreach($tasks as $task)
                    <td>
                        <div class="row" style="margin: 0">
                            <a type="button" class="taskFile mr-1" id="task_{{ $task->id }}">
                                {{ $task->getFileName() }}
                                <input type="hidden" value="{{$task->task}}" name="task_path">
                            </a>
                            <div id="taskDelete_{{ $task->id }}" class="taskDelete mr-2" style="position: relative; top: 6px">
                                <div class="deleteEdu"></div>
                            </div>
                            <span class="text-muted">{{ $task->created_at->format('d.m.Y') }}</span>
                        </div>
                    </td>
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
                    @foreach($data['arrayTasks'] as $month => $tasks)
                        @foreach($tasks as $taskId => $task)
                            @if (isset($data['arrayHomework'][$student][$taskId]))
                                @if ($data['arrayHomework'][$student][$taskId]->grade != 'on check')
                                <td class="workIsDone">
                                    <a type="button" class="homeworkLoad" style="color: white"
                                       id="homework_{{ $data['arrayHomework'][$student][$taskId]->id }}">Проверено
                                        ({{$data['arrayHomework'][$student][$taskId]->updated_at->format('d.m.Y')}})
                                    </a>
                                </td>
                                @else
                                <td>
                                    <a type="button" class="homeworkLoad"
                                       id="homework_{{ $data['arrayHomework'][$student][$taskId]->id }}">
                                        Посмотреть работу
                                    </a>
                                </td>
                                @endif
                            @else
                                <td></td>
                            @endif
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="https://getbootstrap.com/docs/4.5/assets/js/docs.min.js"></script>
<script src="{{ asset('js/personal/new-task/task.js') }}"></script>
