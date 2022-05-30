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

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('personal.cert.store') }}" method="POST"
                              novalidate>
                            @csrf
                            @if (isset(auth()->user()->teacher->certificate))
                                <div class="col-md-6 mb-2"
                                     style="background: #273667; padding: 10px; border-radius: 5px">
                                    <label class="form-label" style="color: white">Причина отправки заявки:</label>
                                    <select name="reason" class="form-control">
                                        @foreach($reasons as $key => $reason)
                                            <option value="{{ $key }}">{{ $reason }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group col-md-3 mb-2">
                                    <label for="validationCustom01" class="form-label">Фамилия</label>
                                    <input value="{{ auth()->user()->surname }}" type="text" class="form-control"
                                           id="validationCustom01" placeholder="Фамилия" required>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                    <label for="validationCustom02" class="form-label">Имя</label>
                                    <input value="{{ auth()->user()->name }}" type="text" class="form-control"
                                           id="validationCustom02" placeholder="Имя" required>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                    <label for="validationCustom02" class="form-label">Отчество</label>
                                    <input value="{{ auth()->user()->patronymic }}" type="text" class="form-control"
                                           placeholder="Отчество" required>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label for="validationCustom03" class="form-label">ИНН</label>
                                <input type="text" class="form-control mask-inn-individual" id="validationCustom03"
                                       required name="data[inn]">
                                <div class="invalid-feedback">
                                    Укажите Ваш ИНН
                                </div>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label for="validationCustom05" class="form-label">Паспортные данные</label>
                                <input type="text" class="form-control pasport" id="validationCustom05"
                                       required name="data[pasport]">
                                <div class="invalid-feedback">
                                    Укажите серию и номер паспорта
                                </div>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label for="validationCustom05" class="form-label">СНИЛС</label>
                                <input type="text" class="form-control mask-snils" id="validationCustom05"
                                       required name="data[snils]">
                                <div class="invalid-feedback">
                                    Укажите Ваш СНИЛС
                                </div>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label for="validationCustom01" class="form-label">E-mail</label>
                                <input value="{{ auth()->user()->email }}" type="text" class="form-control"
                                       id="validationCustom01" placeholder="E-mail" required>
                            </div>
                            <input type="hidden" name="teacher_id" value="{{ auth()->user()->teacher->id }}">
                            <div class="sendApp col-md-12 mb-2">
                                <button class="btn btn-primary" type="submit">Отправить заявку</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>



    <!-- /.content-wrapper -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.sendApp').click(function () {
                if(!confirm('Пожалуйста, проверьте, что все данные введены корректно' + '\n' + 'Отправить заявку?')){
                    return false;
                }
            });
        })
    </script>
@endsection
