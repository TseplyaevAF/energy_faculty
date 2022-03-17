<div class="teachersData mt-4" id="teachersDataId">
    <input type="hidden" name="{{ $teacher = $user->teacher }}">
    <input value="3" type="hidden" name="role_id">
    <div class="form-group w-25">
        <input value="{{ $teacher->post }}" type="text" class="form-control" name="post" placeholder="Должность">
        @error('post')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-25">
        <input value="{{ $teacher->rank }}" type="text" class="form-control" name="rank" placeholder="Должность">
        @error('rank')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group w-25">
        <label>Выберите кафедру</label>
        <select name="chair_id" class="form-control">
            @foreach($chairs as $chair)
            <option value="{{$chair->id }}" {{ $chair->id == $teacher->chair_id ? 'selected' : ''}}>{{ $chair->title }}</option>
            @endforeach
        </select>
    </div>
</div>
