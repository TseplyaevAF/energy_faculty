<style>
    label.required:after
    {
        color: rgba(205, 17, 17, 0.88);
        content: " *";
    }
</style>
<div class="form-group">
    <h5>
        Файл с решением:
        <a type="button" class="homeworkFile" id="homeworkFile_{{$homework->id}}"><h6>{{ $homework->homework }}</h6></a>
    </h5>
    <h6>Работу выполнил:
        {{ $homework->student->user->surname }}
        {{ mb_substr($homework->student->user->name, 0, 1) }}.
        {{ mb_substr($homework->student->user->patronymic, 0, 1)}}.
    </h6>
</div>
<div class="input-group mb-2">
    <label class="control-label required">Оставьте комментарий</label>
    <input type="hidden" value="{{ $homework->task_id }}" name="task_id">
    <textarea style="width: 100%" rows="5" id="grade">@if ($homework->grade != 'on check'){{$homework->grade}}@endif</textarea>
</div>
<div class="form-group">
    <button type="button" id="checkHomework" class="btn btn-primary checkHomework">
        Сохранить
    </button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
</div>
<script src="{{ asset('js/personal/new-task/homework.js') }}"></script>
