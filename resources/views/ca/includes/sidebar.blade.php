    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('includes.ef-logo', ['titlePersonal' => 'Удостоверяющий<br>центр'])

      <!-- Sidebar -->
      <div class="sidebar">
        <ul class="pt-2 nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('ca.cert_app.index') }}" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Заявки на ЭЦП</p>
            </a>
          </li>
        </ul>
      </div>
      <!-- /.sidebar -->
    </aside>
