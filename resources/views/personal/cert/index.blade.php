  @extends('personal.layouts.main')

  @section('title-block')Мой сертификат@endsection

  @section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 mb-2">Сертификат электронной подписи</h1>

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
              @if (isset($cert))
                    <div class="col-md-6">
                      <div class="card mb-3">
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
                                      {{date('Y-m-d H:i:s', $cert['validFrom_time_t'])}}
                                  </div>
                              </div>
                              <hr>
                              <div class="row">
                                  <div class="col-sm-3">
                                      <h6 class="mb-0">Действителен до</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                      {{date('Y-m-d H:i:s', $cert['validTo_time_t'])}}
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
                                  <a href="{{ route('personal.cert.create') }}" class="btn btn-block btn-primary">Заказать подпись</a>
                              </div>
                          </div>
                      </div>
                  @endif
              @endif
          </div><!-- /.container-fluid -->
      </section>
    <!-- /.content -->
  </div>
  @endsection
