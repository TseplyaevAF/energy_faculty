@extends('personal.layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/exam_sheets/style.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Задолженности</h1>
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
                <div class="form-group col-md-12">
                    <h5>Мои долги:</h5>
                    <table class="table table-hover text-wrap tableAdaptive">
                        <thead>
                        <tr>
                            <th>Ведомость от</th>
                            <th>Дисциплина</th>
                            <th>Преподаватель</th>
                            <th>Оценка</th>
                            <th>Дата сдачи</th>
                            <th>Допуск</th>
                        </tr>
                        </thead>
                        <tbody class="completed-sheets">
                        @foreach($individuals as $individual)
                            <tr>
                                <td>
                                    {{ $individual->statement->lesson->year->start_year }}-
                                    {{ $individual->statement->lesson->year->end_year }},
                                    {{ $individual->statement->lesson->semester }} семестр
                                </td>
                                <td>
                                    {{ $individual->statement->lesson->discipline->title }}
                                </td>
                                <td>
                                    {{ $individual->statement->lesson->teacher->user->surname }}
                                </td>
                                <td>
                                    {{ $evals[$individual->eval] }}
                                </td>
                                <td>
                                    {{ $individual->exam_finish_date }}
                                </td>
                                <td class="project-actions">
                                    @if (!isset($individual->exam_sheet))
                                        <form action="{{ route('personal.exam_sheet.store') }}" METHOD="POST">
                                            @csrf
                                            <input type="hidden" name="individual_id" value="{{ $individual->id }}">
                                            <button class="btn btn-info btn-sm">
                                                <i class="far fa-plus-square"></i>
                                                Получить
                                            </button>
                                        </form>
                                    @elseif (isset($individual->exam_sheet->dekan_signature))
                                        <a href="javascript:void(0)" data-toggle="modal"
                                           class="show btn btn-primary btn-sm"
                                           data-target="#sheet_{{ $individual->exam_sheet->id }}">
                                            Открыть
                                        </a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="sheet_{{ $individual->exam_sheet->id }}"
                                             style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Допуск</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card card-margin">
                                                                <div class="card-header no-border">
                                                                    <h5 class="card-title">Экзаменационный лист</h5>
                                                                </div>
                                                                <div class="card-body pt-0">
                                                                    <div class="widget-49">
                                                                        <div class="widget-49-title-wrapper">
                                                                            <div class="widget-49-date-primary">
                                                                    <span class="widget-49-date-day">
                                                                        {!! QrCode::size(70)->generate(route('personal.exam_sheet.show', $individual->exam_sheet->id)) !!}
                                                                    </span>
                                                                                <span class="widget-49-date-month"></span>
                                                                            </div>
                                                                            <div class="widget-49-meeting-info">
                                                                    <span class="widget-49-pro-title">
                                                                        <b>
                                                                        Студент: {{ $student->user->fullName() }}
                                                                        </b>
                                                                    </span>
                                                                                <span class="widget-49-meeting-time"><b>Действителен до: {{ $individual->exam_sheet->before }}</b> </span>
                                                                            </div>
                                                                        </div>
                                                                        <ol class="widget-49-meeting-points">
                                                                <span>
                                                                    <div>Дисциплина: {{ $individual->statement->lesson->discipline->title }}</div>
                                                                    <div>Группа: {{ $student->group->title }}, {{ $individual->statement->lesson->semester }} семестр</div>
                                                                </span>
                                                                        </ol>
                                                                        <div class="widget-49-meeting-action">
                                                                            Подписано деканом факультета
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                            Закрыть
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <i>Допуск скоро выдадут...</i>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
