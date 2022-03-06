<div class="teachersData" id="teachersDataId">
    <div class="form-group w-25">
        <input value="{{ old('post') }}" type="text" class="form-control" name="post" placeholder="Должность">
        @error('post')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div><div class="form-group w-25">
        <input value="{{ old('rank') }}" type="text" class="form-control" name="rank" placeholder="Звание">
        @error('rank')
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
