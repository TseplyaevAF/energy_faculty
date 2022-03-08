@extends('admin.layouts.main')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Изменение учебной группы {{ $group->title }}</h1>
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
                @if (session('error'))
                    <div class="col-3 alert alert-warning" role="alert">{!! session('error') !!}</div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('admin.group.update', $group->id) }}" method="POST" class="w-25">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <input value="{{ $group->title }}" type="text" class="form-control" name="title"
                                       id="title" placeholder="Название группы">
                                @error('title')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group w-50">
                                <label>Выберите старосту группы</label>
                                <select name="student_id" class="form-control">
                                    @if (empty($students->all()))
                                        <option value="">Студенты не найдены</option>
                                    @else
                                        <option value="">-- Староста не выбран</option>
                                        @foreach($students as $student)
                                            @if (!empty($headman))
                                                <option
                                                    value="{{ $student->id }}" {{ $student->id == $headman->id ? 'selected' : ''}} > {{ $student->user->surname }}</option>
                                            @else
                                                <option
                                                    value="{{ $student->id }}" {{ $student->id == $group->student_id ? 'selected' : ''}} > {{ $student->user->surname }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('student_id')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Выберите кафедру</label>
                                <select name="chair_id" class="form-control">
                                    @foreach($chairs as $chair)
                                        <option
                                            value="{{$chair->id }}" {{$chair->id == $group->chair_id ? 'selected' : ''}}>{{ $chair->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="submit" class="btn btn-primary" value="Сохранить">
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
