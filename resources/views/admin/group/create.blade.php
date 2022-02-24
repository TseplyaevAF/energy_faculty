@extends('admin.layouts.main')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Добавление учебной группы</h1>
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
                        <form action="{{ route('admin.group.store') }}" method="POST" class="w-25">
                            @csrf
                            <div class="form-group">
                                <input value="{{ old('title') }}" type="text" class="form-control" name="title"
                                       id="group_title" placeholder="Название группы">
                                @error('title')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Выберите кафедру</label>
                                <select name="chair_id" class="form-control">
                                    @foreach($chairs as $chair)
                                        <option
                                            value="{{$chair->id }}" {{$chair->id == old('chair_id') ? 'selected' : ''}}>{{ $chair->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="submit" class="btn btn-primary" value="Добавить">
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
