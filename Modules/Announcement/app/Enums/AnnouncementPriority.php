<?php

namespace Modules\Announcement\app\Enums;

enum AnnouncementPriority: string
{
    case NORMAL = 'normal';
    case IMPORTANT = 'important';
    case URGENT = 'urgent';
}
