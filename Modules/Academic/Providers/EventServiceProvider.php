<?php

namespace Modules\Academic\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Academic\Events\CounselorEvents\CounselorCreated;
use Modules\Academic\Events\CounselorEvents\CounselorDeleted;
use Modules\Academic\Events\CounselorEvents\CounselorRestored;
use Modules\Academic\Events\CounselorEvents\CounselorSectionAssigned;
use Modules\Academic\Events\CounselorEvents\CounselorSectionUnassigned;
use Modules\Academic\Events\CounselorEvents\CounselorStatusToggled;
use Modules\Academic\Events\CounselorEvents\CounselorUpdated;
use Modules\Academic\Events\GuardianEvens\GuardianCreated;
use Modules\Academic\Events\GuardianEvens\GuardianDeleted;
use Modules\Academic\Events\GuardianEvens\GuardianRestored;
use Modules\Academic\Events\GuardianEvens\GuardianUpdated;
use Modules\Academic\Events\GuardianEvens\StudentAttachedToGuardian;
use Modules\Academic\Events\GuardianEvens\StudentDetachedFromGuardian;
use Modules\Academic\Events\InspectionProgramEvents\CounselorAssignedToInspectionProgram;
use Modules\Academic\Events\InspectionProgramEvents\CounselorUnassignedFromInspectionProgram;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramCreated;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramDeleted;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramRestored;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramSetCurrent;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramStatusUpdated;
use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramUpdated;
use Modules\Academic\Events\InspectionProgramEvents\ObservationSubmitted;
use Modules\Academic\Events\LeaveRequests\LeaveRequestApproved;
use Modules\Academic\Events\LeaveRequests\LeaveRequestCreated;
use Modules\Academic\Events\LeaveRequests\LeaveRequestDeleted;
use Modules\Academic\Events\LeaveRequests\LeaveRequestRejected;
use Modules\Academic\Events\LeaveRequests\LeaveRequestUpdated;
use Modules\Academic\Events\StudentAttendance\StudentAttendanceBulkRecorded;
use Modules\Academic\Events\StudentAttendance\StudentAttendanceDeleted;
use Modules\Academic\Events\StudentAttendance\StudentAttendanceRecorded;
use Modules\Academic\Events\StudentAttendance\StudentAttendanceUpdated;
use Modules\Academic\Events\StudentEvents\MedicalRecordUpdated;
use Modules\Academic\Events\StudentEvents\StudentAssignedToSection;
use Modules\Academic\Events\StudentEvents\StudentDeleted;
use Modules\Academic\Events\StudentEvents\StudentPromoted;
use Modules\Academic\Events\StudentEvents\StudentRestored;
use Modules\Academic\Events\StudentEvents\StudentStatusUpdated;
use Modules\Academic\Events\StudentEvents\StudentTransferred;
use Modules\Academic\Events\StudentEvents\StudentUpdated;
use Modules\Academic\Events\StudentPointEvents\StudentPointDeleted;
use Modules\Academic\Events\StudentPointEvents\StudentPointGiven;
use Modules\Academic\Events\StudentPointEvents\StudentPointsBulkGiven;
use Modules\Academic\Events\SubjectsEvents\SubjectCreated;
use Modules\Academic\Events\SubjectsEvents\SubjectDeleted;
use Modules\Academic\Events\SubjectsEvents\SubjectRestored;
use Modules\Academic\Events\SubjectsEvents\SubjectUpdated;
use Modules\Academic\Events\SubjectsEvents\TeacherAssignedToSubject;
use Modules\Academic\Events\SubjectsEvents\TeacherUnassignedFromSubject;
use Modules\Academic\Events\TeacherAttendance\TeacherAttendanceDeleted;
use Modules\Academic\Events\TeacherAttendance\TeacherAttendanceRecorded;
use Modules\Academic\Events\TeacherAttendance\TeacherAttendanceUpdated;
use Modules\Academic\Events\TeacherEvents\QualificationAdded;
use Modules\Academic\Events\TeacherEvents\QualificationDeleted;
use Modules\Academic\Events\TeacherEvents\TeacherCreated;
use Modules\Academic\Events\TeacherEvents\TeacherDeleted;
use Modules\Academic\Events\TeacherEvents\TeacherRestored;
use Modules\Academic\Events\TeacherEvents\TeacherStatusToggled;
use Modules\Academic\Events\TeacherEvents\TeacherUpdated;
use Modules\Academic\Events\TimetableEvents\TimetableEntryCreated;
use Modules\Academic\Events\TimetableEvents\TimetableEntryDeleted;
use Modules\Academic\Events\TimetableEvents\TimetableEntryUpdated;
use Modules\Academic\Listeners\Counselors\LogCounselors\LogCounselorSectionAssigned;
use Modules\Academic\Listeners\Counselors\LogCounselors\LogCounselorSectionUnassigned;
use Modules\Academic\Listeners\Counselors\NotifyCounselorSectionAssigned;
use Modules\Academic\Listeners\Counselors\NotifyCounselorSectionUnassigned;
use Modules\Academic\Listeners\Guardians\LogGuardians\LogStudentAttachedToGuardian;
use Modules\Academic\Listeners\Guardians\LogGuardians\LogStudentDetachedFromGuardian;
use Modules\Academic\Listeners\Guardians\NotifyStudentAttachedToGuardian;
use Modules\Academic\Listeners\Guardians\NotifyStudentDetachedFromGuardian;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogCounselorAssignedToInspectionProgram;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogCounselorUnassignedFromInspectionProgram;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogObservationSubmitted;
use Modules\Academic\Listeners\InspectionPrograms\NotifyCounselorAssignedToInspectionProgram;
use Modules\Academic\Listeners\InspectionPrograms\NotifyCounselorUnassignedFromInspectionProgram;
use Modules\Academic\Listeners\InspectionPrograms\NotifyInspectionProgramSetCurrent;
use Modules\Academic\Listeners\InspectionPrograms\NotifyInspectionProgramStatusUpdated;
use Modules\Academic\Listeners\InspectionPrograms\NotifyObservationSubmitted;
use Modules\Academic\Listeners\LeaveRequests\NotifyLeaveRequestApproved;
use Modules\Academic\Listeners\LeaveRequests\NotifyLeaveRequestRejected;
use Modules\Academic\Listeners\LogSubjects\LogTeacherAssignedToSubject;
use Modules\Academic\Listeners\LogSubjects\LogTeacherUnassignedFromSubject;
use Modules\Academic\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceBulkRecorded;
use Modules\Academic\Listeners\StudentAttendance\NotifyStudentAbsence;
use Modules\Academic\Listeners\StudentAttendance\NotifyStudentLate;
use Modules\Academic\Listeners\StudentPoints\LogStudentPoints\LogStudentPointsBulkGiven;
use Modules\Academic\Listeners\StudentPoints\NotifyStudentPointDeleted;
use Modules\Academic\Listeners\StudentPoints\NotifyStudentPointGiven;
use Modules\Academic\Listeners\StudentPoints\NotifyStudentPointsBulkGiven;
use Modules\Academic\Listeners\Students\LogSudents\LogStudentAssignedToSection;
use Modules\Academic\Listeners\Students\NotifyStudentAssignedToSection;
use Modules\Academic\Listeners\Students\NotifyStudentDeleted;
use Modules\Academic\Listeners\Students\NotifyStudentPromoted;
use Modules\Academic\Listeners\Students\NotifyStudentTransferred;
use Modules\Academic\Listeners\Teachers\NotifyTeacherAssignedToSubject;
use Modules\Academic\Listeners\Teachers\NotifyTeacherUnassignedFromSubject;
use Modules\Academic\Listeners\Timetables\NotifyTimetableEntryCreated;
use Modules\Academic\Listeners\Timetables\NotifyTimetableEntryDeleted;
use Modules\Academic\Listeners\Timetables\NotifyTimetableEntryUpdated;
use Modules\School\Events\SectionCreated;
use Modules\School\Listeners\LogSectionCreated;

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
            ],
        CounselorDeleted::class => [
        ],
        CounselorUpdated::class => [
        ],

        CounselorRestored::class => [
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
        ],

        /*
       |--------------------------------------------------------------------------
       | Guardian
       |--------------------------------------------------------------------------
       */

        GuardianCreated::class => [
        ],

        GuardianDeleted::class => [
        ],

        GuardianUpdated::class => [
        ],

        GuardianRestored::class=>[
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
        ],

        InspectionProgramDeleted::class=>[
        ],

        InspectionProgramRestored::class=>[
        ],

        InspectionProgramSetCurrent::class=>[
            NotifyInspectionProgramSetCurrent::class,
        ],

        InspectionProgramStatusUpdated::class=>[
            NotifyInspectionProgramStatusUpdated::class,
        ],

        InspectionProgramUpdated::class=>[
        ],

        ObservationSubmitted::class=>[
            LogObservationSubmitted::class,
            NotifyObservationSubmitted::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Leave Requests
        |--------------------------------------------------------------------------
        */

        LeaveRequestCreated::class => [
        ],

        LeaveRequestUpdated::class => [
        ],

        LeaveRequestDeleted::class => [
        ],

        LeaveRequestApproved::class => [
            NotifyLeaveRequestApproved::class,
        ],

        LeaveRequestRejected::class => [
            NotifyLeaveRequestRejected::class,
        ],


        /*
        |--------------------------------------------------------------------------
        | Student Attendance
        |--------------------------------------------------------------------------
        */

        StudentAttendanceRecorded::class => [
            NotifyStudentAbsence::class,
            NotifyStudentLate::class,
        ],

        StudentAttendanceUpdated::class => [
        ],

        StudentAttendanceDeleted::class => [
        ],

        StudentAttendanceBulkRecorded::class => [
            LogStudentAttendanceBulkRecorded::class,
        ],

        /*
       |--------------------------------------------------------------------------
       | Student
       |--------------------------------------------------------------------------
       */

        MedicalRecordUpdated::class => [
        ],

        StudentAssignedToSection::class=>[
            LogStudentAssignedToSection::class,
            NotifyStudentAssignedToSection::class,
        ],

        SectionCreated::class => [
            LogSectionCreated::class,
        ],

        StudentDeleted::class => [
            NotifyStudentDeleted::class,
        ],

        StudentPromoted::class => [
            NotifyStudentPromoted::class,
        ],

        StudentRestored::class => [
        ],

        StudentStatusUpdated::class => [
        ],

        StudentTransferred::class => [
            NotifyStudentTransferred::class,
        ],

        StudentUpdated::class => [
        ],

        /*
       |--------------------------------------------------------------------------
       | Student Point
       |--------------------------------------------------------------------------
       */

        StudentPointDeleted::class => [
            NotifyStudentPointDeleted::class,
        ],

        StudentPointGiven::class => [
            NotifyStudentPointGiven::class,
        ],

        StudentPointsBulkGiven::class => [
            LogStudentPointsBulkGiven::class,
            NotifyStudentPointsBulkGiven::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Subject
        |--------------------------------------------------------------------------
        */

        SubjectCreated::class => [
        ],

        SubjectDeleted::class => [
        ],

        SubjectRestored::class => [
        ],

        SubjectUpdated::class => [
        ],

        TeacherAssignedToSubject::class=>[
            LogTeacherAssignedToSubject::class,
            NotifyTeacherAssignedToSubject::class,
        ],

        TeacherUnassignedFromSubject::class=>[
            LogTeacherUnassignedFromSubject::class,
            NotifyTeacherUnassignedFromSubject::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Teacher Attendance
        |--------------------------------------------------------------------------
        */

        TeacherAttendanceRecorded::class => [
        ],

        TeacherAttendanceUpdated::class => [
        ],

        TeacherAttendanceDeleted::class => [
        ],

        /*
        |--------------------------------------------------------------------------
        | Teacher
        |--------------------------------------------------------------------------
        */

        QualificationAdded::class => [
        ],

        QualificationDeleted::class => [
        ],

        TeacherCreated::class => [
        ],

        TeacherDeleted::class => [
        ],

        TeacherRestored::class => [
        ],

        TeacherStatusToggled::class => [
        ],

        TeacherUpdated::class => [
        ],

      /*
      |--------------------------------------------------------------------------
      | Teacher
      |--------------------------------------------------------------------------
      */

        TimetableEntryCreated::class => [
            NotifyTimetableEntryCreated::class,
        ],

        TimetableEntryDeleted::class => [
            NotifyTimetableEntryDeleted::class,
        ],

        TimetableEntryUpdated::class => [
            NotifyTimetableEntryUpdated::class,
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
