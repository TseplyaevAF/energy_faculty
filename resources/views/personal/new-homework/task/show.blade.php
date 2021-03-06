<link rel="stylesheet" href="{{ asset('css/personal/task/delete-style.css') }}">
<link rel="stylesheet" href="{{ asset('css/personal/homework/style.css') }}">
<input value="{{ current($data)['lesson_id'] }}" type="hidden" name="lesson_id">

{{-- Модальное окно для загрузки работы студента --}}
<div class="modal fade" id="homeworkCreateModal" tabindex="-1" role="dialog"
     aria-labelledby="homeworkCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">Загрузка домашнего задания</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="homeworkCreateModalBody">
                <label for="exampleInputFile">Выберите файл с заданием</label>
                <div class="input-group mb-2">
                    <div class="custom-file">
                        <input type="file" id="file" name="homework" class="custom-file-input" accept=".pdf,.docx">
                        <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                    </div>
                </div>
                <blockquote>
                    <span class="text-muted" style="font-size: 14px">
                        Поддерживаются следующие форматы файлов: <b>pdf и docx - </b>
                        <i>не более 10МБ</i>
                    </span>
                </blockquote>
                <button type="button" class="btn btn-primary homeworkStore">
                    Сохранить
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

{{-- Модальное окно для загрузки информации о работе студента --}}
<div class="modal fade" id="homeworkLoadModal" tabindex="-1" role="dialog"
     aria-labelledby="homeworkLoadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">Информация о задании</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="homeworkLoadModalBody">
            </div>
        </div>
    </div>
</div>

<div class="table-responsive studentsWorks__content">
    <div class="form-group">
        <h5><b>{{$lesson->discipline->title}}</b></h5>
        <h6>{{$lesson->semester}} семестр</h6>
    </div>

    <div class="form-group scroll-table-body">
        <table class="table table-bordered table-hover tableAdaptive">
            <thead>
            <tr>
                <th class="titles" colspan="{{ current($data)['tasksCount'] }}">Задания по месяцам</th>
            </tr>
            </thead>
            <tbody class="group-tasks">
            <tr>
                @foreach($data as $item)
                    @foreach($item['arrayTasks'] as $month => $tasks)
                    <td class="months" colspan="{{count($tasks)}}">
                        {{ $month }}
                    </td>
                    @endforeach
                @endforeach
            </tr>
            <tr>
                @foreach($data as $item)
                    @foreach($item['arrayTasks'] as $month => $tasks)
                        @foreach($tasks as $task)
                        <td>
                            <a type="button" class="taskFile mr-1" id="task_{{ $task->id }}">
                                {{ $task->getFileName() }}
                                <input type="hidden" value="{{ $task->task }}" name="task_path">
                            </a>
                            <span class="text-muted">{{ $task->created_at->format('d.m.Y') }}</span>
                        </td>
                        @endforeach
                    @endforeach
                @endforeach
            </tr>
            @php
                $count = 1;
            @endphp
            <tr>
            @foreach($data as $item)
                @foreach($item['arrayTasks'] as $month => $tasks)
                    @foreach($item['arrayHomework'] as $student => $homework)
                            @foreach($tasks as $taskId => $task)
                                @if (isset($item['arrayHomework'][$student][$taskId]))
                                    @if ($item['arrayHomework'][$student][$taskId]->grade != 'on check')
                                    <td class="workIsDone">
                                        <a type="button" class="homeworkLoad" style="color: white"
                                           id="homework_{{ $item['arrayHomework'][$student][$taskId]->id }}">Проверено
                                            ({{$item['arrayHomework'][$student][$taskId]->updated_at->format('d.m.Y')}})
                                        </a>
                                    </td>
                                    @else
                                        <td class="workOnVerification">
                                            <div class="row" style="margin: 0;">
                                                <a type="button" class="homeworkLoad mr-1" style="color: white"
                                                   id="homework_{{ $item['arrayHomework'][$student][$taskId]->id }}">Загружено
                                                    ({{$item['arrayHomework'][$student][$taskId]->updated_at->format('d.m.Y')}})
                                                </a>
                                                <div id="homeworkDelete_{{$item['arrayHomework'][$student][$taskId]->id}}" class="homeworkDelete" style="position: relative; top: 6px">
                                                    <div class="deleteEdu"></div>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                @else
                                    <td>
                                        <a type="button" class="homeworkCreate btn btn-outline-info"
                                           id="homeworkCreate_{{ $taskId }}">
                                            Загрузить работу
                                        </a>
                                    </td>
                                @endif
                            @endforeach
                        @endforeach
                @endforeach
            @endforeach
            </tr>
            </tbody>
        </table>
    </div>
</div>
<script src="https://getbootstrap.com/docs/4.5/assets/js/docs.min.js"></script>
<script>
    $("#loadEduMaterialModal").on("hidden.bs.modal", function () {
        $('#videoPlayer')[0].pause();
        $('#videoPlayer').get(0).currentTime = 0;
    });
</script>
