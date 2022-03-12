<div>
    <input value="@if (isset($eval)) {{ $eval }} @endif"
           type="text"
           class="form-control"
           name="eval"
           placeholder="Поставьте оценку"
           data-id="eval_{{$id}}"
    >
</div>
