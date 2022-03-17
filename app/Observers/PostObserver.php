<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "Saved" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function saved(Post $post)
    {
        app('PostCache')->reset();
    }
}
