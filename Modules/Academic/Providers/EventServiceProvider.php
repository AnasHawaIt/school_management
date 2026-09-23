<?php

namespace Modules\Academic\Providers;

use App\Events\CounselorEvents\CounselorStatusToggled;
use App\Events\CounselorEvents\CounselorUpdated;
use App\Events\GuardianEvens\GuardianDeleted;
use App\Events\GuardianEvens\GuardianRestored;
use App\Events\GuardianEvens\GuardianUpdated;
use App\Events\GuardianEvens\StudentAttachedToGuardian;
use App\Events\GuardianEvens\StudentDetachedFromGuardian;
use App\Events\InspectionProgramEvents\CounselorUnassignedFromInspectionProgram;
use App\Events\InspectionProgramEvents\InspectionProgramCreated;
use App\Events\InspectionProgramEvents\InspectionProgramDeleted;
use App\Events\InspectionProgramEvents\InspectionProgramRestored;
use App\Events\InspectionProgramEvents\InspectionProgramSetCurrent;
use App\Events\InspectionProgramEvents\InspectionProgramStatusUpdated;
use App\Events\InspectionProgramEvents\InspectionProgramUpdated;
use App\Events\InspectionProgramEvents\ObservationSubmitted;
use App\Events\LeaveRequests\LeaveRequestApproved;
use App\Events\LeaveRequests\LeaveRequestCreated;
use App\Events\LeaveRequests\LeaveRequestDeleted;
use App\Events\LeaveRequests\LeaveRequestRejected;
use App\Events\LeaveRequests\LeaveRequestUpdated;
use App\Events\StudentAttendance\StudentAttendanceBulkRecorded;
use App\Events\StudentAttendance\StudentAttendanceDeleted;
use App\Events\StudentAttendance\StudentAttendanceRecorded;
use App\Events\StudentAttendance\StudentAttendanceUpdated;
use App\Events\TeacherAttendance\TeacherAttendanceDeleted;
use App\Events\TeacherAttendance\TeacherAttendanceRecorded;
use App\Events\TeacherAttendance\TeacherAttendanceUpdated;
use App\Listeners\Counselors\LogCounselors\LogCounselorDeleted;
use App\Listeners\Counselors\LogCounselors\LogCounselorRestored;
use App\Listeners\Counselors\LogCounselors\LogCounselorSectionAssigned;
use App\Listeners\Counselors\LogCounselors\LogCounselorSectionUnassigned;
use App\Listeners\Counselors\LogCounselors\LogCounselorStatusToggled;
use App\Listeners\Counselors\LogCounselors\LogCounselorUpdated;
use App\Listeners\Counselors\NotifyCounselorSectionUnassigned;
use App\Listeners\Guardians\LogGuardians\LogGuardianDeleted;
use App\Listeners\Guardians\LogGuardians\LogGuardianRestored;
use App\Listeners\Guardians\LogGuardians\LogGuardianUpdated;
use App\Listeners\Guardians\LogGuardians\LogStudentAttachedToGuardian;
use App\Listeners\Guardians\LogGuardians\LogStudentDetachedFromGuardian;
use App\Listeners\Guardians\NotifyStudentDetachedFromGuardian;
use App\Listeners\InspectionPrograms\LogInspectionPrograms\LogCounselorUnassignedFromInspectionProgram;
use App\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramCreated;
use App\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramDeleted;
use App\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramRestored;
use App\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramSetCurrent;
use App\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramStatusUpdated;
use App\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramUpdated;
use App\Listeners\InspectionPrograms\LogInspectionPrograms\LogObservationSubmitted;
use App\Listeners\InspectionPrograms\NotifyCounselorUnassignedFromInspectionProgram;
use App\Listeners\InspectionPrograms\NotifyInspectionProgramSetCurrent;
use App\Listeners\InspectionPrograms\NotifyInspectionProgramStatusUpdated;
use App\Listeners\InspectionPrograms\NotifyObservationSubmitted;
use App\Listeners\LeaveRequests\LogLeaveRequestApproved;
use App\Listeners\LeaveRequests\LogLeaveRequestCreated;
use App\Listeners\LeaveRequests\LogLeaveRequestDeleted;
use App\Listeners\LeaveRequests\LogLeaveRequestRejected;
use App\Listeners\LeaveRequests\LogLeaveRequestUpdated;
use App\Listeners\LeaveRequests\NotifyLeaveRequestApproved;
use App\Listeners\LeaveRequests\NotifyLeaveRequestRejected;
use App\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceBulkRecorded;
use App\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceDeleted;
use App\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceRecorded;
use App\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceUpdated;
use App\Listeners\StudentAttendance\NotifyStudentAbsence;
use App\Listeners\StudentAttendance\NotifyStudentLate;
use App\Listeners\TeacherAttendance\LogTeacherAttendance\LogTeacherAttendanceDeleted;
use App\Listeners\TeacherAttendance\LogTeacherAttendance\LogTeacherAttendanceRecorded;
use App\Listeners\TeacherAttendance\LogTeacherAttendance\LogTeacherAttendanceUpdated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Academic\app\Events\CounselorEvents\CounselorCreated;
use Modules\Academic\app\Events\CounselorEvents\CounselorDeleted;
use Modules\Academic\app\Events\CounselorEvents\CounselorRestored;
use Modules\Academic\app\Events\CounselorEvents\CounselorSectionAssigned;
use Modules\Academic\app\Events\CounselorEvents\CounselorSectionUnassigned;
use Modules\Academic\app\Events\GuardianEvens\GuardianCreated;
use Modules\Academic\app\Events\InspectionProgramEvents\CounselorAssignedToInspectionProgram;
use Modules\Academic\app\Listeners\Counselors\LogCounselors\LogCounselorCreated;
use Modules\Academic\app\Listeners\Counselors\NotifyCounselorSectionAssigned;
use Modules\Academic\app\Listeners\Guardians\LogGuardians\LogGuardianCreated;
use Modules\Academic\app\Listeners\Guardians\NotifyStudentAttachedToGuardian;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogCounselorAssignedToInspectionProgram;
use Modules\Academic\app\Listeners\InspectionPrograms\NotifyCounselorAssignedToInspectionProgram;

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
        | Counselor
        |--------------------------------------------------------------------------
        */

        CounselorCreated::class => [
            LogCounselorCreated::class,
            ],
        CounselorDeleted::class => [
            LogCounselorDeleted::class,
        ],
        CounselorUpdated::class => [
            LogCounselorUpdated::class,
        ],

        CounselorRestored::class => [
            LogCounselorRestored::class,
        ],

        CounselorSectionAssigned::class => [
            LogCounselorSectionAssigned::class,
            NotifyCounselorSectionAssigned::class,
        ],

        CounselorSectionUnassigned::class => [
            LogCounselorSectionUnassigned::class,
            NotifyCounselorSectionUnassigned::class,
        ],

        CounselorStatusToggled::class=>[
            LogCounselorStatusToggled::class,
        ],

        /*
       |--------------------------------------------------------------------------
       | Guardian
       |--------------------------------------------------------------------------
       */

        GuardianCreated::class => [
            LogGuardianCreated::class,
        ],

        GuardianDeleted::class => [
            LogGuardianDeleted::class,
        ],

        GuardianUpdated::class => [
            LogGuardianUpdated::class,
        ],

        GuardianRestored::class=>[
            LogGuardianRestored::class,
        ],

        StudentAttachedToGuardian::class=>[
            LogStudentAttachedToGuardian::class,
            NotifyStudentAttachedToGuardian::class,
        ],

        StudentDetachedFromGuardian::class=>[
            LogStudentDetachedFromGuardian::class,
            NotifyStudentDetachedFromGuardian::class,
        ],

         /*
          |--------------------------------------------------------------------------
          | Inspection Program
          |--------------------------------------------------------------------------
          */

        CounselorAssignedToInspectionProgram::class=>[
            LogCounselorAssignedToInspectionProgram::class,
            NotifyCounselorAssignedToInspectionProgram::class,
        ],

        CounselorUnassignedFromInspectionProgram::class=>[
            LogCounselorUnassignedFromInspectionProgram::class,
            NotifyCounselorUnassignedFromInspectionProgram::class,
        ],

        InspectionProgramCreated::class=>[
            LogInspectionProgramCreated::class,
        ],

        InspectionProgramDeleted::class=>[
            LogInspectionProgramDeleted::class,
        ],

        InspectionProgramRestored::class=>[
            LogInspectionProgramRestored::class,
        ],

        InspectionProgramSetCurrent::class=>[
            LogInspectionProgramSetCurrent::class,
            NotifyInspectionProgramSetCurrent::class,
        ],

        InspectionProgramStatusUpdated::class=>[
            LogInspectionProgramStatusUpdated::class,
            NotifyInspectionProgramStatusUpdated::class,
        ],

        InspectionProgramUpdated::class=>[
            LogInspectionProgramUpdated::class,
        ],

        ObservationSubmitted::class=>[
            LogObservationSubmitted::class,
            NotifyObservationSubmitted::class,
        ],
زز
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
