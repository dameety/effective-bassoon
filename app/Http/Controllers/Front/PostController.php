<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Post $post)
    {
        if (! Cache::has($post->cachekey()) ) {
            Cache::put($post->cachekey(), $post);
        }

        $post = Cache::get($post->cachekey());

        return view('front.posts.show', [
            'post' => $post
        ]);
    }
}
