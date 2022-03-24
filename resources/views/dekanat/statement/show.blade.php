@extends('dekanat.layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dekanat/individual_history/style.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Ведомость № {{ $statement->id  }}</h1>
                        <input type="hidden" class="inputStatement" value="{{ $statement->id }}">
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
                @if (session('error'))
                    <div class="col-3 alert alert-danger" role="alert">{!! session('error') !!}</div>
                @endif
                <div class="card w-75">
                    <div class="card-header">
                        <div class="form-group">
                            Группа: {{ $statement->lesson->group->title  }},
                            семестр: {{ $statement->lesson->semester  }},
                            учебный год: {{ $statement->lesson->year->start_year  }}
                            -{{ $statement->lesson->year->end_year  }}
                        </div>
                        <div class="form-group">
                            Контроль:
                            {{ $statement->lesson->discipline->title }}, {{ $controlForms[$statement->control_form] }}
                        </div>
                        <div class="form-group">
                            Преподаватель: {{ $statement->lesson->teacher->user->surname }}
                        </div>
                        @if (isset($statement->start_date))
                            <div class="form-group">
                                Дата проведения экзамена: {{ date('d.m.Y', strtotime($statement->start_date) ) }}
                            </div>
                        @endif
                        <div class="form-group">
                            Дата сдачи ведомости: {{ date('d.m.Y', strtotime($statement->finish_date)) }}
                        </div>
                        @if ($statement->finish_date < now())
                            @if (isset($statement->report))
                                <div class="form-group">
                                    Скачать отчёт:
                                    <a href="{{ route('dekanat.statement.download', $statement->id) }}"
                                       class="btn btn-success btn-sm">
                                        {{$statement->report}}
                                    </a>
                                </div>
                            @else
                                <div class="form-group">
                                    <a href="{{ route('dekanat.statement.export', $statement->id) }}"
                                       class="btn btn-success">
                                        Сгенерировать отчёт
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <h5>Студенты, прошедшие контроль:</h5>
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>№ записи</th>
                                    <th>ФИО</th>
                                    <th>№ зачетной книжки</th>
                                    <th>Оценка</th>
                                    <th>Дата последней сдачи</th>
                                    <th>История сдач</th>
                                </tr>
                                </thead>
                                <tbody class="completed-sheets">
                                @foreach( $individuals as $individual)
                                    <tr>
                                        <td>{{ $individual['id'] }}</td>
                                        <td>{{ $individual['studentFIO'] }}</td>
                                        <td>{{ $individual['student_id_number'] }}</td>
                                        <td>{{ $evalTypes[$individual['evaluation']] }}</td>
                                        <td>{{ $individual['exam_finish_date'] }}</td>
                                        <td>
                                            @if (isset($individual['history']))
                                                <a href="javascript:void(0)" data-toggle="modal"
                                                   class="show btn btn-primary btn-sm"
                                                   data-target="#history_{{ $individual['id'] }}">
                                                    Открыть
                                                </a>
                                                <div class="modal fade" id="history_{{$individual['id']}}" role="dialog"
                                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">История
                                                                    сдач</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="col-md-12">
                                                                    <div id="content">
                                                                        <ul class="timeline">
                                                                            @foreach(json_decode($individual['history'], true) as $individual)
                                                                                <li class="event">
                                                                                    <p><small>Дата
                                                                                            сдачи: </small>{{ date('d.m.Y', strtotime($individual['exam_finish_date'])) }}
                                                                                    </p>
                                                                                    <p>
                                                                                        <b>Оценка: </b>{{ $individual['eval'] }}
                                                                                    </p>
                                                                                    <p><b>Экзамен
                                                                                            принял: </b>{{ $individual['teacher'] }}
                                                                                    </p>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Закрыть
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script>
    </script>
@endsection
