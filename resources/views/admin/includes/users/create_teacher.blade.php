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
</div>
