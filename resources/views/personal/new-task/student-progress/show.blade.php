{{--<input value="{{ $data['lesson_id'] }}" type="hidden" name="lesson_id">--}}

{{--Модальное окно для импорта успеваемости студентов из Excel файла--}}
<div class="modal fade" id="importStudentsProgressModal" tabindex="-1" role="dialog"
     aria-labelledby="importStudentsProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">Добавление успеваемости студентов</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="exampleInputFile">Выберите файл с таблицей успеваемости</label>
                <div class="input-group mb-2">
                    <div class="custom-file">
                        <input type="file" id="file" class="custom-file-input" name="student_progress" accept=".xlsx">
                        <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                    </div>
                </div>
                <blockquote>
                    <span class="text-muted" style="font-size: 14px">
                        Поддерживаются следующие форматы файлов: <b>.xlsx</b>
                        <i>не более 10МБ</i>
                    </span>
                </blockquote>
                <button type="button" id="importStudentsProgress" class="btn btn-primary importStudentsProgress">
                    Сохранить
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <div class="form-group">
        <h5><b>{{$lesson->discipline->title}}</b></h5>
        <h6><b>Группа: </b>{{$lesson->group->title}}, {{$lesson->semester}} семестр</h6>
    </div>
    <div class="mb-2">
        <a href="javascript:void(0)" data-toggle="modal"
           class="show btn btn-primary"
           data-target="#importStudentsProgressModal">
            Загрузить успеваемость
        </a>
    </div>

    <div class="form-group scroll-table-body">
        <table class="table table-bordered table-hover tableAdaptive">
            <thead>
            <tr>
                <th rowspan="3" style="width: 25%">ФИО</th>
                <th colspan="10">Успеваемость по месяцам</th>
            </tr>
            </thead>
            <tbody class="student-progress-table">
                <tr>
                    <td></td>
                    @foreach($data['arrayMonths'] as $arrayMonth)
                        <td colspan="2">{{$arrayMonth}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td></td>
                    @for ($i=0; $i<$data['monthsCount']; $i++)
                        <td>Кол-во пропусков</td>
                        <td>Оценка за месяц</td>
                    @endfor
                </tr>
                @foreach($data['arrayStudentsProgress'] as $student => $months)
                <tr>
                    <td>{{ $student }}</>
                    @foreach($months as $month => $monthData)
                        <td>{{$monthData['number_of_debts']}}</td>
                        <td>{{$monthData['mark']}}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="https://getbootstrap.com/docs/4.5/assets/js/docs.min.js"></script>
<script src="{{ asset('js/personal/new-task/studentProgress.js') }}"></script>
