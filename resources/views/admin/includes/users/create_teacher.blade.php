<div class="teachersData" id="teachersDataId">
    <div class="form-group w-25">
        <input value="{{ old('post') }}" type="text" class="form-control" name="post" placeholder="Должность">
        @error('post')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-50">
        <label>Деятельность</label>
        <textarea id="summernote" name="activity">{{ old('activity') }}</textarea>
        @error('activity')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-25">
        <input value="{{ old('work_experience') }}" type="text" class="form-control" name="work_experience" placeholder="Стаж работы">
        @error('work_experience')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-25">
        <input value="{{ old('address') }}" type="text" class="form-control" name="address" placeholder="Адрес">
        @error('address')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-25">
        <label>Выберите кафедру</label>
        <select name="chair_id" class="form-control">
            @foreach($chairs as $chair)
            <option value="{{$chair->id }}" {{$chair->id == old('chair_id') ? 'selected' : ''}}>{{ $chair->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group w-25">
        <label>Выберите преподаваемые дисциплины</label>
        <select class="select2" name="disciplines_ids[]" multiple="multiple" data-placeholder="Выберите дисциплины" style="width: 100%;">
            @foreach ($disciplines as $discipline)
            <option {{ is_array(old('disciplines_ids')) 
                    && in_array($discipline->id, old('disciplines_ids')) 
                    ? 'selected' : ''}} value="{{ $discipline->id }}">{{ $discipline->title }}</option>
            @endforeach
        </select>
        @error('disciplines_ids')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>