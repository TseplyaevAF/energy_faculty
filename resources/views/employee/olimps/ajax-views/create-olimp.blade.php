<link rel="stylesheet" href="{{ asset('css/help/style.css') }}">
<style>
    .help:before {
        bottom: 0;
        left: 0;
        transform: none;
    }
</style>
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

<div class="mb-2 templates">
    <label><input type="radio" name="radio" value="1"
        class="help" data-help="Используйте один из предыдущих постов как шаблон">
        Шаблон мероприятия
        <img class="help help-icon" src="{{ asset('assets/default/question-circle.png') }}">
    </label><br>
    <div class="block-text mb-2" id="block-1">
        <div class="form-s2 selectOlimpType">
            <select class="form-control formselect required" id="olimp_type_id">
            </select>
        </div>
    </div>
    <label><input type="radio" name="radio" value="2"
        class="help" data-help="Создайте пост с нуля">
        Новое мероприятие
        <img class="help help-icon" src="{{ asset('assets/default/question-circle.png') }}">
    </label><br>
    <div class="block-text mb-2" id="block-2" style="display: none;">
        <div class="form-group">
            <input type="text" class="form-control mb-1" name="olimp_type" placeholder="Молодёжная научная весна...">
            <span class="text-danger olimpsErrors" id="olimpTypeError"></span>
        </div>
    </div>
</div>



<button type="button" id="create-post" class="btn btn-info mb-3">
    Продолжить
</button>

<script>
    $(document).ready(function () {
        getOlimpTypes($("#category").val());

        function getOlimpTypes(category) {
            if ($('#category').find(":selected").text().toLowerCase().indexOf('новости') !== -1) {
                $('.templates').hide();
            } else {
                $('.templates').show();
            }
            let $olimpType = $('#olimp_type_id');
            $olimpType.empty();
            $olimpType.append(`<option value="0" disabled selected>Поиск...</option>`);
            $.ajax({
                type: 'GET',
                url: `olimps/get-olimp-types/${category}`,
                success: function (response) {
                    response = JSON.parse(response);
                    $olimpType.empty();
                    if (response.length === 0) {
                        $('input[name="radio"][value="1"]').prop('disabled', true);
                        return;
                    } else {
                        $('input[name="radio"][value="1"]').prop('disabled', false);
                    }
                    response.forEach(element => {
                        $olimpType.append(`<option value="${element['olimp_type']['id']}_${element['news_id']}">
                                                ${element['olimp_type']['title']} ${element['year']}
                                            </option>`);
                    });
                    $('input[name="radio"][value="1"]').prop('checked', true);
                }
            });
        }

        $('#category').on('change', function () {
            getOlimpTypes($(this).val());
        });

        $('input[name="radio"]').click(function(){
            var target = $('#block-' + $(this).val());

            $('.block-text').not(target).hide(0);
            target.fadeIn(500);
        });

        $('#create-post').on('click', function () {
            $('.olimpsErrors').html('');
            let category = $("#category");
            if (category.find(":selected").text().toLowerCase().indexOf('новости') !== -1) {
                location.href = `news/create/${category.val()}/${null}/${null}`;
                return;
            }
            // открываем существующий шаблон
            // olimpType[0] - id типа мероприятия
            // olimpType[1] - id новости, которая соответствует типу мероприятия
            if ($('input[name="radio"][value="1"]').is(':checked')) {
                let olimpType = $('#olimp_type_id').val().split('_');
                location.href = `news/create/${category.val()}/${olimpType[0]}/${olimpType[1]}`;
            }
            if ($('input[name="radio"][value="2"]').is(':checked')) {
                let olimpType = $("input[name='olimp_type']").val()
                if (olimpType === "") {
                    $('#olimpTypeError').text('Необходимо ввести название мероприятия');
                    return;
                }
                // создаем новый тип мероприятия
                if(confirm(`Название введено правильно?\n${olimpType}`)) {
                    $.ajax({
                        type: 'POST',
                        data: {
                            '_token': $("input[name='_token']").val(),
                            'olimp_type': olimpType
                        },
                        url: 'olimps/store-olimp-type',
                        datatype: 'json',
                        success: function (olimpTypeId) {
                            location.href = `news/create/${category.val()}/${olimpTypeId}/${null}`;
                        }
                    });
                }
            }
        })
    });
</script>
