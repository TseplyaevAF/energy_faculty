<style>
    .workIsDone {
        background-color: rgba(10, 147, 10, 0.6);
    }
    .workOnVerification {
        background-color: rgba(52, 93, 170, 0.56);
    }
</style>

<input value="{{ $data['lesson_id'] }}" type="hidden" name="lesson_id">

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
                        <input type="file" id="file" name="homework" class="custom-file-input" accept=".pdf">
                        <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                    </div>
                </div>
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
        <table class="table table-bordered table-hover" id="statements-table">
            <thead>
            <tr>
                <th colspan="{{ $data['tasksCount'] }}">Задания по месяцам</th>
            </tr>
            </thead>
            <tbody class="group-tasks">
            <tr>
                @foreach($data['arrayTasks'] as $month => $tasks)
                <td colspan="{{count($tasks)}}">
                    {{ $month }}
                </td>
                @endforeach
            </tr>
            <tr>
                @foreach($data['arrayTasks'] as $month => $tasks)
                    @foreach($tasks as $task)
                    <td>
                        {{ $task }}
                    </td>
                    @endforeach
                @endforeach
            </tr>
            @php
                $count = 1;
            @endphp
            <tr>
            @foreach($data['arrayTasks'] as $month => $tasks)
                @foreach($data['arrayHomework'] as $student => $homework)
                        @foreach($tasks as $taskId => $task)
                            @if (isset($data['arrayHomework'][$student][$taskId]))
                                @if ($data['arrayHomework'][$student][$taskId]->grade != 'on check')
                                <td class="workIsDone">
                                    <a type="button" class="homeworkLoad" style="color: white"
                                       id="homework_{{ $data['arrayHomework'][$student][$taskId]->id }}">
                                        {{ $data['arrayHomework'][$student][$taskId]->homework }}
                                    </a>
                                </td>
                                @else
                                    <td class="workOnVerification">
                                        <a type="button" class="homeworkLoad" style="color: white"
                                           id="homework_{{ $data['arrayHomework'][$student][$taskId]->id }}">
                                            {{ $data['arrayHomework'][$student][$taskId]->homework }}
                                        </a>
                                    </td>
                                @endif
                            @else
                                <td>
                                    <a type="button" class="homeworkCreate"
                                       id="homeworkCreate_{{ $taskId }}">
                                        Загрузить работу
                                    </a>
                                </td>
                            @endif
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
    });
</script>
