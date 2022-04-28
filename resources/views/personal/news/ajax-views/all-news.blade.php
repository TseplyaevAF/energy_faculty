@foreach($group_news as $post)
    <hr id="hr_{{$post->id}}">
    @if (in_array($post->id, $unread_posts->pluck('id')->toArray()))
        <div class="postBody mb-4" id="unreadPost_{{ $post->id }}" style="background: rgb(244 246 249)">
    @else
        <div class="postBody mb-4" id="post_{{ $post->id }}">
    @endif
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
                                          style="display: inline-block">
                                        @csrf
                                        @method('delete')
                                        <div class="deletePost">
                                            <button type="submit" class="border-0 bg-transparent">
                                                <i style="color:rgba(156,11,11,0.93)" class="fas fa-2xs fa-times"></i>
                                            </button>
                                        </div>
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
<script>

</script>
