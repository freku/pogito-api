<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Post;
use App\Twitch\Helpers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreatePostRequest;
use App\Services\Interfaces\PostServiceInterface;
use App\Services\Interfaces\TwitchServiceInterface;

class PostController extends Controller
{
    public function __construct(private TwitchServiceInterface $twitchService, private PostServiceInterface $postService)
    {
        $this->middleware('auth')->except(['index', 'show', 'sort_new']);
    }

    public function index()
    {
        $todayMinusOneWeekAgo = \Carbon\Carbon::today()->subWeek();
        // $todayMinusOneWeekAgo = \Carbon\Carbon::today()->subYear();

        $posts = Post::all()->where('created_at', '>=', $todayMinusOneWeekAgo)->sortByDesc('likes')->forPage(1, 5);

        return view('home', ['posts' => $posts, 'sort_type' => 1]);
    }

    public function sort_new()
    {
        $todayMinusOneWeekAgo = \Carbon\Carbon::today()->subYear();

        $posts = Post::all()->where('created_at', '>=', $todayMinusOneWeekAgo)->sortByDesc('likes')->forPage(1, 5);

        return view('home', ['posts' => $posts, 'sort_type' => 2]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(CreatePostRequest $request)
    {
        $id = $this->twitchService->extractTwitchClipId($request->link);

        $accesToken = $this->twitchService->getAccessToken();
        $postData = $this->twitchService->getClipJsonById($accesToken, $id)['data'][0];

        $post = $this->postService->createPostWithTags($request->tytul, $postData, $request->tags);

        return redirect("/post/$post->id-" . Str::slug($post->title));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $url_array = explode('-', $id);
        $post_id = $url_array[0];

        $post = Post::find($post_id);

        // post istnieje
        if ($post) {
            // ustawic poprawne url ( z tytulem postu )
            if (count($url_array) == 1) {
                return redirect("/post/$post_id-" . Str::slug($post->title));
            } elseif ($id != $post_id . '-' . Str::slug($post->title)) {
                return redirect("/post/$post_id-" . Str::slug($post->title));
            }

            // $user_id = auth()->user()->id;

            $view = view('posts.view', [
                'tytul' => $post->title,
                'autor' => $post->user->name,
                'avatar' => $post->user->avatar,
                'autor_id' => $post->user->id,
                'clip_url' => $post->clip_url,
                'likes' => $post->likes,
                'tags' => $post->tags,
                // 'time' => $this->getPostDate($post->created_at),
                'time' => $this->postService->getFormattedTimeDifference($post->created_at),
                'post_id' => $post->id,
                // 'is_liked' => Helpers::isLiked($user_id, 'post_id', $post->id),
                'is_liked' => false,
                // 'is_rank' => Helpers::hasRank($user_id, ['Mod', 'Admin']),
                'is_rank' => false,
            ]);

            // $response = ;
            return response($view, 200);
        } else { // post nie istnieje
            // view 404 page
            return redirect('/');
        }
    }
}
