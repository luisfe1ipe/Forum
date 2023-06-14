<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Feed;
use App\Models\Like;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        $feeds = Feed::with('user')->latest()->get();

        return response([
            'feeds' => $feeds
        ], 200);
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        auth()->user()->feeds()->create($data);

        return response([
            'message' => 'success',
        ], 201);
    }

    public function likePost($feed_id)
    {
        $feed = Feed::find($feed_id);

        if (!$feed) {
            return response([
                'message' => 'feed not found',
            ], 404);
        }

        $unlike_post = Like::where('user_id', auth()->id())->where('feed_id', $feed_id)->delete();
        if ($unlike_post) {
            return response([
                'message' => 'unliked',
            ], 200);
        }

        $like_post = Like::create([
            'user_id' => auth()->id(),
            'feed_id' => $feed_id
        ]);

        if ($like_post) {
            return response([
                'message' => 'liked',
            ], 200);
        }
    }
}
