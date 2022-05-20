  @extends('admin.layouts.main')

  @section('content')
      @csrf
      {{--Модальное окно для импорта пользователей из Excel--}}
      <div class="modal fade" id="importUsersModal" tabindex="-1" role="dialog"
           aria-labelledby="importUsersModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="card-title">Импорт пользователей из Excel</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                          <select name="role_id" class="form-control">
                              <option value="1">Студенты</option>
                          </select>
                      </div>
                      @include('admin.includes.users.import_students')
                      <button type="button" id="importUsers" class="btn btn-primary">
                          Сохранить
                      </button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                  </div>
              </div>
          </div>
      </div>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Пользователи</h1>
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
            <div class="col-md-2 mb-3">
                <a href="{{ route('admin.user.create') }}" class="btn btn-block btn-primary">Создать</a>
            </div>
            <div class="col-md-2 mb-3">
                <a href="javascript:void(0)" data-toggle="modal"
                   class="btn btn-block btn-success"
                   data-target="#importUsersModal">Загрузить из Excel</a>
            </div>
        </div>

        <div class="row">
          <div class="col-md-3 mb-2">
            <label for="exampleFormControlInput1" class="form-label">Фильтры</label>
            <form action="{{route('admin.user.index')}}" method="GET">
              <div class="form-group">
                <input @if(isset($_GET['user_id'])) value="{{$_GET['user_id']}}" @endif type="text" class="form-control" name="user_id" placeholder="ID">
              </div>
              <div class="form-group">
                <input @if(isset($_GET['full_name'])) value="{{$_GET['full_name']}}" id="full_name" @endif type="text" class="form-control typeahead" name="full_name" placeholder="ФИО">
              </div>
              <div class="form-group">
                <select name="role_id" class="form-select form-select-sm" aria-label=".form-select-sm example">
                  <option value="">Все роли</option>
                  @foreach($roles as $id => $role)
                  <option value="{{ $id }}" @if(isset($_GET['role_id'])) @if($_GET['role_id']==$id) selected @endif @endif>{{ $role }}</option>
                  @endforeach
                </select>
              </div>
              <button type="submit" class="btn btn-primary mb-2">Применить</button>
            </form>
            <form action="{{ request()->url() }}" method="GET">
              <button type="submit" class="btn btn-default">Сбросить</button>
            </form>
          </div>
          <div class="col-md-9">
            <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>ФИО</th>
                      <th>Роль</th>
                      <th colspan="3">Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $user)
                    <tr>
                      <td>{{ $user->id }}</td>
                      <td>{{ $user->surname }}
                        {{ $user->name }}
                        {{ $user->patronymic }}
                      </td>
                      @foreach ($roles as $key => $role)
                        @if ($user->role_id == $key)
                          <td>{{ $role }}</td>
                          @break
                        @endif
                      @endforeach
                      <td><a href="{{ route('admin.user.edit', $user->id) }}" class="text-success"><i class="far fa-edit"></i></a></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <div class="mt-3">
              {{ $users->withQueryString()->links() }}
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

  <script type="text/javascript">
    path = "{{ route('admin.user.search') }}";

    $(document).ready(function() {
      $('input').attr('autocomplete', 'off');

        $('.studentsTemplateDownload').on('click', function () {
            $.ajax({
                type: 'GET',
                url: 'users/export',
                success: function(response) {
                    downloadFile(response);
                }
            });
        });

        $('#importUsers').on('click', function () {
            $('.importUsersErrors').html('');
            let groupTitle = $("input[name='title']").val();
            const file = $('#excel_file')[0].files[0];
            if (groupTitle === '') {
                $('#studentGroupError').text('Необходимо ввести название группы');
            }
            if (file === undefined) {
                $('#fileError').text('Файл не выбран');
                return;
            }
            let formData = new FormData();
            formData.append('_token', $("input[name='_token']").val());
            formData.append('excel_file', file);
            formData.append('title', groupTitle);
            formData.append('chair_id', $("#chair_id").find(":selected").val());
            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                url: 'users/students-import',
                datatype: 'json',
                success: function (response) {
                    alert(response);
                    $('#importUsersModal').modal('hide');
                },
                error: function (response) {
                    if (response.responseJSON !== undefined) {
                        $('#fileError').text(response.responseJSON.errors['excel_file']);
                    } else {
                        $('#studentGroupError').text(response.responseText);
                    }
                }
            });
        });

        function downloadFile(response) {
            var a = document.createElement("a");
            a.href = response.file;
            a.download = response.file_name;
            document.body.appendChild(a);
            a.click();
            a.remove();
        }
    });

    $('input.typeahead').typeahead({
      source: function(query, process) {
        return $.get(path, {
          term: query
        }, function(data) {
          return process(data);
        });
      }
    });
  </script>

  <!-- /.content-wrapper -->
  @endsection
