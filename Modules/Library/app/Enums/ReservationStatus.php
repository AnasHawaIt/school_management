<?php

namespace Modules\Library\app\Enums;

enum ReservationStatus: string
{
        /**
         * الحجز قيد الانتظار
         */
        case PENDING = 'pending';

        /**
         * تم إشعار العضو بأن الكتاب أصبح متاحاً
         */
        case NOTIFIED = 'notified';

        /**
         * تم تنفيذ الحجز واستلام الكتاب
         */
        case FULFILLED = 'fulfilled';

        /**
         * تم إلغاء الحجز
         */
        case CANCELLED = 'cancelled';

        /**
         * انتهت صلاحية الحجز
         */
        case EXPIRED = 'expired';
}

