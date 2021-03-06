<link href="{{ asset('css/logo_animation.css') }}" rel="stylesheet">
    <div class="brand-link">
    <div class="row">
        <a href="{{ env('FRONTEND_URL') }}"><img src="{{ asset('assets/default/logo.png') }}"
         alt="AdminLTE Logo"
         class="brand-image img-circle elevation-3">
        </a>
        <a href="@if(isset($mainUrl)){{ $mainUrl }}@else/@endif">
            <span class="brand-text font-weight">{!! $titlePersonal !!}</span>
        </a>
    </div>
    </div>
    <a type="button" class="nav-link changeTheme ml-2 mt-1" style="border-radius: 0.25rem">
        <div class="row d-flex" style="margin: 0">
            <i class="changeThemeIcon nav-icon fas fa-adjust mr-1 mt-1"></i>
            <p class="changeThemeText">День/ночь</p>
        </div>
    </a>
