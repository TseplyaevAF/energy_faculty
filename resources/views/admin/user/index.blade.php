  @extends('admin.layouts.main')

  @section('content')
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
          <div class="col-1 mb-3">
            <a href="{{ route('admin.user.create') }}" class="btn btn-block btn-primary">Создать</a>
          </div>
        </div>

        <div class="row">
          <div class="col-2">
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
            <form action="{{ route('admin.user.index') }}" method="GET">
              <input value="" type="hidden" name="role_id">
              <input value="" type="hidden" name="user_id">
              <input value="" type="hidden" name="full_name">
              <button type="submit" class="btn btn-default">Сбросить</button>
            </form>
          </div>
          <div class="col-6">
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
