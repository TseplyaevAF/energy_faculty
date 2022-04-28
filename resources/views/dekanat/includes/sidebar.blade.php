<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/dekanat" class="brand-link">
        <img src="{{ asset('assets/default/logo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Деканат ЭФ</span>
    </a>

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
