<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('includes.ef-logo', ['titlePersonal' => 'Деканат'])

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="pt-2 nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('dekanat.statement.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-poll-h"></i>
                    <p>Ведомости</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dekanat.exam_sheet.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-list-ol"></i>
                    <p>Заявки на пересдачу</p>
                </a>
            </li>
        </ul>
    </div>
    <!-- /.sidebar -->
</aside>
