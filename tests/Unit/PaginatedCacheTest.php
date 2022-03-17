<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaginatedCacheTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_cache_reset_key_changes_when_a_model_is_created()
    {
        $currentResetKey = app('PostCache')->cacheResetKey;

        Post::factory()->create();

        $this->assertNotEquals($currentResetKey, app('PostCache')->cacheResetKey);
    }

    public function test_cache_uses_valid_page_key()
    {
        Cache::flush();
        $page = random_int(1, 10000);
        $cache = app('PostCache')->forPage($page);
        $cachePageKey = Post::PREFIX_FOR_PAGINATION_CACHE . $page . '_' . Cache::get(Post::CACHE_RESET_KEY);

        $this->assertEquals($cachePageKey, $cache->getCurrentPageCacheKey());
    }

    public function test_page_query_is_returned_from_cache_if_present()
    {
        $cacheRepositorySpy = Mockery::spy(Cache::driver());
        Cache::swap($cacheRepositorySpy);
        $page = random_int(1, 10000);
        Post::factory(3)->create();
        $cachedContent = Post::first();

        $cache = app('PostCache')
                    ->forPage($page)
                    ->forQuery(
                        function () {
                            return Post::orderBy('publication_date', 'desc')->get();
                        }
                    );

        Cache::put($cache->getCurrentPageCacheKey(), $cachedContent);
        $cache->getData();

        $cacheRepositorySpy->shouldHaveReceived('get')
                        ->with($cache->getCurrentPageCacheKey());
        $store = $cacheRepositorySpy->getStore();
        $this->assertEquals($cachedContent, $store->get($cache->getCurrentPageCacheKey()));
    }

    public function test_page_query_is_returned_from_db_if_absent_in_cache()
    {
        $cacheRepositorySpy = Mockery::spy(Cache::driver());
        Cache::swap($cacheRepositorySpy);
        $page = random_int(1, 10000);
        Post::factory(3)->create();

        $cache = app('PostCache')
                    ->forPage($page)
                    ->forQuery(
                        function () {
                            return Post::orderBy('publication_date', 'desc')->get();
                        }
                    );

        $this->assertFalse( Cache::has($cache->getCurrentPageCacheKey()) );
        $cache->getData();
        $cacheRepositorySpy->shouldNotReceive('get')
                        ->with($cache->getCurrentPageCacheKey());
    }

    public function test_page_query_is_returned_from_db_if_a_new_model_is_created()
    {
        $cacheRepositorySpy = Mockery::spy(Cache::driver());
        Cache::swap($cacheRepositorySpy);
        $page = random_int(1, 10000);
        Post::factory(3)->create();
        $cachedContent = Post::first();

        $cache = app('PostCache')
                    ->forPage($page)
                    ->forQuery(
                        function () {
                            return Post::orderBy('publication_date', 'desc')->get();
                        }
                    );

        Cache::put($cache->getCurrentPageCacheKey(), $cachedContent);
        $this->assertTrue( Cache::has($cache->getCurrentPageCacheKey()) );
        $this->assertNotNull( $cache->getData() );

        Post::factory()->create();
        $cache->getData();

        $cacheRepositorySpy->shouldNotReceive('get')
                        ->with($cache->getCurrentPageCacheKey());
    }
}
