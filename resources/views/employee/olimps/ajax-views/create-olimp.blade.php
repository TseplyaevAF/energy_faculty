<div class="mb-3">
    <h6>Категория<span class="gcolor"></span></h6>
    <div class="form-s2 selectCategory">
        <select class="form-control formselect" id="category">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->title }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-2">
    <label><input type="radio" name="radio" value="1"> Шаблон мероприятия</label><br>
    <div class="block-text mb-2" id="block-1">
        <div class="form-s2 selectOlimpType">
            <select class="form-control formselect required" id="add_olimp_type_title">
            </select>
        </div>
    </div>
    <label><input type="radio" name="radio" value="2"> Новое мероприятие</label><br>
    <div class="block-text mb-2" id="block-2" style="display: none;">
        <div class="form-group">
            <input type="text" class="form-control" name="olimp_type" placeholder="Молодёжная научная весна...">
        </div>
    </div>
</div>



<button type="button" id="create-post" class="btn btn-info mb-3">
    Продолжить
</button>

<script>
    $(document).ready(function () {
        $('input[name="radio"][value="1"]').prop('checked', true);
        getOlimpTypes($("#category").val());

        function getOlimpTypes(category) {
            let $addOlimpTypeTitle = $('#add_olimp_type_title');
            $addOlimpTypeTitle.empty();
            $addOlimpTypeTitle.append(`<option value="0" disabled selected>Поиск...</option>`);
            $.ajax({
                type: 'GET',
                url: `olimps/get-olimp-types/${category}`,
                success: function (response) {
                    $addOlimpTypeTitle.empty();
                    JSON.parse(response).forEach(element => {
                        $addOlimpTypeTitle.append(`<option value="${element['id']}">${element['title']}</option>`);
                    });
                }
            });
        }

        // загрузить список учебных групп, у которых преподается выбранная дисциплина
        $('#category').on('change', function () {
            getOlimpTypes($(this).val());
        });

        $('input[name="radio"]').click(function(){
            var target = $('#block-' + $(this).val());

            $('.block-text').not(target).hide(0);
            target.fadeIn(500);
        });
    });
</script>
