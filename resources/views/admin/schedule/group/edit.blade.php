@extends('admin.layouts.main')

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
                        <a href="{{ route('admin.schedule.group.show', $group->id) }}"><i class="fas fa-chevron-left"></i></a>
                        Обновление занятия
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
        @if (session('error'))
        <div class="col-3 alert alert-warning" role="alert">{!! session('error') !!}</div>
        @endif
            <div class="schedule__title">
                <h1 class="schedule__title-h1">
                    <strong>{{ $group->title }}</strong>
                </h1>
            </div>
            <small class="schedule__title-week-type"><span class="uniform-bg"><span>курс: {{ $group->course }}, семестр: {{ $group->semester }}</span></span></small></strong>
            <div class="row mt-4">
                <div class="col-12">
                    <form action="{{ route('admin.schedule.group.update', $schedule->id) }}" method="POST" class="w-25">
                        @csrf
                        @method('PATCH')
                        <input value="{{ $group->id }}" type="hidden" name="group_id">
                        <div class="form-group">
                            <label>Тип недели</label>
                            <select name="week_type" class="form-control">
                                @foreach($week_types as $week_type_number => $week_type)
                                <option value="{{ $week_type_number }}" {{ $week_type_number == $schedule->week_type ? 'selected' : ''}}>{{ $week_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>День недели</label>
                            <select name="day" class="form-control">
                                @foreach($days as $day_number => $day)
                                <option value="{{ $day_number }}" {{ $day_number == $schedule->day ? 'selected' : ''}}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Время занятия</label>
                            <select name="class_time_id" class="form-control">
                                @foreach($class_times as $class_time)
                                <option value="{{$class_time->id }}" {{$class_time->id == $schedule->class_time_id ? 'selected' : ''}}>{{ date("H:i", strtotime($class_time->start_time)) }}-{{ date("H:i", strtotime($class_time->end_time)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Дисциплина</label>
                            <select name="discipline_id" class="form-control">
                                @foreach($disciplines as $discipline)
                                <option value="{{$discipline->id }}" {{$discipline->id == $schedule->discipline_id ? 'selected' : ''}}>{{ $discipline->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Преподаватель</label>
                            <select name="teacher_id" class="form-control">
                                @foreach($teachers as $teacher)
                                <option value="{{$teacher->id }}" {{$teacher->id == $schedule->teacher_id ? 'selected' : ''}}>{{ $teacher->user->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Вид занятия</label>
                            <select name="class_type_id" class="form-control">
                                @foreach($class_types as $class_type)
                                <option value="{{$class_type->id }}" {{$class_type->id == $schedule->class_type_id ? 'selected' : ''}}>{{ $class_type->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Аудитория</label>
                            <select name="classroom_id" class="form-control">
                                @foreach($classrooms as $classroom)
                                <option value="{{$classroom->id }}" {{$classroom->id == $schedule->classroom_id ? 'selected' : ''}}>
                                    {{ $classroom->corps }}-{{ $classroom->cabinet }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary mb-2" value="Сохранить">
                    </form>
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
