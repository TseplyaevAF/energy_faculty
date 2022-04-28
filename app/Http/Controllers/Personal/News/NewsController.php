<?php

namespace App\Http\Controllers\Personal\News;

use App\Events\AddGroupPostEvent;
use App\Events\CountGroupPostEvent;
use App\Events\DeleteGroupPostEvent;
use App\Events\ReadGroupPostEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Personal\News\StoreRequest;
use App\Http\Requests\Personal\News\UpdateRequest;
use App\Models\Group\GroupNews;
use App\Models\UnreadPost;
use App\Service\Group\News\Service;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        Gate::authorize('isStudent');
        $user = auth()->user();
        $group = $user->student->group;
        $group_news = $group->news->sortByDesc('created_at')->paginate(8);
        $unread_posts = $user->unread_posts;
        if ($request->ajax()) {
            return view('personal.news.ajax-views.all-news',
                compact('group_news', 'unread_posts'));
        }
        $total_count = (int) ceil($group_news->total() / $group_news->perPage());
        return view('personal.news.index',
            compact('group_news', 'total_count', 'unread_posts'));
    }

    public function create()
    {
        Gate::authorize('isHeadman');

        return view('personal.news.create');
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('isHeadman');

        $data = $request->validated();

        $post = $this->service->store($data);

        event(new AddGroupPostEvent($post->id));
        event(new CountGroupPostEvent());

        return redirect()->route('personal.news.index');
    }

    public function edit(GroupNews $news)
    {
        Gate::authorize('isHeadman');
        Gate::authorize('edit-group-news', [$news]);

        $images = json_decode($news->images);
        return view('personal.news.edit', compact('news', 'images'));
    }

    public function update(UpdateRequest $request, GroupNews $news)
    {
        Gate::authorize('isHeadman');
        Gate::authorize('edit-group-news', [$news]);

        $data = $request->validated();

        $news = $this->service->update($data, $news);

        return redirect()
            ->route('personal.news.edit', compact('news'))
            ->withSuccess('Запись успешно отредактирована');
    }

    public function destroy(GroupNews $news)
    {
        Gate::authorize('isHeadman');
        Gate::authorize('edit-group-news', [$news]);

        $this->service->delete($news);

        event(new DeleteGroupPostEvent($news->id));
        event(new CountGroupPostEvent());

        return redirect()->route('personal.news.index');
    }

    public function getUnreadPostsCount() {
        return count(auth()->user()->unread_posts);
    }

    public function readPost(GroupNews $news) {
        $unreadPost = UnreadPost::
            where('group_news_id', $news->id)
            ->where('user_id', auth()->user()->id)
            ->first();
        $unreadPost->delete();
        event(new ReadGroupPostEvent($news->id));
        event(new CountGroupPostEvent());
    }

    public function showNewAddedPost(GroupNews $post) {
        return view('personal.news.ajax-views.show-new-added-post', compact('post'));
    }
}
