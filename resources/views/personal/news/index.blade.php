@extends('personal.layouts.main')

@section('title-block')Новости группы {{ auth()->user()->student->group->title  }}@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/news/group_news.css') }}">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Новости группы {{ auth()->user()->student->group->title }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @can('isHeadman')
                <div class="row">
                    <div class="col-1 mb-3">
                        <a href="{{ route('personal.news.create') }}" class="btn btn-block btn-primary">Добавить запись</a>
                    </div>
                </div>
                @endcan
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="container posts-content">
                                @foreach($group_news as $post)
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="media mb-3">

                                                <img src="{{ asset('storage/images/personal/no_photo.jpg') }}"
                                                     class="d-block ui-w-40 rounded-circle" alt="">
                                                <div class="media-body ml-3">
                                                    {{ $headman->user->surname }}
                                                    <div class="text-muted small">Опубликовано: {{ date('d.m.Y', strtotime($post->created_at)) }} в {{ date('H:i', strtotime($post->created_at)) }}</div>
                                                </div>
                                            </div>
                                            <p>{!! $post->content !!}</p>
                                            @if (isset($post->images))
                                                @php
                                                $images = json_decode($post->images);
                                                @endphp
                                                <a href="javascript:void(0)" class="ui-rect ui-bg-cover" style="background-image: url('{{ asset('storage/' . $images[0]) }}');"></a>
                                            @endif
                                        </div>
                                        <div class="card-footer">
                                            <a href="javascript:void(0)" class="d-inline-block text-muted">
                                                <strong>123</strong> Likes</small>
                                            </a>
                                            @can('isHeadman')
                                            <a class="btn btn-info btn-sm mr-1" href="{{ route('personal.news.edit', $post->id) }}">
                                                <i class="fas fa-pencil-alt"></i>
                                                Редактировать
                                            </a>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
