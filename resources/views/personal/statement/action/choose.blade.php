<style>
    input.largerCheckbox {
        width: 20px;
        height: 20px;
    }
</style>
<div>
    <input value="{{ $isChoose }}" type="checkbox" name="choose" data-id="selectRow_{{ $id }}" class="choose largerCheckbox"
           "
        @if($isChoose)
            checked
        @else
           {{ $isChoose = true }}
        @endif
    >
    <label for="scales"></label>
</div>
