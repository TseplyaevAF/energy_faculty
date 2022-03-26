@extends('dekanat.layouts.main')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/datepicker/cssworld.ru-xcal.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Добавить ведомость для группы {{ $group->title  }}</h1>
                    </div>
                    <input value="{{ $group->id }}" type="hidden" id="group_id">
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
                @if (session('error'))
                    <div class="col-3 alert alert-warning" role="alert">{!! session('error') !!}</div>
                @endif
                <div class="row w-50">
                    <div class="col-12">
                        <form action="{{ route('dekanat.statement.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group col-md-6">
                                <h6>Учебный год</h6>
                                <select class="form-control formselect required" placeholder="Select Sub Category"
                                        id="year">
                                    @foreach($years as $year)
                                        <option value="{{ $year['id'] }}" {{$year['id'] == old('year') ? 'selected' : ''}}>
                                            {{ $year['start_year'] }}-{{ $year['end_year'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <h6>Дисциплины</h6>
                                <select class="form-control formselect required" placeholder="Select Sub Category"
                                        id="discipline" name="lesson_id"></select>
                            </div>
                            <div class="form-group col-md-6">
                                <h6>Форма контроля</h6>
                                <select class="form-control formselect required" placeholder="Select Sub Category"
                                        name="control_form">
                                    @foreach($controls as $key => $control)
                                        <option value="{{ $key }}" {{ $key == old('control_form') ? 'selected' : '' }}>
                                            {{ $control }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <h6>Дата проведения экзамена</h6>
                                <input autocomplete="off" type="text" class="form-control"
                                name="start_date" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                            </div>
                            <div class="form-group col-md-3">
                                <h6>Дата сдачи ведомости</h6>
                                <input autocomplete="off" type="text" class="form-control"
                                name="finish_date" size="10" onClick="xCal(this)" onKeyUp="xCal()">
                            </div>
                            <h5>Подписать ведомость</h5>
                            <div class="input-group mb-2 w-50">
                                <div class="custom-file">
                                    <!-- multiple -->
                                    <input type="file" class="custom-file-input" name="private_key" accept=".key">
                                    <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary mb-2" value="Сохранить">
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/news/cssworld.ru-xcal-en.js') }}"></script>
    <script>
        $(document).ready(function () {
            let groupId = $('#group_id').val();

            getDisciplines($('#year').val());

            $('#year').on('change', function () {
                getDisciplines($(this).val());
            });

            function getDisciplines(id) {
                $('#discipline').empty();
                $('#discipline').append(`<option value="0" disabled selected>Поиск...</option>`);
                $.ajax({
                    type: 'GET',
                    url: 'getDisciplines/' + groupId + '/' + id,
                    success: function (response) {
                        var res = JSON.parse(response);
                        $('#discipline').empty();
                        jQuery.each(res, function(index, value) {
                            console.log(res);
                            $('#discipline').append(`<option value="${value['id']}">
                            ${value['discipline']},
                            ${value['teacher']},
                            ${value['semester']}
                        </option>`);
                        });
                    }
                });
            }
        });
    </script>
@endsection
