<link href="{{ asset('css/logo_animation.css') }}" rel="stylesheet">
<a href="{{ env('FRONTEND_URL') }}" class="brand-link">
    <div class="row">
    <img src="{{ asset('assets/default/logo.png') }}"
         alt="AdminLTE Logo"
         class="brand-image img-circle elevation-3">
    <span class="brand-text font-weight">{!! $titlePersonal !!}</span>
    </div>
    <a type="button" class="nav-link changeTheme ml-2 mt-1" style="border-radius: 0.25rem">
        <div class="row d-flex" style="margin: 0">
            <i class="nav-icon fas fa-adjust mr-1 mt-1"></i>
            <p>День/ночь</p>
        </div>
    </a>
</a>
