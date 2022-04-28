@extends('personal.layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/personal/settings/style.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="container light-style flex-grow-1 container-p-y">
                    <h4 class="font-weight-bold py-3 mb-4">Настройки аккаунта</h4>
                    <div class="card overflow-hidden">
                        <div class="row no-gutters row-bordered row-border-light">
                            <div class="col-md-3 pt-0">
                                <div class="list-group list-group-flush account-settings-links">
                                    <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">Основные</a>
                                    <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Изменить пароль</a>
                                    <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-security">Безопасность</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tab-content">
                                    @if (session('error'))
                                        <div class="col-12 alert alert-warning" role="alert">{!! session('error') !!}</div>
                                    @endif

                                    @if (session('success'))
                                    <div class="col-12 alert alert-success" role="alert">{!! session('success') !!}</div>
                                    @endif
                                    <!-- GENERAL SETTINGS -->
                                    <form class="tab-pane fade active show" id="account-general" action="{{ route('personal.settings.updateMain', $user->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <div class="card-body media align-items-center">
                                                @if (isset($user->avatar))
                                                    @php
                                                        $modelId = explode('/', $user->avatar)[0];
                                                        $mediaId = explode('/', $user->avatar)[2];
                                                        $filename = explode('/', $user->avatar)[3];
                                                    @endphp
                                                    <img src="{{ route('personal.settings.showImage', [$modelId, $mediaId, $filename]) }}" class="d-block ui-w-80" id="blah">
                                                @else
                                                    <img src="{{ asset('assets/default/personal_default_photo.jpg') }}" class="d-block ui-w-80" id="blah">
                                                @endif
                                                <div class="media-body ml-4">
                                                    <label class="btn btn-outline-primary">
                                                        Загрузить новое фото
                                                        <input name="avatar" type="file" class="account-settings-fileinput" accept=".jpg,.jpeg,.png,.bmp,.svg" id="imgInp">
                                                    </label> &nbsp;
                                                    <button type="button" class="btn btn-default md-btn-flat" id="deletePhoto">Удалить</button>
                                                </div>
                                                    <input type="hidden" name="no_photo" id="noPhoto">
                                                @error('avatar')
                                                <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <hr class="border-light m-0">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="form-label">Номер телефона</label>
                                                    <input name="phone_number" id="phone" type="tel" class="form-control mb-1" value="{{ $user->phone_number }}">
                                                    @error('phone_number')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">E-mail</label>
                                                    <input name="email" type="text" class="form-control mb-1" value="{{ $user->email }}">
                                                    @error('email')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <input value="{{ $user->id }}" class="form-control" type="hidden" name="user_id">
                                        <input type="submit" class="btn btn-primary mb-2" value="Сохранить">
                                    </form>
                                    <!-- PASSWORD SETTINGS -->
                                    <form class="tab-pane fade" id="account-change-password" action="{{ route('personal.settings.updatePassword', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <div class="card-body pb-2">
                                                <div class="form-group">
                                                    <label class="form-label">Текущий пароль</label>
                                                    <input type="password" name="old_password" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Новый пароль</label>
                                                    <input type="password" name="new_password" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Повторите новый пароль</label>
                                                    <input type="password" name="new_password_repeat" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <input value="{{ $user->id }}" class="form-control" type="hidden" name="user_id">
                                        <input type="submit" class="btn btn-primary mb-2" value="Сохранить">
                                    </form>
                                    <!-- SECURITY SETTINGS -->
                                    <form class="tab-pane fade" id="account-security" action="{{ route('personal.settings.changeSecurity', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <div class="card-body pb-2">
                                                <label class="form-label">Двухфакторная аутентификация</label>
                                                @if (!auth()->user()->is_active_2fa)
                                                    <footer class="blockquote-footer">
                                                        Добавьте еще один уровень безопасности Вашего аккаунта, включив
                                                        подтверждение входа по СМС.
                                                        <cite title="Source Title"></cite>
                                                    </footer>
                                                    <input type="submit" class="btn btn-primary mb-2" value="Включить">
                                                @else
                                                    <div class="form-group">
                                                        <footer class="blockquote-footer">
                                                            В данный момент двухфакторная аутентификация активна.
                                                            <cite title="Source Title"></cite>
                                                        </footer>
                                                        <input type="submit" class="btn btn-danger mb-2" value="Отключить">
                                                    </div>
                                                @endif
                                                <input value="{{ $user->id }}" class="form-control" type="hidden" name="user_id">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
</div>

<script>
imgInp.onchange = evt => {
    const [file] = imgInp.files
    if (file) {
        blah.src = URL.createObjectURL(file)
    }
}

deletePhoto.onclick = function () {
    document.getElementById('imgInp').value = "";
    document.getElementById('noPhoto').value = "-1";
    blah.src = "http://energy_faculty.com/storage/images/personal/no_photo.jpg";
}
</script>
<!-- /.content-wrapper -->
@endsection
