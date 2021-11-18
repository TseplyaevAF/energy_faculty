    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="/employee" class="brand-link">
        <img src="{{ asset('storage/' . 'images/admin/sidebar/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @if (isset(auth()->user()->role->student_id))
        <span class="brand-text font-weight-light">Кабинет студента</span>
        @elseif (isset(auth()->user()->role->teacher_id))
        <span class="brand-text font-weight-light">Преподаватель</span>
        @endif
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            @if (!isset(auth()->user()->avatar))
            <img src="{{ asset('storage/' . 'images/employee/no_photo.png') }}" class="img-square elevation-2" alt="User Image">
            @endif
          </div>
          <div class="info">
            <a href="#" class="d-block">{{ auth()->user()->surname }} {{auth()->user()->name}}</a>
          </div>
        </div>
        <ul class="pt-2 nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('personal.task.index') }}" class="nav-link">
              <i class="nav-icon fas fa-tasks"></i>
              <p>Задания для групп</p>
            </a>
          </li>
        </ul>
      </div>
      <!-- /.sidebar -->
    </aside>