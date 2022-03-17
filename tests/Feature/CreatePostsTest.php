<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class CreatePostsTest extends TestCase
{
    use RefreshDatabase, WithFaker, AdditionalAssertions;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_posts_creation_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)
            ->get(route('back.posts.create'))
            ->assertStatus(200);
    }

    public function test_a_post_requires_a_creator()
    {
        $title = $this->faker->sentence(4);
        $description = $this->faker->text;

        $response = $this->post(route('back.posts.store'), [
            'title' => $title,
            'description' => $description,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect( route('login'));
    }

    public function test_only_authenticated_user_can_write_posts()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('back.posts.create'));

        $response->assertOk();
        $response->assertViewIs('back.posts.create');
    }

    public function test_store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Back\PostController::class,
            'store',
            \App\Http\Requests\PostStoreRequest::class
        );
    }

    public function test_only_authenticated_user_can_publish_a_post()
    {
        $title = $this->faker->sentence(4);
        $description = $this->faker->text;
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('back.posts.store'), [
            'title' => $title,
            'description' => $description,
        ]);

        $posts = Post::query()
                    ->where('title', $title)
                    ->where('description', $description)
                    ->get();
        $this->assertCount(1, $posts);
        $response->assertRedirect(route('back.posts.index'));
        $this->assertDatabaseHas('posts', [
            'title' => $title,
            'description' => $description
        ]);
    }

    public function test_validation_when_creating_a_blog_post_without_title()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create();
        $description = $this->faker->text;

        $response = $this->actingAs($user)->post(route('back.posts.store'), [
            'description' => $description,
        ]);

        $response->assertStatus(302);
        $response->assertInvalid(['title']);
        $response->assertSessionHasErrors(['title']);
    }

    public function test_validation_when_creating_a_blog_post_without_description()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create();
        $title = $this->faker->sentence(4);

        $response = $this->actingAs($user)->post(route('back.posts.store'), [
            'title' => $title,
        ]);

        $response->assertStatus(302);
        $response->assertInvalid(['description']);
        $response->assertSessionHasErrors(['description']);
    }
}
