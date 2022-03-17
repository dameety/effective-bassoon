<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowPostsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_anyone_can_see_list_of_posts()
    {
        $posts = Post::factory()->count(3)->create();

        $response = $this->get(route('landing'));

        $response->assertStatus(200);;
        $response->assertViewIs('front.landing');
        $response->assertViewHas('posts');

        foreach($posts as $post) {
            $response->assertSee($post->title);
            $response->assertSee($post->description);
            $response->assertSee($post->publication_date->toFormattedDateString());
        }
    }

    public function test_anyone_can_read_a_post()
    {
        $post = Post::factory()->create();

        $response = $this->get(route('front.posts.show', $post));

        $response->assertStatus(200);
        $response->assertSee($post->title);
        $response->assertSee($post->description);
        $response->assertSee($post->publication_date->toFormattedDateString());
    }

    public function test_authenticated_user_can_see_their_posts_in_dashboard()
    {
        $user = User::factory()->create();
        $posts = Post::factory(3)
                        ->create([
                            'user_id' => $user->id
                        ]);

        $response = $this->actingAs($user)->get(route('back.posts.index'));

        $response->assertStatus(200);
        foreach ($posts as $post) {
            $response->assertSee($post->title);
            $response->assertSee($post->description);
        }
    }

    public function test_authenticated_user_cannot_see_posts_created_by_others_dashbaord()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('back.posts.index'));

        $response->assertStatus(200);
        foreach ($posts as $post) {
            $response->assertDontSee($post->title);
            $response->assertDontSee($post->description);
        }
    }

    public function test_geuest_cannot_view_posts_index_admin_page()
    {
        $response = $this->get(route('back.posts.index'));

        $response->assertStatus(302);
        $response->assertRedirect( route('login'));
    }

    public function test_geuest_cannot_view_posts_show_admin_page()
    {
        $post = Post::factory()->create();

        $response = $this->get(route('back.posts.show', $post));

        $response->assertStatus(302);
        $response->assertRedirect( route('login'));
    }
}
