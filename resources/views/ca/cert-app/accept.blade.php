@extends('ca.layouts.main')

@section('content')
    <style>
        .photoBorder {
            margin-top: 20px;
            width: 120px;
            height: 120px;
            background-repeat     : no-repeat;
            background-size       : cover;
            background-position-x : 50%;
            background-position-y : 50%;
        }
    </style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Выдача электронной подписи</h1>
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
                    <div class="col-md-10">
                        <form action="{{ route('ca.cert_app.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div><b>Информация о преподавателе:</b></div>
                                        <div class="mb-2">
                                            @if (isset($teacher->user->avatar))
                                                @php
                                                    $modelId = explode('/', $teacher->user->avatar)[0];
                                                    $mediaId = explode('/', $teacher->user->avatar)[2];
                                                    $filename = explode('/', $teacher->user->avatar)[3];
                                                @endphp
                                                <div style="
                                                    background-image: url({{ route('personal.settings.showImage', [$modelId, $mediaId, $filename]) }});
                                                    background-color: #000" class="photoBorder">
                                                </div>
                                            @else
                                                <div style="
                                                    background-image: url({{ asset('assets/default/personal_default_photo.jpg') }});
                                                    background-color: #000" class="photoBorder">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-user-information">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <span class="glyphicon glyphicon-asterisk text-primary"></span>
                                                            ID
                                                        </strong>
                                                    </td>
                                                    <td class="text-primary">
                                                        {{ $teacher->id  }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <span
                                                                class="glyphicon glyphicon-user  text-primary"></span>
                                                            ФИО
                                                        </strong>
                                                    </td>
                                                    <td class="text-primary">
                                                        {{ $teacher->user->surname  }}
                                                        {{ $teacher->user->name  }}
                                                        {{ $teacher->user->patronymic  }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <span
                                                                class="glyphicon glyphicon-user  text-primary"></span>
                                                            Паспортные данные
                                                        </strong>
                                                    </td>
                                                    <td class="text-primary">
                                                        {{ $data->pasport  }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <span
                                                                class="glyphicon glyphicon-user  text-primary"></span>
                                                            ИНН
                                                        </strong>
                                                    </td>
                                                    <td class="text-primary">
                                                        {{ $data->inn  }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <span
                                                                class="glyphicon glyphicon-user  text-primary"></span>
                                                            СНИЛС
                                                        </strong>
                                                    </td>
                                                    <td class="text-primary">
                                                        {{ $data->snils  }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <span
                                                                class="glyphicon glyphicon-eye-open text-primary"></span>
                                                            Преподаваемые дисциплины
                                                        </strong>
                                                    </td>
                                                    <td class="text-primary">
                                                        @foreach($teacher->disciplines->unique('id') as $dis)
                                                        {{ $dis->title }},
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <span
                                                                class="glyphicon glyphicon-envelope text-primary"></span>
                                                            Email
                                                        </strong>
                                                    </td>
                                                    <td class="text-primary">
                                                        {{ $teacher->user->email  }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <span
                                                                class="glyphicon glyphicon-calendar text-primary"></span>
                                                            Дата регистрации
                                                        </strong>
                                                    </td>
                                                    <td class="text-primary">
                                                        {{ $teacher->user->created_at  }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row mb-2" style="margin: 0">
                                                <i>Подтвердите выдаваемую подпись сертификатом УЦ
                                                    и нажмите на кнопку "Подписать":</i>
                                            </div>
                                            <div class="input-group mb-2">
                                                <div class="custom-file">
                                                    <!-- multiple -->
                                                    <input type="file" class="custom-file-input" name="private_key" accept=".key">
                                                    <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                                                </div>
                                            </div>
                                            @error('task')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-primary" value="Подписать">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <input type="hidden" value="{{ $certApp->id }}" name="certAppId">
                        </form>
                    </div>
                </div>


            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection
