<?php

namespace Modules\Announcement\Enums;

enum AnnouncementPriority: string
{
    case NORMAL = 'normal';
    case IMPORTANT = 'important';
    case URGENT = 'urgent';
}
