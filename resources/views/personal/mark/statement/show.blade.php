<link rel="stylesheet" href="{{ asset('css/dekanat/individual_history/style.css') }}">

<div class="card card-margin">
    <h5>Экзаменационная ведомость №{{$statement->id}}</h5>
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
            <table class="table table-bordered table-responsive" id="individuals-table">
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
                        <a type="button" class="btn btn-primary btn-sm mb-2 showHistory"
                        id="{{$individual['id']}}">
                            Открыть
                        </a>
                        <div class="form-group history" id="history_{{$individual['id']}}" style="display: none;">
                            <ul class="timeline" >
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
            const url = '{{ route('personal.mark.statementDownload', ':id') }}';
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                type: 'GET',
                url: url.replace(':id', $(this).attr('id')),
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

        $(".showHistory").on('click', function() {
            $('#history_'+ $(this).attr('id')).slideToggle(300);
            return false;
        });
    });
</script>
