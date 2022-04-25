<div class="form-group">
    <h6>Файл с решением: <a type="button" class="workFile" id="workFile_{{ $homework->id }}">{{ $homework->homework }}</a></h6>
</div>
@if ($homework->grade != 'on check')
<div class="input-group mb-2">
    <label>Отзыв преподавателя</label>
    <textarea disabled style="width: 100%" rows="5" id="grade">{{ $homework->grade }}</textarea>
    <h7><i>{{ $homework->task->lesson->teacher->user->fullName() }}</i></h7>
</div>
@endif
<div class="form-group">
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
</div>
