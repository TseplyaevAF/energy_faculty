<div class="form-group">
    <h5>Назначить преподавателя для следующей нагрузки:</h5>
    <h6>{{$lesson->group->title}}, {{$lesson->semester}} семестр, {{$lesson->discipline->title}}</h6>
</div>
<div class="form-group mb-3">
    <label class="control-label required">Выберите преподавателя</label>
    <select name="status" class="form-control" id="choiceTeacher">
        <option value="-1">-- Не выбран</option>
        @foreach($teachers as $teacher)
            <option value="{{ $teacher->id }}" {{ $teacher->id == $lesson->teacher->id ? 'selected' : ''}} >{{ $teacher->user->fullName() }}</option>
        @endforeach

    </select>
</div>
<div class="form-group">
    <button type="button" id="setNewTeacher" class="btn btn-primary setNewTeacher">
        Сохранить
    </button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
</div>
