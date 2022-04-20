<div class="form-group">
    <h5>Назначить куратора для группы {{$group->title}}</h5>
</div>
<div class="form-group mb-3">
    <label class="control-label required">Выберите преподавателя</label>
    <select name="status" class="form-control" id="choiceTeacher">
        <option value="-1">-- Не выбран</option>
        @if (isset($curator))
            @foreach($teachersArray as $id => $teacher)
                <option value="{{ $id }}" {{ $id == $curator->id ? 'selected' : ''}} >{{ $teacher }}</option>
            @endforeach
        @else
            @foreach($teachersArray as $id => $teacher)
                <option value="{{ $id }}" >{{ $teacher }}</option>
            @endforeach
        @endif

    </select>
</div>
<div class="form-group">
    <button type="button" id="setNewCurator" class="btn btn-primary setNewCurator">
        Сохранить
    </button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
</div>
