<?php

namespace Modules\Announcement\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnnouncementCache
{
    private const VERSION_KEY = 'announcements:cache:version';

    public function key(Request $request): string
    {
        $filters = $request->only(['status', 'pinned', 'search', 'sort', 'direction', 'per_page']);
        $filters['user'] = $request->user()?->id;
        ksort($filters);

        return 'announcements:v'.Cache::get(self::VERSION_KEY, 1).':'.sha1(
            json_encode($filters, JSON_THROW_ON_ERROR)
        );
    }

    public function invalidate(): void
    {
        Cache::increment(self::VERSION_KEY);
    }
}
