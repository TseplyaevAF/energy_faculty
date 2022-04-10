<link rel="stylesheet" href="{{ asset('css/dekanat/individual_history/style.css') }}">
<style>
    .scroll-table-body {
        height: 300px;
        overflow-x: auto;
        margin-top: 0px;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .scroll-table table {
        width: 100%;
        table-layout: fixed;
        border: none;
    }

    .scroll-table thead th {
        font-weight: bold;
        text-align: left;
        border: none;
        padding: 10px 15px;
        background: #d8d8d8;
        font-size: 14px;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }

    .scroll-table tbody td {
        text-align: left;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
        padding: 10px 15px;
        font-size: 14px;
        vertical-align: top;
    }

    .scroll-table tbody tr:nth-child(even) {
        background: #f3f3f3;
    }

    /* Стили для скролла */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    }

    ::-webkit-scrollbar-thumb {
        box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="card card-margin">
    @if (session('success'))
        <div class="col-3 alert alert-success" role="alert">{!! session('success') !!}</div>
    @endif
    @if (session('error'))
        <div class="col-3 alert alert-danger" role="alert">{!! session('error') !!}</div>
    @endif
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
        <div class="form-group">
            <button type="button" id="{{ $statement->id }}"
                    class="btn btn-success btn-sm statementReportDownload">
                Скачать отчёт в excel
            </button>
        </div>
    </div>
    <div class="card-body">
        <h5>Студенты, прошедшие контроль:</h5>
        <div class="form-group scroll-table-body">
            <table class="table table-bordered table-responsive">
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
                        <td style="min-width:100px">{{ $individual['id'] }}</td>
                        <td>{{ $individual['studentFIO'] }}</td>
                        <td>{{ $individual['student_id_number'] }}</td>
                        <td>{{ $evalTypes[$individual['evaluation']] }}</td>
                        <td>{{ $individual['exam_finish_date'] }}</td>
                        <td style="min-width:100px">
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

<script>
    $(document).ready(function () {
        $('.statementReportDownload').on('click', function () {
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                type: 'GET',
                url: 'marks/statements/' + $(this).attr('id') + '/download',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                success: function (data) {
                    const blob = new Blob([data], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Экзаменационная ведомость.xlsx";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            });
        })
    });
</script>
