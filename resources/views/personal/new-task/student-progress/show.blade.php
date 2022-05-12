<style>
    .titles {
        font-weight: bold;
        background-color: #86baa1;
        color: #043204;
    }
</style>

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
                <div class="mb-2">
                    <h6>Месяц</h6>
                    <div class="form-s2 selectGroup">
                        <select class="form-control formselect required" id="month_title">
                            @foreach($data['months'] as $numberMonth => $month)
                                <option value="{{ $numberMonth }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <label for="exampleInputFile">Выберите файл с таблицей успеваемости</label>
                <div class="input-group mb-2">
                    <div class="custom-file">
                        <input type="file" id="student_progress_file" class="custom-file-input" accept=".xlsx">
                        <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                    </div>
                </div>
                <blockquote>
                    <span class="text-muted" style="font-size: 14px">
                        Поддерживаются следующие форматы файлов: <b>.xlsx</b>
                        <i>не более 10МБ</i>
                    </span>
                </blockquote>
                <button type="button" class="btn btn-primary importStudentsProgress">
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
        <h6><b>Группа: </b>{{$lesson->group->title}}, {{$lesson->semester}} семестр, {{$lesson->year->getYear()}}</h6>
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
                <td rowspan="3" style="width: 25%"></td>
                <td colspan="10" style="font-weight: bold; text-align: center">Успеваемость по месяцам</td>
            </tr>
            </thead>
            <tbody class="student-progress-table">
                <tr>
                    <td></td>
                    @foreach($data['arrayMonths'] as $arrayMonth)
                        <td colspan="2" class="titles">{{$arrayMonth}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="font-weight: bold; text-align: center">ФИО</td>
                    @for ($i=0; $i<$data['monthsCount']; $i++)
                        <td style="width: 125px; background-color: rgba(236,217,120,0.84); font-weight: bold; color: #7d4a39">Кол-во пропусков</td>
                        <td style="width: 125px; background-color: #e2b51f; font-weight: bold; color: #7d4a39">Оценка за месяц</td>
                    @endfor
                </tr>
                @foreach($data['arrayStudentsProgress'] as $student => $months)
                <tr>
                    <td>{{ $student }}</td>
                    @foreach($months as $month => $monthData)
                        <td>{{$monthData['number_of_passes']}}</td>
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
