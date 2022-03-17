<?php

namespace App\Console\Commands;

use App\Models\Feed;
use Exception;
use App\Jobs\ImportBlogPosts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchBlogPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch new blogs posts from api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $response = Http::get(config('core.feed_api_url'));
            $response->throw();

        } catch( Exception $exception) {
            Log::error($exception->getMessage());
            $this->error('Failed fetching posts');
            //notify admin
            return 1;
        }

        if($response->body() === "[]") {
            Log::warning('Successfully return from feed api but no blog post to import.');
            $this->line('Successfully return from feed api but no blog post to import.');
            //notify admin
            return 1;
        }

        Feed::create([
            'response_body' => $response->body()
        ]);
        ImportBlogPosts::dispatch();

        $this->line('Successfully fetched new blogs posts. Importing to posts now...');
        Log::info('Successfully fetched new blogs posts. Importing to posts now...');

        return 0;
    }    
}
