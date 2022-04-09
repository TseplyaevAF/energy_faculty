@extends('personal.layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/marks/style.css') }}">
<style>

</style>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Успеваемость</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="col-3 alert alert-success" role="alert">{!! session('success') !!}</div>
                @endif
                    <div class="row">
                        <div class="tabs vertical">
                            <ul class="tabs__caption">
                                <li data-id="statements">Ведомости</li>
                                <li data-id="tasks">Задания</li>
                                <li data-id="monthly-marks">Месячные оценки</li>
                            </ul>
                            <div class="tabs__content">
                                <div id="preloader">
                                    <img src="{{ asset('storage/loading.gif') }}" alt=
                                    "AJAX loader" title="AJAX loader"/>
                                </div>
                                <div class="row filters w-50">
                                    <div class="form-group col-md-6" id="groups">
                                        <h6>Мои группы<span class="gcolor"></span></h6>
                                        <div class="form-s2 selectGroup">
                                            <div>
                                                <select class="form-control formselect required" id="statement_group_name">
                                                    <option value="reset_filter_group">Все группы</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <h6>Семестр</h6>
                                        <select class="form-control formselect required" id="semester">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                            <option>6</option>
                                            <option>7</option>
                                            <option>8</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" name="filter"
                                                id="filter" class="btn btn-info">
                                            Показать
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5>Экзаменационные ведомости:</h5>
                                    <table class="table table-sm" id="statements-table">
                                        <thead>
                                        <tr>
                                            <th>№ ведомости</th>
                                            <th>Группа</th>
                                            <th>Дисциплина</th>
                                            <th>Форма контроля</th>
                                            <th>Семестр</th>
                                            <th>Учебный год</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                        <tbody class="group-statements"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tabs__content">
                                Содержимое второго блока
                            </div>
                        </div><!-- .tabs-->
                    </div>
            </div>
        </section>
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#preloader').hide();
        $('ul.tabs__caption').on('click', 'li:not(.active)', function() {
            $(this).closest('div.tabs').find('div.tabs__content').children('.row').hide();
            let id = $(this).attr('data-id');
            $(this)
                .addClass('active').siblings().removeClass('active')
                .closest('div.tabs').find('div.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
            if (id === 'statements') {
                showStatementReport(this);
            }
        });

        function changeSelect() {
            let id = $(this).find(":selected").val();
        }

        $('#filter').click(function () {
            let filterGroup = $('#statement_group_name').val();
            let filterSemester = $('#semester').val();
            getStatements(filterGroup, filterSemester);
        })

        function getStatements(filterGroup = '', filterSemester = '') {
            $.ajax({
                type: 'GET',
                url: 'marks/getStatements',
                data: {
                    'semester': filterSemester,
                    'group': filterGroup
                },
                success: function (response) {
                    let data = JSON.parse(response);
                    console.log(data)
                    $('.group-statements').find('tr').remove();
                    $.each(data, function (key, item) {
                        $('.group-statements').append('<tr>\
                            <td>' + item.id + '</td>\
                            <td>' + item.lesson.group + '</td>\
                            <td>' + item.lesson.discipline + '</td>\
                            <td>' + item.control_form + '</td>\
                            <td>' + item.lesson.semester + '</td>\
                            <td>' + item.lesson.year + '</td>\
                            <td><a type="button" a-toggle="modal" id="statement_' + item.id +'"\
                                data-attr="" data-target="#smallModal" class="showStatement">\
                                <i class="fas fa-eye text-success fa-lg"></i>\
                            </a></td>\
                            </tr>');
                    })
                }
            });
        }

        $("#statements-table").on('click', '.showStatement', function() {
            var id = $(this).attr('id');
            alert(id)
        });

        function showStatementReport(el) {
            $('#preloader').show();
            $.ajax({
                type: 'GET',
                url: "{{ route('personal.mark.getGroups') }}",
                success: function (response) {
                    let data = JSON.parse(response);
                    let select = createSelect(data, 'statement_group_name');
                    $(el).closest('div.tabs').find('.selectGroup').replaceWith(
                        select
                    );
                    $('#statement_group_name').on('change', changeSelect);
                    $('#preloader').hide();
                    $(el).closest('div.tabs').find('div.tabs__content').children('.row').show();
                }
            });
        }

        function createSelect(data, id) {
            let select = '<select class="form-control" type="text" id=' + id + '>';
            select += '<option value="">-- Не выбрано</option>';
            for (let i = 0; i < data.length; i++) {
                select += '<option value="' + data[i].id + '">' + data[i].title + '</option>';
            }
            select += '</select>';
            return select;
        }
    });
</script>
@endsection
