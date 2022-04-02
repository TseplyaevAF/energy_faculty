@extends('admin.layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css\schedule\style.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                    <a href="{{ route('admin.schedule.group.show', $group->id) }}"><i class="fas fa-chevron-left"></i></a>
                        Добавление занятия
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
                    <form action="{{ route('admin.schedule.group.store') }}" method="POST" class="w-25">
                        @csrf
                        <input value="{{ $group->id }}" type="hidden" name="group_id">
                        <div class="form-group">
                            <label>Тип недели</label>
                            <select name="week_type" class="form-control">
                                @foreach($data['week_types'] as $number => $week_type)
                                <option value="{{ $number }}" {{ $number == old('week_type') ? 'selected' : ''}}>{{ $week_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>День недели</label>
                            <select name="day" class="form-control">
                                @foreach($data['days'] as $number => $day)
                                <option value="{{ $number }}" {{ $number == old('day') ? 'selected' : ''}}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Время занятия</label>
                            <select name="class_time_id" class="form-control">
                                @foreach($data['class_times'] as $class_time)
                                <option value="{{$class_time->id }}" {{$class_time->id == old('class_time_id') ? 'selected' : ''}}>{{ date("H:i", strtotime($class_time->start_time)) }}-{{ date("H:i", strtotime($class_time->end_time)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Нагрузка</label>
                            <select name="lesson_id" class="form-control">
                                @foreach($data['lessons'] as $lesson)
                                <option value="{{$lesson->id }}" {{$lesson->id == old('lesson_id') ? 'selected' : ''}}>
                                    {{ $lesson->teacher->user->surname }},
                                    {{ $lesson->discipline->title }},
                                    {{ $lesson->semester }} семестр
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Тип занятия</label>
                            <input value="{{ old('class_type_id') }}" type="text" class="form-control"
                                   name="class_type_id" id="class_type" placeholder="практика">
                            @error('class_type_id')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Аудитория</label>
                            <input value="{{ old('classroom_id') }}" type="text" id="classroom"
                                   autocomplete="off" class="form-control"
                                   name="classroom_id" placeholder="03-401">
                            @error('classroom_id')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                        <input type="submit" class="btn btn-primary mb-2" value="Добавить">
                    </form>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/schedules/showGroups.js') }}"></script>
<!-- /.content-wrapper -->
@endsection
