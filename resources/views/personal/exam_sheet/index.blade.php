@extends('personal.layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/exam_sheets/style.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Допуски</h1>
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
                @foreach($exam_sheets as $sheet)
                    <div class="col-lg-4">
                        <div class="card card-margin">
                            <div class="card-header no-border">
                                <h5 class="card-title">Экзаменационный лист</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="widget-49">
                                    <div class="widget-49-title-wrapper">
                                        <div class="widget-49-date-primary">
                                            <span class="widget-49-date-day">
                                                {!! QrCode::size(50)->generate(route('personal.exam_sheet.show', $sheet->id)) !!}
                                            </span>
                                            <span class="widget-49-date-month"></span>
                                        </div>
                                        <div class="widget-49-meeting-info">
                                            <span class="widget-49-pro-title">
                                                Студент: {{ $student->user->surname }} {{ $student->user->name }} {{ $student->user->patronymic }}
                                            </span>
                                            <span class="widget-49-meeting-time"><b>Действителен до: {{ $sheet->before }}</b> </span>
                                        </div>
                                    </div>
                                    <ol class="widget-49-meeting-points">
                                        <li class="widget-49-meeting-item">
                                            <span>
                                                Дисциплина: {{ $sheet->individual->statement->lesson->discipline->title }},
                                                {{ $student->group->title }}, {{ $sheet->individual->statement->lesson->semester }} семестр
                                            </span></li>
                                    </ol>
                                    <div class="widget-49-meeting-action">
                                        Подписано деканом факультета
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

@endsection
