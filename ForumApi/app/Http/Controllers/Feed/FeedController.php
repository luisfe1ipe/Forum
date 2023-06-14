<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Feed;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function store(PostRequest $request)
    {
        $data = $request->validated();

        auth()->user()->feeds()->create($data);

        return response([
            'message' => 'success',
        ], 201);
    }
}
