@extends('ca.layouts.main')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css\ca\style.css')  }}">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Заявки на получение электронных подписей</h1>
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

      <div class="row">
          <div class="col-8">
              <div class="card">
                  <div class="card-body table-responsive">
                      <table class="table table-hover text-wrap">
                          <thead>
                          <tr>
                              <th>Номер заявки</th>
                              <th>ФИО</th>
                              <th>Email</th>
                              <th style="width: 40%;">Действия</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach ($certApps as $certApp)
                              <tr>
                                  <td>{{ $certApp->id }}</td>
                                  <td>
                                      {{ $certApp->teacher->user->surname }}
                                      {{ $certApp->teacher->user->name }}
                                      {{ $certApp->teacher->user->patronymic }}
                                  </td>
                                  <td>
                                      {{ $certApp->teacher->user->email }}
                                  </td>
                                  <td class="project-actions text-left">
                                      <a class="btn btn-info btn-sm mr-1" href="{{ route('ca.cert_app.accept', $certApp->id) }}">
                                          <i class="fas fa-pencil-alt"></i>
                                          Выдать сертификат
                                      </a>
                                      <form action="" method="post"
                                            style="display: inline-block">
                                          @csrf
                                          @method('delete')
                                          <button type="submit" class="btn btn-danger btn-sm delete-btn" href="#">
                                              <i class="fas fa-trash">
                                              </i>
                                              Отклонить заявку
                                          </button>
                                      </form>
                                  </td>
                              </tr>
                          @endforeach
                          </tbody>
                      </table>
                  </div>
                  <!-- /.card-body -->
              </div>
          </div>
      </div>


    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
