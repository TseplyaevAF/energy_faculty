<div class="studentsData w-25" id="studentsDataId">
    <div class="form-group">
        <input value="{{ old('student_id_number') }}" type="text" class="form-control" name="student_id_number" placeholder="Номер студенческого билета">
        @error('student_id_number')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group">
        <label>Выберите группу</label>
        <select name="group_id" class="form-control">
            @foreach($groups as $group)
            <option value="{{$group->id }}" {{$group->id == old('group_id') ? 'selected' : ''}}>{{ $group->title }}</option>
            @endforeach
        </select>
    </div>
</div>