@extends('personal.layouts.main')

@section('title-block')Новости группы {{ auth()->user()->student->group->title  }}@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/news/group_news.css') }}">
    <link rel="stylesheet" href="{{ asset('css/news/jquery.fancybox.min.css') }}">

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
                <div class="row">
                    <div class="col-md-6" style="">
                        <div class="createPost">
                            @can('isHeadman')
                                <div class="row" style="margin: 0">
                                    <div class="form-group" style="margin-bottom: 0">
                                        <a href="{{ route('personal.news.create') }}" class="btn btn-block btn-primary">Добавить запись</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                        <div class="container posts-content" style="background: #ffffff; padding: 0px 15px 15px 15px">
                            @foreach($group_news as $post)
                                    <hr>
                                <div class="postBody mb-4">
                                    <div class="media">
                                        <div class="userAvatar">
                                            @if (isset(auth()->user()->avatar))
                                                @php
                                                    $modelId = explode('/', auth()->user()->avatar)[0];
                                                    $mediaId = explode('/', auth()->user()->avatar)[2];
                                                    $filename = explode('/', auth()->user()->avatar)[3];
                                                @endphp
                                                <img src="{{ route('personal.settings.showImage', [$modelId, $mediaId, $filename]) }}"
                                                     class="d-block ui-w-40 rounded-circle" alt="">
                                            @else
                                                <img src="{{ asset('assets/default/personal_default_photo.jpg') }}"
                                                     class="d-block ui-w-40 rounded-circle" alt="">
                                            @endif
                                        </div>
                                        <div class="media-body ml-2">
                                            <div class="postDate">
                                                <div class="mr-2">
                                                    {{ $post->user->surnameName() }}
                                                </div>
                                                <div class="text-muted">{{ date('H:i', strtotime($post->created_at)) }}</div>
                                                <div>
                                                    @can('isHeadman')
                                                        @can('edit-group-news', [$post])
                                                        <a class="ml-2" href="{{ route('personal.news.edit', $post->id) }}">
                                                            <i class="fas fa-pencil-alt" style="color: rgba(7,130,7,0.95)"></i>
                                                        </a>
                                                        <form action="{{ route('personal.news.destroy', $post->id) }}" method="post"
                                                            class="ml-1"  style="display: inline-block">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="deletePost border-0 bg-transparent">
                                                                <i style="color:rgba(156,11,11,0.93)" class="fas fa-2xs fa-times"></i>
                                                            </button>
                                                        </form>
                                                        @endcan
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="postContent">
                                        <div>{!! $post->content !!}</div>
                                        @if (isset($post->images))
                                            <div class="row">
                                                @foreach(json_decode($post->images) as $image)
                                                    <div class="col-lg-2 col-md-2 col-4 thumb">
                                                        <a data-fancybox="gallery" href="{{ asset('storage/' . $image) }}">
                                                            <img class="img-fluid" src="{{ asset('storage/' . $image) }}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="footer">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery/jquery.fancybox.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.deletePost').click(function () {
                if(!confirm('Вы действительно хотите удалить пост?')){
                    return false;
                }
            });
        })
    </script>

    <!-- /.content-wrapper -->
@endsection
