@extends('personal.layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/personal/settings/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/help/style.css') }}">
<style>
    .help-2fa:before {
        top: 100%;
        height: 100px;
        width: 270px;
    }
</style>

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
                            <div class="col-md-8">
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
                                                    @endphp
                                                    <img src="{{ route('personal.settings.showImage', [$modelId, $mediaId, 'filename']) }}" class="d-block ui-w-80" id="blah">
                                                @else
                                                    <img src="{{ asset('assets/default/personal_default_photo.jpg') }}" class="d-block ui-w-80" id="blah">
                                                @endif
                                                <div class="media-body ml-4">
                                                    <label class="btn btn-outline-primary mb-2">
                                                        Загрузить новое фото
                                                        <input name="avatar" type="file" class="account-settings-fileinput" accept=".jpg,.jpeg,.png,.bmp,.svg" id="imgInp">
                                                    </label> &nbsp;
                                                    <button type="button" class="btn btn-default md-btn-flat mb-2" id="deletePhoto">Удалить</button>
                                                </div>
                                                    <input type="hidden" name="no_photo" id="noPhoto">
                                                @error('avatar')
                                                <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <hr class="border-light m-0">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="form-label help" data-help="Телефон для двухфакторной аутентификации">
                                                        Номер телефона
                                                        <img class="help help-icon" src="{{ asset('assets/default/question-circle.png') }}">
                                                    </label>
                                                    <input name="phone_number" type="tel" class="form-control phoneMask mb-1" value="{{ $user->phone_number }}">
                                                    @error('phone_number')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                @if (isset(auth()->user()->teacher))
                                                <div class="form-group">
                                                    <label class="form-label help" data-help="Телефон для связи со студентами и сотрудниками">
                                                        Рабочий номер телефона
                                                        <img class="help help-icon" src="{{ asset('assets/default/question-circle.png') }}">
                                                    </label>
                                                    <input name="work_phone" type="tel" class="form-control phoneMask mb-1" value="{{ $user->teacher->work_phone }}">
                                                    @error('work_phone')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                @endif
                                                <div class="form-group">
                                                    <label class="form-label">E-mail</label>
                                                    <input name="email" type="text" class="form-control mb-1" value="{{ $user->email }}">
                                                    @error('email')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <input type="submit" class="btn btn-primary" value="Сохранить">
                                            </div>
                                        </div>
                                        <input value="{{ $user->id }}" class="form-control" type="hidden" name="user_id">
                                    </form>
                                    <!-- PASSWORD SETTINGS -->
                                    <form class="tab-pane fade" id="account-change-password" action="{{ route('personal.settings.updatePassword', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <div class="card-body">
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
                                                <input type="submit" class="btn btn-primary" value="Сохранить">
                                            </div>
                                        </div>
                                        <input value="{{ $user->id }}" class="form-control" type="hidden" name="user_id">
                                    </form>
                                    <!-- SECURITY SETTINGS -->
                                    <form class="tab-pane fade" id="account-security" action="{{ route('personal.settings.changeSecurity', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card-body">
                                            <label class="form-label help help-2fa" data-help="Каждый раз при входе в аккаунт Вам нужно будет вводить 4 последние цифры номера телефона, который будет Вам звонить">
                                                Двухфакторная аутентификация
                                                <img class="help help-2fa help-icon" src="{{ asset('assets/default/question-circle.png') }}">
                                            </label>
                                            @if (!auth()->user()->is_active_2fa)
                                                <footer class="blockquote-footer mb-2">
                                                    Повысьте уровень безопасности Вашего аккаунта, включив
                                                    подтверждение входа по СМС.
                                                    <cite title="Source Title"></cite>
                                                </footer>
                                                <button type="button" class="btn btn-primary" id="on2fa">Включить</button>
                                            @else
                                                <div class="form-group">
                                                    <footer class="blockquote-footer mb-2">
                                                        Для номера {{ auth()->user()->phone_number }} подключена двухфакторная аутентификация
                                                        <cite title="Source Title"></cite>
                                                    </footer>
                                                    <button type="submit" id="off2fa" class="btn btn-danger mb-2">Отключить</button>
                                                </div>
                                            @endif
                                            <input value="{{ $user->id }}" class="form-control" type="hidden" name="user_id">
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
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#on2fa').on('click',function () {
            let userPhone = '{{ auth()->user()->phone_number }}';
            if (userPhone === '') {
                alert('Необходимо ввести номер телефона в разделе "Основные"');
            } else {
                if (confirm(`Подключить двухфакторную аутентификацию для номера:\n${userPhone} ?`)) {
                    $.ajax({
                        type: 'PATCH',
                        url:  'settings/{{ auth()->user()->id }}/security',
                        data: {
                            '_token': $("input[name='_token']").val(),
                            'phone_number': userPhone
                        },
                        success: function(response) {
                            alert(response);
                            location.reload();
                        },
                        error: function(jqXHR, status, error) {
                            if (error === 'Forbidden') {
                                alert(jqXHR.responseText);
                            } else {
                                alert('Произошла ошибка');
                            }
                        },
                    });
                }
            }
        });

        $('#off2fa').click(function () {
            if(!confirm('Вы уверены, что хотите отключить двухфакторную аутентификацию?')){
                return false;
            }
        });
    });

imgInp.onchange = evt => {
    const [file] = imgInp.files
    if (file) {
        blah.src = URL.createObjectURL(file)
    }
}

deletePhoto.onclick = function () {
    document.getElementById('imgInp').value = "";
    document.getElementById('noPhoto').value = "-1";
    blah.src = "{{ url('assets/default/personal_default_photo.jpg') }}";
}
</script>
<!-- /.content-wrapper -->
@endsection
