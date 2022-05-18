@extends('layouts.app')

@section('content')
    <div class="container" style="background-color: white; padding: 15px">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-2">
                    <h1 style="font-size: 80px; margin-bottom: 0">404</h1>
                    <h6>Запрашиваемая страница
                        <span class="text-muted" style="font-weight: bold">"{{ $_SERVER['REQUEST_URI'] }}"</span>
                        не найдена
                    </h6>
                </div>
                <a href="/" class="btn btn-primary">Вернуться в профиль</a>
            </div>
        </div>
    </div>
@endsection
