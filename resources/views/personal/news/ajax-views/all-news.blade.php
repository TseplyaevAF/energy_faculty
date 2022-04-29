<style>
    .postContent a {
        word-wrap: break-word;
    }
</style>
@foreach($group_news as $post)
    <hr id="hr_{{$post->id}}">
    @if (in_array($post->id, $unread_posts->pluck('id')->toArray()))
        <div class="postBody mb-4" id="unreadPost_{{ $post->id }}" style="background: rgb(244 246 249)">
    @else
        <div class="postBody mb-4" id="post_{{ $post->id }}">
    @endif
            <div class="media">
                <div class="userAvatar">
                    @if (isset($post->user->avatar))
                        @php
                            $modelId = explode('/', $post->user->avatar)[0];
                            $mediaId = explode('/', $post->user->avatar)[2];
                            $filename = explode('/', $post->user->avatar)[3];
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
{{--                            @if($group->getHeadman()->id == $post->user->student->id)--}}
{{--                                <i style="font-size: 14px">(староста)</i>--}}
{{--                            @endif--}}
                        </div>
                        <div class="text-muted">{{ date('d.m.y в H:i', strtotime($post->created_at)) }}</div>
                        <div>
                            @can('edit-group-news', [$post])
                                <a class="ml-2" href="{{ route('personal.news.edit', [$group, $post->id]) }}">
                                    <i class="fas fa-pencil-alt" style="color: rgba(7,130,7,0.95)"></i>
                                </a>
                                <form action="{{ route('personal.news.destroy', $post->id) }}" method="post"
                                      style="display: inline-block">
                                    @csrf
                                    @method('delete')
                                    <div class="deletePost" id="delete_{{ $post->id }}">
                                        <button type="button" class="border-0 bg-transparent">
                                            <i style="color:rgba(156,11,11,0.93)" class="fas fa-2xs fa-times"></i>
                                        </button>
                                    </div>
                                </form>
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
<script>

</script>
