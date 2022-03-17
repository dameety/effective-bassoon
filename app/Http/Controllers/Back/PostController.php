<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PostController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::where([
                            'user_id' => auth()->id()
                        ])
                        ->orderBy('publication_date', 'desc')
                        ->simplePaginate(Post::PAGINATION_COUNT);
        
        return view('back.posts.index', compact('posts'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('back.posts.create');
    }

    /**
     * @param \App\Http\Requests\PostStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        $post = Post::create(
                        array_merge($request->validated(), [
                            'publication_date' => Carbon::now(),
                            'user_id' => auth()->id()
                        ])
                    );

        $request->session()->flash('post.id', $post->id);

        return redirect()->route('back.posts.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Post $post)
    {
        $this->authorize('view-post');
        
        return view('back.posts.show', compact('post'));
    }
}
