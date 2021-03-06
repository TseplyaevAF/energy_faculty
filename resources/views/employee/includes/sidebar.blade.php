    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('includes.ef-logo', ['titlePersonal' => 'Кафедра ' . $chair->title, 'mainUrl' => route('employee.main.index')])

      <!-- Sidebar -->
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            @if (!isset(auth()->user()->avatar))
            <img src="{{ asset('assets/default/employee_default_photo.png') }}" class="img-square elevation-2" alt="User Image">
            @endif
          </div>
          <div class="info">
              @if(auth()->user()->role_id != $roleTeacher)
            <a href="/employee" class="d-block">
                <p>{{ auth()->user()->surname }} {{auth()->user()->name}}</p>
            </a>
                  @else
                  <a href="{{route('personal.main.index')}}" class="d-block">
                      <p>{{ auth()->user()->surname }} {{auth()->user()->name}}</p>
                  </a>
              @endif
          </div>
        </div>
        <ul class="pt-2 nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('employee.chair.edit', $chair->id) }}" class="nav-link">
              <i class="nav-icon fas fa-info"></i>
              <p>Информация о кафедре</p>
          </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('employee.news.index') }}" class="nav-link">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>Новости</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('employee.schedule.index') }}" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Расписание занятий</p>
            </a>
          </li>
            <li class="nav-item">
                <a href="{{ route('employee.group.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Учебные группы</p>
                </a>
            </li>
        </ul>
      </div>
      <!-- /.sidebar -->
    </aside>
