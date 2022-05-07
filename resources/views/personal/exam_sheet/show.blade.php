@extends('personal.layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/exam_sheets/style.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Экзаменационный лист {{ $sheet->id  }}</h1>
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
                <div class="form-group col-md-4">
                    @if (session('error'))
                        <div class="alert alert-warning" role="alert">{!! session('error') !!}</div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('personal.exam_sheet.sign', $sheet) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-group col-md-5">
                                <label for="exampleInputFile">ФИО студента: </label>
                                <input value="{{ $studentFIO }}" type="text" class="form-control"
                                       placeholder="Фамилия" readonly>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="exampleInputFile">Группа: </label>
                                <input
                                    value="{{ $sheet->individual->statement->lesson->group->title }}, {{ $sheet->individual->statement->lesson->semester }} семестр"
                                    type="text" class="form-control"
                                    readonly>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="exampleInputFile">Контроль: </label>
                                <input
                                    value="{{ $sheet->individual->statement->lesson->discipline->title }}, {{ $controlForms[$sheet->individual->statement->control_form] }}"
                                    type="text" class="form-control" readonly>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="exampleInputFile">Результаты экзаменационной ведомости №{{ $sheet->individual->statement->id  }}:</label>
                                <input
                                    value="{{ $evalTypes[$sheet->individual->eval] }}"
                                    type="text" class="form-control"
                                    readonly>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="exampleInputFile">Оценка:</label>
                                <select name="eval" class="form-control">
                                    @foreach($evalTypes as $id => $evalType)
                                        <option value="{{ $id }}" {{$id == old('id') ? 'selected' : ''}}>{{ $evalType }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-5">
                                <h5>Выберите файл с Вашим секретным ключом:</h5>
                                <div class="custom-file">
                                    <input type="file" id="file" class="custom-file-input" name="private_key"
                                           accept=".key">
                                    <label class="custom-file-label" for="exampleInputFile">Выберите
                                        файл</label>
                                </div>
                            </div>

                            <div class="form-group col-md-5">
                                <button type="submit" class="btn btn-warning btn-sm btn-complete">
                                    Подписать
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
