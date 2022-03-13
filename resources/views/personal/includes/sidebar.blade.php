    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="/personal" class="brand-link">
        <img src="{{ asset('storage/' . 'images/admin/sidebar/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @if (auth()->user()->role_id == 2)
        <span class="brand-text font-weight-light">Студент</span>
        @elseif (auth()->user()->role_id == 3)
        <span class="brand-text font-weight-light">Преподаватель</span>
        @endif
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            @if (isset(auth()->user()->avatar))
              @php
                  $modelId = explode('/', auth()->user()->avatar)[0];
                  $mediaId = explode('/', auth()->user()->avatar)[2];
                  $filename = explode('/', auth()->user()->avatar)[3];
              @endphp
              <img src="{{ route('personal.settings.showImage', [$modelId, $mediaId, $filename]) }}" class="img-square elevation-2" alt="User Image">
            @else
              <img src="{{ asset('storage/images/personal/no_photo.jpg') }}" class="img-square elevation-2" alt="User Image">
            @endif
          </div>
          <div class="info">
            <a href="/personal" class="d-block">{{ auth()->user()->surname }} {{auth()->user()->name}}</a>
          </div>
        </div>
        <ul class="pt-2 nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('personal.settings.edit') }}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>Настройки</p>
            </a>
          </li>
          @can('isTeacher')
          <li class="nav-item">
            <a href="{{ route('personal.task.index') }}" class="nav-link">
              <i class="nav-icon fas fa-tasks"></i>
              <p>Задания для групп</p>
            </a>
          </li>
          @endcan
          @can('isStudent')
          <li class="nav-item">
            <a href="{{ route('personal.homework.index') }}" class="nav-link">
              <i class="nav-icon fas fa-file-word"></i>
              <p>Домашние задания</p>
            </a>
          </li>
            <li class="nav-item">
                <a href="{{ route('personal.news.index') }}" class="nav-link">
                    <i class="nav-icon far fa-newspaper"></i>
                    <p>Новости</p>
                </a>
            </li>
            @can('isHeadman')
            <li class="nav-item">
              <a href="{{ route('personal.application.index') }}" class="nav-link">
                <i class="nav-icon far fa-address-card"></i>
                <p>Заявки студентов</p>
              </a>
            </li>
            @endcan
          @endcan
          <li class="nav-item">
            <a href="{{ route('personal.main.schedule') }}" class="nav-link">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>Расписание занятий</p>
            </a>
          </li>
            @can('isTeacher')
            <li class="nav-item">
                <a href="{{ route('personal.cert.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-file-signature"></i>
                    <p>Моя подпись</p>
                </a>
            </li>
            @endcan
            @can('isTeacher')
                <li class="nav-item">
                    <a href="{{ route('personal.statement.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-poll-h"></i>
                        <p>Ведомости</p>
                    </a>
                </li>
            @endcan
            @can('isStudent')
                <li class="nav-item">
                    <a href="{{ route('personal.exam_sheet.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-poll-h"></i>
                        <p>Допуски</p>
                    </a>
                </li>
            @endcan
        </ul>
      </div>
      <!-- /.sidebar -->
    </aside>
