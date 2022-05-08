@extends('personal.layouts.main')

@section('title-block')События группы {{ $group->title  }}@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal/news/group_news.css') }}">
    <link rel="stylesheet" href="{{ asset('css/news/jquery.fancybox.min.css') }}">
    <input type="hidden" name="total-count" value="{{ $total_count }}">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">События группы {{ $group->title }}</h1>
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
                    <div class="col-md-8 col-sm-12  mb-3" style="">
                        <div class="createPost">
                            @can('create-group-news', $group)
                                <div class="row" style="margin: 0">
                                    <div class="form-group" style="margin-bottom: 0">
                                        <a href="{{ route('personal.news.create', $group) }}" class="btn btn-block btn-primary">Добавить запись</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                        @if ($total_count !== 0)
                        <div class="container posts-content" id="posts-content" style="background: #ffffff; padding: 0px 15px 15px 15px">
                            @include('personal.news.ajax-views.all-news')
                        </div>
                        @endif
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <div class="ajax-load text-center" style="display: none">
            <p><img src="{{ asset('assets/default/loading.gif') }}"
                    alt="AJAX loader" title="AJAX loader"/>Посты загружаются...</p>
        </div>
        <!-- /.content -->
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery/jquery.fancybox.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            let totalCount = $("input[name='total-count']").val()

            function loadNextPage(page) {
                $.ajax({
                    url: '{{$group->id}}/?page=' + page,
                    type: 'GET',
                    beforeSend: function () {
                        $('.ajax-load').show();
                    },
                    success: function (data) {
                        if (data === " ") {
                            return;
                        }
                        $('.ajax-load').hide();
                        $('#posts-content').append(data).show();
                    }
                })
            }

            let page = 1;
            $(window).scroll(function () {
                if(Math.ceil($(window).scrollTop() + $(window).height()) >= $(document).height()) {
                    if (parseInt(totalCount) >= page) {
                        page++;
                        loadNextPage(page);
                    }
                }
            })

            Echo.channel('read-group-post-channel')
                .listen('.read-group-post-event', (e) => {
                    $('#readingPost_' + e.postId).css('background', '#fff');
            })

            Echo.channel('delete-group-post-channel')
                .listen('.delete-group-post-event', (e) => {
                    let $readingPost = $('#readingPost_' + e.postId);
                    let $unreadPost = $('#unreadPost_' + e.postId);
                    let $post = $('#post_' + e.postId);
                    if ($readingPost !== undefined) {
                        $readingPost.remove()
                    }
                    if ($unreadPost !== undefined) {
                        $unreadPost.remove()
                    }
                    if ($post !== undefined) {
                        $post.remove()
                    }
                    $('#hr_' + e.postId).remove();
                })

            Echo.channel('add-group-post-channel')
                .listen('.add-group-post-event', (e) => {
                    $.ajax({
                        type: 'GET',
                        url: 'group/show/new-added-post/' + e.postId,
                        success: function (response) {
                            $('#posts-content').prepend(response).show();
                        }
                    });
                })

            $('.content')
                .on('mousemove', '.postBody', function () {
                    let postId = $(this).attr('id');
                    if (postId.includes('unreadPost')) {
                        postId = postId.split('_')[1];
                        $(this).attr('id', 'readingPost_' + postId)
                        $.ajax({
                            type: 'GET',
                            url: 'group/show/read-post/' + postId,
                        });
                    }
                    if (postId.includes('readingPost_')) {
                        $(this).css('background', '#fff');
                    }
                })
                .on('click', '.deletePost', function () {
                    if(confirm('Вы действительно хотите удалить пост?')){
                        let postId = $(this).attr('id').split('_')[1];
                        $.ajax({
                            type: 'DELETE',
                            url: postId,
                            dataType: 'JSON',
                            data: { '_token': $("input[name='_token']").val() }
                        });
                    }
                })
        })
    </script>

    <!-- /.content-wrapper -->
@endsection
