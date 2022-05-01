  @extends('admin.layouts.main')

  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
                <a href="{{ route('admin.lesson.get-chair-load', [$data['chair']->id, $data['year']->id]) }}"><i class="fas fa-chevron-left mb-2"></i></a>
                Добавление учебной нагрузки
            </h1>
            <h5>{{ $data['chair']->title }} на {{ $data['year']->start_year }}-{{ $data['year']->end_year }}</h5>
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
            <form action="{{ route('admin.lesson.store', [$data['chair']->id, $data['year']->id]) }}" method="POST" class="w-25">
              @csrf
                <div class="form-group">
                    <label>Выберите семестр</label>
                    <select name="semester" class="form-control">
                        @foreach($data['semesters'] as $semester)
                            <option value="{{ $semester }}" {{$semester == old('semester') ? 'selected' : ''}}>{{ $semester }}</option>
                        @endforeach
                    </select>
                    @error('semester')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Выберите группу</label>
                    <select name="group_id" class="form-control">
                        @foreach($data['groups'] as $group)
                            <option
                                value="{{$group->id }}" {{$group->id == old('group_id') ? 'selected' : ''}}>{{ $group->title }}</option>
                        @endforeach
                    </select>
                    @error('group_id')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Выберите преподавателя</label>
                    <select name="teacher_id" class="form-control">
                        <option value="">-- Не выбран</option>
                        @foreach($data['teachers'] as $teacher)
                            <option
                                value="{{$teacher->id }}" {{$teacher->id == old('teacher_id') ? 'selected' : ''}}>{{ $teacher->user->surname }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Выберите дисциплину</label>
                    <select name="discipline_id" class="form-control">
                        @foreach($data['disciplines'] as $discipline)
                            <option
                                value="{{$discipline->id }}" {{$discipline->id == old('discipline_id') ? 'selected' : ''}}>{{ $discipline->title }}</option>
                        @endforeach
                    </select>
                    @error('discipline_id')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" value="{{ $data['year']->id }}" name="year_id">

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
