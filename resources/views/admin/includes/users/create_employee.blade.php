<div class="employeesData w-25" id="employeesDataId">
    <div class="form-group">
        <label>Выберите кафедру</label>
        <select name="chair_id" class="form-control">
            @foreach($chairs as $chair)
            <option value="{{$chair->id }}" {{$chair->id == old('chair_id') ? 'selected' : ''}}>{{ $chair->title }}</option>
            @endforeach
        </select>
    </div>
</div>