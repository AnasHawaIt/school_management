<?php

namespace Modules\Announcement\Enums;

enum AnnouncementStatus: string
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case PUBLISHED = 'published';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';
}
