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
                        Обновление занятия у группы {{ $group->title }}
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
            <div class="row mt-4">
                <div class="col-12">
                    <form action="{{ route('personal.main.updateSchedule', $schedule->id) }}" method="POST" class="w-25">
                        @csrf
                        @method('PATCH')
                        <input value="{{ $group->id }}" type="hidden" name="group_id">
                        <div class="form-group">
                            <label>Тип недели</label>
                            <select name="week_type_id" class="form-control">
                                @foreach($week_types as $week_type)
                                <option value="{{$week_type->id }}" {{$week_type->id == $schedule->week_type_id ? 'selected' : ''}}>{{ $week_type->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>День недели</label>
                            <select name="day_id" class="form-control">
                                @foreach($days as $day)
                                <option value="{{$day->id }}" {{$day->id == $schedule->day_id ? 'selected' : ''}}>{{ $day->title }}</option>
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
                                <option value="{{$classroom->id }}" {{$classroom->id == $schedule->classroom_id ? 'selected' : ''}}>{{ $classroom->title }}</option>
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
<!-- /.content-wrapper -->
@endsection