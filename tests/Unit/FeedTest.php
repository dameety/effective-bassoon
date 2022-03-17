<?php

namespace Tests\Unit;

use App\Models\Feed;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class FeedTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_can_change_import_status_to_imported()
    {
        $feed = Feed::factory()->create([
            'import_status' => false
        ]);
        $feed->imported();

        $this->assertTrue($feed->refresh()->import_status);
    }

    public function test_can_get_only_unimported_feeds()
    {
        Feed::factory(2)->create([
            'import_status' => true
        ]);
        Feed::factory(4)->create([
            'import_status' => false
        ]);

        $this->assertEquals(4, count(Feed::unImported()));

        foreach(Feed::unImported() as $feed) {
            $this->assertFalse($feed->import_status);
        }
    }
}
