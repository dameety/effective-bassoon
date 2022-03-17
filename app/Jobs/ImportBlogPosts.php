<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportBlogPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->attempts() > 1) {
            Log::warning('The importBlogPosts Job is being retried for the ' . $this->attempts() . ' time');
        }

        $feeds = Feed::unImported();

        foreach($feeds as $feed) {
            $rawPostData = json_decode($feed->response_body, true)['data'];

            $data = [];
            foreach($rawPostData as $post) {
                array_push($data,
                    array_merge($post, [
                        'user_id' => User::admin()->id
                    ])
                );
            }
            Post::upsert($data, 'title');

            $feed->imported();
        }
    }

    /** 
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::error($exception->getMessage());
        // Send user notification of failure, etc...
    }
}
