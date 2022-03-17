<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Cache\PaginatedCache;
use App\Http\Controllers\Controller;


class LandingController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $posts = app('PostCache')
                    ->forPage(request()->has('page') ? request()->query('page') : 1)
                    ->forQuery(
                        function () {
                            return Post::orderBy('publication_date', 'desc')->simplePaginate(Post::PAGINATION_COUNT);
                        }
                    )
                    ->getData();

        return view('front.landing', compact('posts'));
    }
}
