<?php

namespace Modules\Announcement\app\Enums;

enum AnnouncementStatus: string
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case PUBLISHED = 'published';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';
}
