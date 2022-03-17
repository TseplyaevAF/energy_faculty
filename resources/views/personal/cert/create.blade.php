@extends('personal.layouts.main')

@section('title-block')Отправка заявки на получение сертификата@endsection

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" xmlns="http://www.w3.org/1999/html">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Заявка на получение электронной подписи</h1>
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

                <div class="row w-50">
                    <div class="col-12">
                        <form action="{{ route('personal.cert.store') }}" method="POST" class="row g-3 needs-validation"
                              novalidate>
                            @csrf
                            <div class="col-md-4">
                                <label for="validationCustom01" class="form-label">Фамилия</label>
                                <input value="{{ auth()->user()->surname }}" type="text" class="form-control"
                                       id="validationCustom01" placeholder="Фамилия" required>
                                <div class="valid-feedback">
                                    Все хорошо!
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Имя</label>
                                <input value="{{ auth()->user()->name }}" type="text" class="form-control"
                                       id="validationCustom02" placeholder="Имя" required>
                                <div class="valid-feedback">
                                    Все хорошо!
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Отчество</label>
                                <input value="{{ auth()->user()->patronymic }}" type="text" class="form-control"
                                       placeholder="Отчество" required>
                                <div class="valid-feedback">
                                    Все хорошо!
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="validationCustom03" class="form-label">ИНН</label>
                                <input type="text" class="form-control mask-inn-individual" id="validationCustom03"
                                       required name="data[inn]">
                                <div class="invalid-feedback">
                                    Укажите Ваш ИНН
                                </div>
                            </div>
                            <div class="col-md-3 mt-3 form-group">
                                <label for="validationCustom05" class="form-label">Паспортные данные</label>
                                <input type="text" class="form-control pasport" id="validationCustom05"
                                       required name="data[pasport]">
                                <div class="invalid-feedback">
                                    Укажите серию и номер паспорта
                                </div>
                            </div>
                            <div class="col-md-3 mt-3 form-group">
                                <label for="validationCustom05" class="form-label">СНИЛС</label>
                                <input type="text" class="form-control mask-snils" id="validationCustom05"
                                       required name="data[snils]">
                                <div class="invalid-feedback">
                                    Укажите серию и номер паспорта
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom01" class="form-label">E-mail</label>
                                <input value="{{ auth()->user()->email }}" type="text" class="form-control"
                                       id="validationCustom01" placeholder="E-mail" required>
                                <div class="valid-feedback">
                                    Все хорошо!
                                </div>
                            </div>
                            <input type="hidden" name="teacher_id" value="{{ auth()->user()->teacher->id }}">
                            <div class="col-12 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                    <label class="form-check-label" for="invalidCheck">
                                        Примите условия и соглашения
                                    </label>
                                    <div class="invalid-feedback md-2">
                                        Вы должны принять перед отправкой.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Отправить форму</button>
                            </div>
                        </form>
                        {{--                        <form action="{{ route('personal.cert.store') }}" method="POST">--}}
                        {{--                            @csrf--}}
                        {{--                            <label for="exampleInputFile">Личные данные</label>--}}
                        {{--                            <div class="form-group w-25">--}}
                        {{--                                <input value="{{ auth()->user()->surname }}" type="text" class="form-control"--}}
                        {{--                                       placeholder="Фамилия" readonly>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="form-group w-25">--}}
                        {{--                                <input value="{{ auth()->user()->name }}" type="text" class="form-control"--}}
                        {{--                                       placeholder="Имя" readonly>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="form-group w-25">--}}
                        {{--                                <input value="{{ auth()->user()->patronymic }}" type="text" class="form-control"--}}
                        {{--                                       placeholder="Отчество" readonly>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="form-group w-25">--}}
                        {{--                                <input value="{{ auth()->user()->email }}" type="text" class="form-control"--}}
                        {{--                                       placeholder="Email" readonly>--}}
                        {{--                            </div>--}}
                        {{--                            <input type="hidden" name="teacher_id" value="{{ auth()->user()->teacher->id }}">--}}

                        {{--                            <div class="form-group">--}}
                        {{--                                <button type="submit" class="btn btn-warning btn-sm btn-complete">--}}
                        {{--                                    Отправить заявку--}}
                        {{--                                </button>--}}
                        {{--                            </div>--}}
                        {{--                        </form>--}}
                    </div>
                </div>


            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>



    <!-- /.content-wrapper -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/personal/cert/complete.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/personal/cert/create_app.js') }}"></script>
@endsection
