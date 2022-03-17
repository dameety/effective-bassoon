<?php

namespace Tests\Unit;

use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PostsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_it_has_correct_cache_key()
    {
        $post = Post::factory()->create();

        $this->assertEquals('post-' . $post->id , $post->cachekey());
    }

    public function test_it_belongs_to_a_creator()
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf('\App\Models\User', $post->creator);
    }
}
