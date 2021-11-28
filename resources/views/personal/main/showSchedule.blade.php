@extends('personal.layouts.main')

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
        <div class="form-group w-25">
            @if (session('success'))
            <div class="col-12 alert alert-success" role="alert">{!! session('success') !!}</div>
            @endif
        </div>
            @can('index-homework')
            <div class="schedule__title">
                <h1 class="schedule__title-h1">
                    <strong>{{ $group->title }}</strong>
                </h1>
            </div>
            <small class="schedule__title-week-type"><span class="uniform-bg"><span>курс: {{ $group->course }}, семестр: {{ $group->semester }}</span></span></small></strong>
            @endcan
            @can('index-task')
            <div class="schedule__title">
                <h5 class="schedule__title-h1">
                    <strong>Преподаватель: {{ auth()->user()->surname }} {{ auth()->user()->name }} {{ auth()->user()->patronymic }}</strong>
                </h5>
            </div>
            @endcan
            <div class="row mt-2">
                <div class="col-8">
                    <table class="table table-bordered text-wrap">
                        <thead class="mt-3">
                            <tr>
                                <th scope="col" class="col-1">День</th>
                                <th scope="col" class="col-1">Время</th>
                                <th scope="col" class="col-4">Чётная неделя</th>
                                <th scope="col" class="col-4">Нечётная неделя</th>
                            </tr>
                        </thead>
                        @foreach ($days as $day)
                            <tbody>
                                <tr>
                                    <td rowspan="7">
                                        <div class="schedule__table-day">
                                            <h2><strong>{{$day->title}}</strong></h2>
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
                                            @if (($pairEven->class_time_id == $time->id) && ($pairEven->day_id == $day->id))
                                                <td class="schedule__table-discipline">
                                                    <div class="row">
                                                        {{ $pairEven->discipline->title }} ·&nbsp
                                                        @can('index-homework')
                                                            <a class="schedule__table-teacher" href="#">{{ $pairEven->teacher->user->surname }} {{ mb_substr($pairEven->teacher->user->name, 0, 1) }}. {{ mb_substr($pairEven->teacher->user->patronymic, 0, 1)}}.</a>&nbsp·&nbsp
                                                        @elsecan('index-task')
                                                            <a class="schedule__table-group" href="#">{{ $pairEven->group->title }}</a>&nbsp·&nbsp
                                                        @endcan
                                                        <p class="schedule__table-class-type text-muted"> {{ $pairEven->class_type->title }} </p>&nbsp·&nbsp
                                                        <p class="schedule__table-classroom">(ауд. {{$pairEven->classroom->title}}) </p>&nbsp
                                                        @can('edit-schedule')
                                                        <a href="{{ route('personal.main.editSchedule', $pairEven->id) }}" class="text-success"><i class="far fa-edit"></i></a>
                                                        @endcan
                                                    </div>
                                                </td>
                                                @php
                                                    $firstColumn = true;
                                                @endphp
                                                @foreach ($scheduleOdd as $pairOdd)    
                                                    @if (($pairOdd->class_time_id == $time->id) && ($pairOdd->day_id == $day->id))
                                                        <td class="schedule__table-discipline">
                                                            <div class="row">
                                                                {{$pairOdd->discipline->title}} ·&nbsp
                                                                @can('index-homework')
                                                                <a class="schedule__table-teacher" href="#">{{ $pairOdd->teacher->user->surname }} {{ mb_substr($pairOdd->teacher->user->name, 0, 1) }}. {{ mb_substr($pairOdd->teacher->user->patronymic, 0, 1)}}.</a>&nbsp·&nbsp
                                                                @elsecan('index-task')
                                                                    <a class="schedule__table-group" href="#">{{ $pairOdd->group->title }}</a>&nbsp·&nbsp
                                                                @endcan
                                                                <p class="schedule__table-class-type text-muted"> {{ $pairOdd->class_type->title }} </p>&nbsp·&nbsp
                                                                <p class="schedule__table-classroom">(ауд. {{$pairOdd->classroom->title}})</p>&nbsp
                                                                @can('edit-schedule')
                                                                <a href="{{ route('personal.main.editSchedule', $pairOdd->id) }}" class="text-success"><i class="far fa-edit"></i></a>
                                                                @endcan
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
                                                @if (($pairOdd->class_time_id == $time->id) && ($pairOdd->day_id == $day->id))
                                                    <td class="schedule__table-discipline">
                                                        <div class="row">
                                                            {{$pairOdd->discipline->title}} ·&nbsp
                                                            @can('index-homework')
                                                            <a class="schedule__table-teacher" href="#">{{ $pairOdd->teacher->user->surname }} {{ mb_substr($pairOdd->teacher->user->name, 0, 1) }}. {{ mb_substr($pairOdd->teacher->user->patronymic, 0, 1)}}.</a>&nbsp·&nbsp
                                                            @elsecan('index-task')
                                                                <a class="schedule__table-group" href="#">{{ $pairOdd->group->title }}</a>&nbsp·&nbsp
                                                            @endcan
                                                            <p class="schedule__table-class-type text-muted"> {{ $pairOdd->class_type->title }} </p>&nbsp·&nbsp
                                                            <p class="schedule__table-classroom">(ауд. {{$pairOdd->classroom->title}})</p>&nbsp
                                                            @can('edit-schedule')
                                                            <a href="{{ route('personal.main.editSchedule', $pairOdd->id) }}" class="text-success"><i class="far fa-edit"></i></a>
                                                            @endcan
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

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('js/schedules/showGroups.js') }}"></script>
<!-- /.content-wrapper -->
@endsection