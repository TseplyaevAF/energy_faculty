@extends('employee.layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css\schedule\style.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                    <a href="{{ route('employee.schedule.index') }}"><i class="fas fa-chevron-left"></i></a>
                        Расписание занятий
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="form-group col-md-4">
                @if (session('error'))
                    <div class="alert alert-warning" role="alert">{!! session('error') !!}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{!! session('success') !!}</div>
                @endif
            </div>
            <div class="schedule__import mb-2 col-md-4">
                <form action="{{ route('employee.schedule.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <label>Загрузить расписание из Excel</label>
                    <div class="form-group">
                        <h6>Семестр</h6>
                        <select name="semester" class="form-control formselect required">
                            @for($i=1; $i<=8; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="excel_file" class="custom-file-input" accept=".xlsx">
                            <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                        </div>
                        @error('excel_file')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                    <input type="submit" class="btn btn-outline-info" value="Загрузить">
                </form>
            </div>
            <div class="schedule__title">
                <h1 class="schedule__title-h1">
                    <strong>{{ $group->title }}</strong>
                </h1>
            </div>
            <small class="schedule__title-week-type"><span class="uniform-bg"><span>курс: {{ $studyPeriod[0] }}, семестр: {{ $studyPeriod[1] }}</span></span></small>
            <div class="row mt-2">
                <div class="col-md-8">
                    <table class="table table-bordered text-wrap">
                        <thead class="mt-3">
                            <tr class="schedule__titles">
                                <th scope="col" class="col-1">День</th>
                                <th scope="col" class="col-1">Время</th>
                                <th scope="col" class="col-4">Верхняя неделя</th>
                                <th scope="col" class="col-4">Нижняя неделя</th>
                            </tr>
                        </thead>
                        @foreach ($days as $day_id =>  $day)
                            <tbody>
                                <tr>
                                    <td rowspan="7">
                                        <div class="schedule__table-day">
                                            <h2><strong>{{$day}}</strong></h2>
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($class_times as $time)
                                    <tr>
                                        @php
                                            $firstColumn = false;
                                            $secondColumn = false;
                                        @endphp
                                        <th class="schedule__table-time" scope="row">{{ date("H:i", strtotime($time->start_time)) }}-{{ date("H:i", strtotime($time->end_time)) }}</th>
                                        @foreach ($scheduleEven as $pairEven)
                                            @if (($pairEven->class_time_id == $time->id) && ($pairEven->day == $day_id))
                                                <td class="schedule__table-discipline">
                                                    <div class="row">
                                                        {{ $pairEven->lesson->discipline->title }} ·&nbsp
                                                        <a class="schedule__table-teacher" href="#">{{ $pairEven->lesson->teacher->user->surname }} {{ mb_substr($pairEven->lesson->teacher->user->name, 0, 1) }}. {{ mb_substr($pairEven->lesson->teacher->user->patronymic, 0, 1)}}.</a>&nbsp·&nbsp
                                                        <p class="schedule__table-class-type"> {{ $pairEven->class_type->title }} </p>&nbsp·&nbsp
                                                        <p class="schedule__table-classroom">(ауд. {{$pairEven->classroom->corps}}-{{$pairEven->classroom->cabinet}}) </p>&nbsp
                                                        <a href="{{ route('employee.schedule.group.edit', $pairEven->id) }}" class="text-success"><i class="far fa-edit"></i></a>
                                                    </div>
                                                </td>
                                                @php
                                                    $firstColumn = true;
                                                @endphp
                                                @foreach ($scheduleOdd as $pairOdd)
                                                    @if (($pairOdd->class_time_id == $time->id) && ($pairOdd->day == $day_id))
                                                        <td class="schedule__table-discipline">
                                                            <div class="row">
                                                                {{$pairOdd->lesson->discipline->title}} ·&nbsp
                                                                <a class="schedule__table-teacher" href="#">{{ $pairOdd->lesson->teacher->user->surname }} {{ mb_substr($pairOdd->lesson->teacher->user->name, 0, 1) }}. {{ mb_substr($pairOdd->lesson->teacher->user->patronymic, 0, 1)}}.</a>&nbsp·&nbsp
                                                                <p class="schedule__table-class-type"> {{ $pairOdd->class_type->title }} </p>&nbsp·&nbsp
                                                                <p class="schedule__table-classroom">(ауд. {{$pairOdd->classroom->corps}}-{{$pairOdd->classroom->cabinet}})</p>&nbsp
                                                                <a href="{{ route('employee.schedule.group.edit', $pairOdd->id) }}" class="text-success"><i class="far fa-edit"></i></a>
                                                            </div>
                                                        </td>
                                                        @php
                                                            $secondColumn = true;
                                                        @endphp
                                                        @break
                                                    @endif
                                                @endforeach
                                                @if (!$secondColumn)
                                                    <td></td>
                                                @endif
                                                @break
                                            @endif
                                        @endforeach
                                        @if ($firstColumn)
                                            @continue
                                        @else
                                            <td></td>
                                            @foreach ($scheduleOdd as $pairOdd)
                                                @if (($pairOdd->class_time_id == $time->id) && ($pairOdd->day == $day_id))
                                                    <td class="schedule__table-discipline">
                                                        <div class="row">
                                                            {{$pairOdd->lesson->discipline->title}} ·&nbsp
                                                            <a class="schedule__table-teacher" href="#">{{ $pairOdd->lesson->teacher->user->surname }} {{ mb_substr($pairOdd->lesson->teacher->user->name, 0, 1) }}. {{ mb_substr($pairOdd->lesson->teacher->user->patronymic, 0, 1)}}.</a>&nbsp·&nbsp
                                                            <p class="schedule__table-class-type"> {{ $pairOdd->class_type->title }} </p>&nbsp·&nbsp
                                                            <p class="schedule__table-classroom">(ауд. {{$pairOdd->classroom->corps}}-{{$pairOdd->classroom->cabinet}})</p>&nbsp
                                                            <a href="{{ route('employee.schedule.group.edit', $pairOdd->id) }}" class="text-success"><i class="far fa-edit"></i></a>
                                                        </div>
                                                    </td>
                                                    @php
                                                        $secondColumn = true;
                                                    @endphp
                                                    @break
                                                @endif
                                            @endforeach
                                            @if (!$secondColumn)
                                                <td></td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
