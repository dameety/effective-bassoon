<?php

namespace App\Cache;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class PaginatedCache
{
    public $page;
    public $model;
    public $query;
    public $cacheResetKey;

    public function __construct(string $model)
    {
        $this->model = App::make($model);
        if (! Cache::has($this->model::CACHE_RESET_KEY)) {
            $this->cacheResetKey = $this->reset();
        }
        $this->cacheResetKey = Cache::get($this->model::CACHE_RESET_KEY);
    }

    public function forPage(int $page = 1)
    {
        $this->page = $page;
        return $this;
    }

    public function forQuery(callable $query)
    {
        $this->query = $query;
        return $this;
    }

    public function getData()
    {
        if (! Cache::has($this->getCurrentPageCacheKey()) ) {
            $posts = ($this->query)();
            Cache::put($this->getCurrentPageCacheKey(), $posts);

            return $posts;
        }

        return Cache::get($this->getCurrentPageCacheKey());
    }


    public function getCurrentPageCacheKey()
    {
        return $this->model::PREFIX_FOR_PAGINATION_CACHE . $this->page . '_' . $this->cacheResetKey;
    }

    /**
     * Any paginated page using the old reset key 
     * will have to be recached next time 
     * they are visited.
     */
    public function reset()
    {
        Cache::forget( $this->model::CACHE_RESET_KEY );
        Cache::put($this->model::CACHE_RESET_KEY, Str::random(20));
        $this->cacheResetKey = Cache::get($this->model::CACHE_RESET_KEY);
    }
}
