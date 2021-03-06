<style>
    .postsCount {
        right: 2px;
        border-radius: 100%;
        /*padding: 2px;*/
        height: 25px;
        width: 25px;
        background-color: rgba(232, 127, 11, 0.89);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        cursor: pointer;
    }
</style>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      @if (auth()->user()->role_id == 2)
          @include('includes.ef-logo', ['titlePersonal' => 'Студент', 'mainUrl' => route('personal.main.index')])
      @elseif (auth()->user()->role_id == 3)
          @include('includes.ef-logo', ['titlePersonal' => 'Преподаватель', 'mainUrl' => route('personal.main.index')])
      @endif

      <!-- Sidebar -->
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            @if (isset(auth()->user()->avatar))
              @php
                  $modelId = explode('/', auth()->user()->avatar)[0];
                  $mediaId = explode('/', auth()->user()->avatar)[2];
              @endphp
              <img src="{{ route('personal.settings.showImage', [$modelId, $mediaId, 'filename']) }}" class="img-square elevation-2" alt="User Image">
            @else
              <img src="{{ asset('assets/default/personal_default_photo.jpg') }}" class="img-square elevation-2" alt="User Image">
            @endif
          </div>
          <div class="info">
            <a href="/personal" class="d-block">
                <p>{{ auth()->user()->surname }} {{auth()->user()->name}}</p>
            </a>
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
          @endcan
            @can('isStudent')
            <li class="nav-item">
                <a href="{{ route('personal.news.index', auth()->user()->student->group->id) }}" class="nav-link">
                    <i class="nav-icon far fa-newspaper"></i>
                    <p>События группы</p>
                    <span class="postsCount" style="display:none;">0</span>
                </a>
            </li>
            @endcan
          @can('isTeacher')
            @can('isCurator')
                <li class="nav-item">
                    <a href="{{ route('personal.news.showGroupsCurator') }}" class="nav-link">
                        <i class="nav-icon far fa-newspaper"></i>
                        <p>События групп</p>
                        <span class="postsCount" style="display:none;">0</span>
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
            @can('isTeacher')
                @can('isCurator')
                <li class="nav-item">
                    <a href="{{ route('personal.mark.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-check-double"></i>
                        <p>Куратору</p>
                    </a>
                </li>
                @endcan
            @endcan
            @can('isStudent')
                <li class="nav-item">
                    <a href="{{ route('personal.exam_sheet.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-poll-h"></i>
                        <p>Задолженности</p>
                    </a>
                </li>
            @endcan
        </ul>
      </div>
      <!-- /.sidebar -->
    </aside>
