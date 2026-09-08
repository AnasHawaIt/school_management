<?php

namespace Modules\Announcement\Enums;

enum AnnouncementAudience: string
{
    case ALL = 'all';
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case TEACHER = 'teacher';
    case PARENT = 'parent';
}
