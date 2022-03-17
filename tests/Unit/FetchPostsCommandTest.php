<?php

namespace Tests\Unit;

use App\Models\Post;
use Tests\TestCase;
use App\Jobs\ImportBlogPosts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

class FetchPostsCommandTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_nothing_happes_when_response_is_empty()
    {
        Queue::fake();
        Http::fake([
            config('core.feed_api_url') => Http::response([
            ], 200),
        ]);

        $this->artisan('fetch:posts')
            ->expectsOutput('Successfully return from feed api but no blog post to import.')
            ->assertExitCode(1);
        Queue::assertNotPushed(ImportBlogPosts::class);
    }

    public function test_new_posts_are_fetched_successfully()
    {
        $post = Post::factory()->make();
        Queue::fake();
        Http::fake([
            config('core.feed_api_url') => Http::response([
                'title' => $post->title,
                'description' => $post->description
            ], 200),
        ]);

        $this->artisan('fetch:posts')
            ->doesntExpectOutput('Failed fetching posts')
            ->expectsOutput('Successfully fetched new blogs posts. Importing to posts now...')
            ->assertExitCode(0);
        Queue::assertPushed(ImportBlogPosts::class);
    }
}
