<style>
    label.required:after
    {
        color: rgba(205, 17, 17, 0.88);
        content: " *";
    }
</style>
<div class="form-group">
    <h5>Файл с решением: <a type="button" class="workFile" id="workFile_{{$homework->id}}">{{ $homework->homework }}</a></h5>
    <h6>Работу выполнил:
        {{ $homework->student->user->surname }}
        {{ mb_substr($homework->student->user->name, 0, 1) }}.
        {{ mb_substr($homework->student->user->patronymic, 0, 1)}}.
    </h6>
</div>
<div class="form-group mb-3">
    <label class="control-label required">Выберите оценку</label>
    <select name="status" class="form-control">
        <option value="-1">-- Не выбрана</option>
        <option value="1">Зачтено</option>
        <option value="0">Не зачтено</option>
    </select>
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