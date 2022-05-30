@extends('personal.layouts.main')

@section('title-block')Моя электронная цифровая подпись@endsection

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 mb-2">Сертификат электронной цифровой подписи</h1>

                    </div><!-- /.col -->
                    <div class="col-sm-6">
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="col-3 alert alert-success" role="alert">{!! session('success') !!}</div>
                    <input value="{{ session('data')['private_key'] }}" type="hidden" id="private_key">
                    <input value="{{ session('data')['filename'] }}" type="hidden" id="filename">
                @endif
                @if (isset($cert))
                    <div class="col-md-6">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">
                                            Серийный номер
                                        </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $cert['subject']['serialNumber'] }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">
                                            Владелец
                                        </h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $cert['subject']['CN'] }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $cert['subject']['emailAddress'] }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Дата выдачи</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{date('d.m.Y', $cert['validFrom_time_t'])}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Действителен до</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{date('d.m.Y', $cert['validTo_time_t'])}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Выдан</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $cert['issuer']['O'] }},
                                        {{ $cert['issuer']['emailAddress'] }}
                                    </div>
                                </div>
                                {{--                              <hr>--}}
                                {{--                              <div class="row">--}}
                                {{--                                  <div class="col-sm-12">--}}
                                {{--                                      <a class="btn btn-info "target="__blank"--}}
                                {{--                                         href="https://www.bootdey.com/snippets/view/profile-edit-data-and-skills">--}}
                                {{--                                          Отозвать сертификат--}}
                                {{--                                      </a>--}}
                                {{--                                  </div>--}}
                                {{--                              </div>--}}
                            </div>
                        </div>
                        <div class="md-3 pb-3">
                            <a href="{{ route('personal.cert.create') }}" class="btn btn-outline-info">
                                Перевыпустить сертификат
                            </a>
                        </div>
                    </div>

                @else
                    @if (isset($certApp))
                        <div class="row">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <p>Заявка на получение сертификата была отправлена ранее</p>
                                    <p>Письмо с ответом Вам придет на почту: <b>{{ $teacher->user->email  }}</b></p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h3 class="h6"><b>Внимание!</b></h3>
                                    <p>В данный момент электронная подпись у Вас отсутствует.</p>
                                    <p>Оставьте заявку на её получение:</p>
                                    <a href="{{ route('personal.cert.create') }}" class="btn btn-block btn-primary">Заказать
                                        подпись</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            let private_key = $('#private_key').val();
            let filename = $('#filename').val();
            if ((typeof private_key != "undefined") && (typeof filename != "undefined")) {
                downloadAsFile(private_key, filename);
                // console.log(private_array);
                // setTimeout(function () {
                //     window.location = 'http://energy_faculty.com/personal/cert/downloadFile/' + private_array + '/' + filename;
                // }, 2000);

                // $.ajax({
                //     type: 'GET',
                //     data: {'private_key': private_key} ,
                //     url:  'http://energy_faculty.com/personal/cert/downloadFile/' + filename,
                //     success: function (response) {
                //         console.log(response);
                //     }
                // });
            }
        });

        function downloadAsFile(data, filename) {
            let a = document.createElement("a");
            let file = new Blob([data], {type: 'application/json'});
            a.href = URL.createObjectURL(file);
            a.download = filename;
            a.click();
        }
    </script>
@endsection
