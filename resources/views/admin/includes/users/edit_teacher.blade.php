<div class="teachersData mt-4" id="teachersDataId">
    <input type="hidden" name="{{ $teacher = $user->role->teacher }}">
    <input value="2" type="hidden" name="role_id">

    <div class="form-group w-25">
        <input value="{{ $teacher->post }}" type="text" class="form-control" name="post" placeholder="Должность">
        @error('post')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-50">
        <label>Деятельность</label>
        <textarea id="summernote" name="activity">{{ $teacher->activity }}</textarea>
        @error('activity')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-25">
        <input value="{{ $teacher->work_experience }}" type="text" class="form-control" name="work_experience" placeholder="Стаж работы">
        @error('work_experience')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-25">
        <input value="{{ $teacher->address }}" type="text" class="form-control" name="address" placeholder="Адрес">
        @error('address')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-25">
        <label>Выберите кафедру</label>
        <select name="chair_id" class="form-control">
            @foreach($chairs as $chair)
            <option value="{{$chair->id }}" {{$chair->id == $teacher->chair_id ? 'selected' : ''}}>{{ $chair->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group w-25">
        <label>Выберите преподаваемые дисциплины</label>
        <select class="select2" name="disciplines_ids[]" multiple="multiple" data-placeholder="Выберите дисциплины" style="width: 100%;">
            @foreach ($disciplines as $discipline)
            <option {{ is_array($teacher->disciplines->pluck('id')->toArray()) 
                    && in_array($discipline->id, $teacher->disciplines->pluck('id')->toArray())
                    ? 'selected' : ''}} value="{{ $discipline->id }}">{{ $discipline->title }}</option>
            @endforeach
        </select>
        @error('disciplines_ids')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>