<div class="studentsData mt-4" id="studentsDataId">
    <input type="hidden" name="{{ $student = $user->role->student }}">
    <input value="1" type="hidden" name="role_id">
    <input value="{{ $student->id }}" type="hidden" name="student_id">

    <div class="form-group w-25">
        <input value="{{ $student->student_id_number }}" type="text" class="form-control" name="student_id_number" placeholder="Номер студенческого билета">
        @error('student_id_number')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-25">
        <label>Выберите группу</label>
        <select name="group_id" class="form-control">
            @foreach($groups as $group)
            <option value="{{ $group->id }}" {{$group->id == $student->group_id ? 'selected' : ''}}>{{ $group->title }}</option>
            @endforeach
        </select>
    </div>
</div>