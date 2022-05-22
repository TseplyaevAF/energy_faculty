    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('includes.ef-logo', ['titlePersonal' => 'Админ-панель'])

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
        </ul>
      </div>
      <!-- /.sidebar -->
    </aside>
