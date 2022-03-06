  @extends('admin.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Добавление занятия</h1>
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
        <div class="col-3 alert alert-warning" role="alert">{{ session('error') }}</div>
        @endif

        <div class="row">
          <div class="col-12">
            <form action="{{ route('admin.lesson.store') }}" method="POST" class="w-25">
              @csrf

                <div class="form-group">
                    <label>Выберите семестр</label>
                    <select name="semester" class="form-control">
                        @foreach($data['semesters'] as $semester)
                            <option value="{{ $semester }}" {{$semester == old('semester') ? 'selected' : ''}}>{{ $semester }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Выберите год обучения</label>
                    <select name="year_id" class="form-control">
                        @foreach($data['years'] as $year)
                            <option value="{{ $year->id }}" {{$year == old('year_id') ? 'selected' : ''}}>
                                {{ $year->start_year }}-{{ $year->end_year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Выберите группу</label>
                    <select name="group_id" class="form-control">
                        @foreach($data['groups'] as $group)
                            <option
                                value="{{$group->id }}" {{$group->id == old('group_id') ? 'selected' : ''}}>{{ $group->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Выберите преподавателя</label>
                    <select name="teacher_id" class="form-control">
                        @foreach($data['teachers'] as $teacher)
                            <option
                                value="{{$teacher->id }}" {{$teacher->id == old('teacher_id') ? 'selected' : ''}}>{{ $teacher->user->surname }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Выберите преподаваемые дисциплины</label>
                    <select class="select2" name="disciplines_ids[]" multiple="multiple" data-placeholder="Выберите дисциплины" style="width: 100%;">
                        @foreach ($data['disciplines'] as $discipline)
                            <option {{ is_array(old('disciplines_ids'))
                    && in_array($discipline->id, old('disciplines_ids'))
                    ? 'selected' : ''}} value="{{ $discipline->id }}">{{ $discipline->title }}</option>
                        @endforeach
                    </select>
                    @error('disciplines_ids')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

              <input type="submit" class="btn btn-primary" value="Добавить">
            </form>
          </div>
        </div>


      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
