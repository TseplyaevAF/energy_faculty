@extends('personal.layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/marks/style.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Панель управления куратора</h1>
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
            <div class="container-fluid breakpoints">
                @if (session('success'))
                    <div class="col-3 alert alert-success" role="alert">{!! session('success') !!}</div>
                @endif
                    <div class="form-group selectGroup">
                        <h6>Мои группы<span class="gcolor"></span></h6>
                        <div>
                            <select class="col-md-2 form-control formselect statement_group_name"
                                    id="statement_group_name">
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="tabs vertical">
                            <ul class="tabs__caption">
                                <li data-id="statements">Ведомости</li>
                                <li data-id="tasks">Успеваемость</li>
                                <li data-id="monthly-marks">Оценки по месяцам</li>
                            </ul>
                            <div class="tabs__content">
                                <div id="preloader">
                                    <img src="{{ asset('storage/loading.gif') }}"
                                        alt="AJAX loader" title="AJAX loader"/>
                                </div>
                                <div class="row filters">
                                    <div class="form-group col-md-6" id="control_forms">
                                        <h6>Форма контроля<span class="gcolor"></span></h6>
                                        <div class="form-s2 selectControlForm">
                                            <div>
                                                <select class="form-control formselect required"
                                                        id="statement_control_form">
                                                    <option value="reset_filter_control_form">Все</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <h6>Семестр</h6>
                                        <select class="form-control formselect required" id="semester">
                                            <option value="">-- Не выбрано</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" name="filter"
                                                id="filter" class="btn btn-info">
                                            Показать
                                        </button>
                                    </div>
                                </div>
                                <h5>Экзаменационные ведомости:</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover"
                                           id="statements-table">
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
                                Раздел Успеваемость
                            </div>
                            <div class="tabs__content">
                                Раздел Оценки по месяцам
                            </div>
                        </div><!-- .tabs-->
                    </div>
            </div>
        </section>
    </div>
    <div class="modal fade bd-example-modal-xl" id="statementModal" tabindex="-1" role="dialog"
         aria-labelledby="statementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content modal-xl">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="mediumBody">
                    <div></div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script>
    $(document).ready(function () {
        $('#preloader').hide();
        let choiceGroup = $("#statement_group_name").val();

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
            $(this).attr('disabled', true);
            $('#preloader').show();
            let filterControlForm = $('#statement_control_form').val();
            let filterSemester = $('#semester').val();
            getStatements(filterControlForm, filterSemester);
        })

        function getStatements(filterControlForm = '', filterSemester = '') {
            $.ajax({
                type: 'GET',
                url: '{{ getenv('APP_URL') }}api/statements',
                data: {
                    'semester': filterSemester,
                    'group': choiceGroup,
                    'control_form': filterControlForm
                },
                success: function (response) {
                    $('.group-statements').find('tr').remove();
                    $.each(response, function (key, item) {
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
                    $('#preloader').hide();
                    $('#filter').attr('disabled', false);
                }
            });
        }

        $("#statements-table").on('click', '.showStatement', function() {
            const url = '{{ route('personal.mark.getStatementInfo', ':id') }}';
            $.ajax({
                url:  url.replace(':id', $(this).attr('id').split('_')[1]),
                beforeSend: function() {
                    $('#preloader').show();
                },
                success: function(result) {
                    if (result === undefined) {
                        alert('Отчет по данной ведомости еще не готов');
                        return;
                    }
                    $('#statementModal').modal("show");
                    $('#mediumBody').html(result).show();
                },
                complete: function() {
                    $('#preloader').hide();
                },
                error: function(jqXHR, status, error) {
                    alert('Невозможно получить отчёт');
                    console.log(jqXHR.responseText);
                    $('#preloader').hide();
                },
                timeout: 8000
            });
        });

        $('#statement_group_name').on('change', function () {
            $('#preloader').show();
            choiceGroup = $(this).val();
            getStatements();
        });

        function showStatementReport(el) {
            $('#preloader').show();
            getControlForms(el);
            getStatements();
            $(el).closest('div.tabs').find('div.tabs__content').children('.row').show();
        }

        function getControlForms(el) {
            $.ajax({
                type: 'GET',
                url: '{{ getenv('APP_URL') }}api/control-forms',
                success: function (response) {
                    let select = createSelect(response, 'statement_control_form');
                    $(el).closest('div.tabs').find('.selectControlForm').replaceWith(select);
                    $('#statement_control_form').on('change', changeSelect);
                }
            });
        }

        function createSelect(data, id) {
            let select = '<select class="form-control" type="text" id=' + id + '>';
            select += '<option value="">-- Не выбрано</option>';
            let count = 1;
            for (let i = 0; i < Object.keys(data).length; i++) {
                select += '<option value="' + count + '">' + data[count] + '</option>';
                count++;
            }
            return select += '</select>';
        }
    });
    </script>
@endsection
