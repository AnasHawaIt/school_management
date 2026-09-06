<?php

namespace Modules\Attendance\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestApproved;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestCreated;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestDeleted;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestRejected;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestUpdated;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceBulkRecorded;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceDeleted;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceRecorded;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceUpdated;
use Modules\Attendance\Events\TeacherAttendance\TeacherAttendanceDeleted;
use Modules\Attendance\Events\TeacherAttendance\TeacherAttendanceRecorded;
use Modules\Attendance\Events\TeacherAttendance\TeacherAttendanceUpdated;
use Modules\Attendance\Listeners\LeaveRequests\LogLeaveRequestApproved;
use Modules\Attendance\Listeners\LeaveRequests\LogLeaveRequestCreated;
use Modules\Attendance\Listeners\LeaveRequests\LogLeaveRequestDeleted;
use Modules\Attendance\Listeners\LeaveRequests\LogLeaveRequestRejected;
use Modules\Attendance\Listeners\LeaveRequests\LogLeaveRequestUpdated;
use Modules\Attendance\Listeners\LeaveRequests\NotifyLeaveRequestApproved;
use Modules\Attendance\Listeners\LeaveRequests\NotifyLeaveRequestRejected;
use Modules\Attendance\Listeners\StudentAttendance\LogStudentAttendanceBulkRecorded;
use Modules\Attendance\Listeners\StudentAttendance\LogStudentAttendanceDeleted;
use Modules\Attendance\Listeners\StudentAttendance\LogStudentAttendanceRecorded;
use Modules\Attendance\Listeners\StudentAttendance\LogStudentAttendanceUpdated;
use Modules\Attendance\Listeners\StudentAttendance\NotifyStudentAbsence;
use Modules\Attendance\Listeners\StudentAttendance\NotifyStudentLate;
use Modules\Attendance\Listeners\TeacherAttendance\LogTeacherAttendanceDeleted;
use Modules\Attendance\Listeners\TeacherAttendance\LogTeacherAttendanceRecorded;
use Modules\Attendance\Listeners\TeacherAttendance\LogTeacherAttendanceUpdated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [

        /*
        |--------------------------------------------------------------------------
        | Leave Requests
        |--------------------------------------------------------------------------
        */

        LeaveRequestCreated::class => [
            LogLeaveRequestCreated::class,
        ],

        LeaveRequestUpdated::class => [
            LogLeaveRequestUpdated::class,
        ],

        LeaveRequestDeleted::class => [
            LogLeaveRequestDeleted::class,
        ],

        LeaveRequestApproved::class => [
            LogLeaveRequestApproved::class,
            NotifyLeaveRequestApproved::class,
        ],

        LeaveRequestRejected::class => [
            LogLeaveRequestRejected::class,
            NotifyLeaveRequestRejected::class,
        ],


        /*
        |--------------------------------------------------------------------------
        | Student Attendance
        |--------------------------------------------------------------------------
        */

        StudentAttendanceRecorded::class => [
            LogStudentAttendanceRecorded::class,
            NotifyStudentAbsence::class,
            NotifyStudentLate::class,
        ],

        StudentAttendanceUpdated::class => [
            LogStudentAttendanceUpdated::class,
        ],

        StudentAttendanceDeleted::class => [
            LogStudentAttendanceDeleted::class,
        ],

        StudentAttendanceBulkRecorded::class => [
            LogStudentAttendanceBulkRecorded::class,
        ],


        /*
        |--------------------------------------------------------------------------
        | Teacher Attendance
        |--------------------------------------------------------------------------
        */

        TeacherAttendanceRecorded::class => [
            LogTeacherAttendanceRecorded::class,
        ],

        TeacherAttendanceUpdated::class => [
            LogTeacherAttendanceUpdated::class,
        ],

        TeacherAttendanceDeleted::class => [
            LogTeacherAttendanceDeleted::class,
        ],
    ];
    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
