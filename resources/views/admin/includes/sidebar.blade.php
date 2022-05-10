    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="{{ url('http://localhost:8080/') }}" class="brand-link">
        <img src="{{ asset('assets/default/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Админ-панель</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <ul class="pt-2 nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="{{ route('admin.chair.index') }}" class="nav-link">
              <i class="nav-icon fas fa-university"></i>
              <p>Кафедры</p>
            </a>
          </li>

            <li class="nav-item">
                <a href="{{ route('admin.group.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Все группы</p>
                </a>
            </li>

          <li class="nav-item">
            <a href="{{ route('admin.discipline.index') }}" class="nav-link">
              <i class="nav-icon fas fa-book-open"></i>
              <p>Дисциплины</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.user.index') }}" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>Пользователи</p>
            </a>
          </li>

            <li class="nav-item">
                <a href="{{ route('admin.lesson.index') }}" class="nav-link">
                    <i class="nav-icon far fa-newspaper"></i>
                    <p>Учебная нагрузка</p>
                </a>
            </li>

          <li class="nav-item">
            <a href="{{ route('admin.schedule.index') }}" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Расписание занятий</p>
            </a>
          </li>
        </ul>
      </div>
      <!-- /.sidebar -->
    </aside>
