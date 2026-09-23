<?php

namespace Modules\Academic\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Academic\app\Events\CounselorEvents\CounselorCreated;
use Modules\Academic\app\Events\CounselorEvents\CounselorDeleted;
use Modules\Academic\app\Events\CounselorEvents\CounselorRestored;
use Modules\Academic\app\Events\CounselorEvents\CounselorSectionAssigned;
use Modules\Academic\app\Events\CounselorEvents\CounselorSectionUnassigned;
use Modules\Academic\app\Events\CounselorEvents\CounselorStatusToggled;
use Modules\Academic\app\Events\CounselorEvents\CounselorUpdated;
use Modules\Academic\app\Events\GuardianEvens\GuardianCreated;
use Modules\Academic\app\Events\GuardianEvens\GuardianDeleted;
use Modules\Academic\app\Events\GuardianEvens\GuardianRestored;
use Modules\Academic\app\Events\GuardianEvens\GuardianUpdated;
use Modules\Academic\app\Events\GuardianEvens\StudentAttachedToGuardian;
use Modules\Academic\app\Events\GuardianEvens\StudentDetachedFromGuardian;
use Modules\Academic\app\Events\InspectionProgramEvents\CounselorAssignedToInspectionProgram;
use Modules\Academic\app\Events\InspectionProgramEvents\CounselorUnassignedFromInspectionProgram;
use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramCreated;
use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramDeleted;
use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramRestored;
use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramSetCurrent;
use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramStatusUpdated;
use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramUpdated;
use Modules\Academic\app\Events\InspectionProgramEvents\ObservationSubmitted;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestApproved;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestCreated;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestDeleted;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestRejected;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestUpdated;
use Modules\Academic\app\Events\StudentAttendance\StudentAttendanceBulkRecorded;
use Modules\Academic\app\Events\StudentAttendance\StudentAttendanceDeleted;
use Modules\Academic\app\Events\StudentAttendance\StudentAttendanceRecorded;
use Modules\Academic\app\Events\StudentAttendance\StudentAttendanceUpdated;
use Modules\Academic\app\Events\StudentEvents\MedicalRecordUpdated;
use Modules\Academic\app\Events\StudentEvents\StudentAssignedToSection;
use Modules\Academic\app\Events\StudentEvents\StudentDeleted;
use Modules\Academic\app\Events\StudentEvents\StudentPromoted;
use Modules\Academic\app\Events\StudentEvents\StudentRestored;
use Modules\Academic\app\Events\StudentEvents\StudentStatusUpdated;
use Modules\Academic\app\Events\StudentEvents\StudentTransferred;
use Modules\Academic\app\Events\StudentEvents\StudentUpdated;
use Modules\Academic\app\Events\StudentPointEvents\StudentPointDeleted;
use Modules\Academic\app\Events\StudentPointEvents\StudentPointGiven;
use Modules\Academic\app\Events\StudentPointEvents\StudentPointsBulkGiven;
use Modules\Academic\app\Events\SubjectsEvents\SubjectCreated;
use Modules\Academic\app\Events\SubjectsEvents\SubjectDeleted;
use Modules\Academic\app\Events\SubjectsEvents\SubjectRestored;
use Modules\Academic\app\Events\SubjectsEvents\SubjectUpdated;
use Modules\Academic\app\Events\SubjectsEvents\TeacherAssignedToSubject;
use Modules\Academic\app\Events\SubjectsEvents\TeacherUnassignedFromSubject;
use Modules\Academic\app\Events\TeacherAttendance\TeacherAttendanceDeleted;
use Modules\Academic\app\Events\TeacherAttendance\TeacherAttendanceRecorded;
use Modules\Academic\app\Events\TeacherAttendance\TeacherAttendanceUpdated;
use Modules\Academic\app\Events\TeacherEvents\QualificationAdded;
use Modules\Academic\app\Events\TeacherEvents\QualificationDeleted;
use Modules\Academic\app\Events\TeacherEvents\TeacherCreated;
use Modules\Academic\app\Events\TeacherEvents\TeacherDeleted;
use Modules\Academic\app\Events\TeacherEvents\TeacherRestored;
use Modules\Academic\app\Events\TeacherEvents\TeacherStatusToggled;
use Modules\Academic\app\Events\TeacherEvents\TeacherUpdated;
use Modules\Academic\app\Events\TimetableEvents\TimetableEntryCreated;
use Modules\Academic\app\Events\TimetableEvents\TimetableEntryDeleted;
use Modules\Academic\app\Events\TimetableEvents\TimetableEntryUpdated;
use Modules\Academic\app\Listeners\Counselors\LogCounselors\LogCounselorCreated;
use Modules\Academic\app\Listeners\Counselors\LogCounselors\LogCounselorDeleted;
use Modules\Academic\app\Listeners\Counselors\LogCounselors\LogCounselorRestored;
use Modules\Academic\app\Listeners\Counselors\LogCounselors\LogCounselorSectionAssigned;
use Modules\Academic\app\Listeners\Counselors\LogCounselors\LogCounselorSectionUnassigned;
use Modules\Academic\app\Listeners\Counselors\LogCounselors\LogCounselorStatusToggled;
use Modules\Academic\app\Listeners\Counselors\LogCounselors\LogCounselorUpdated;
use Modules\Academic\app\Listeners\Counselors\NotifyCounselorSectionAssigned;
use Modules\Academic\app\Listeners\Counselors\NotifyCounselorSectionUnassigned;
use Modules\Academic\app\Listeners\Guardians\LogGuardians\LogGuardianCreated;
use Modules\Academic\app\Listeners\Guardians\LogGuardians\LogGuardianDeleted;
use Modules\Academic\app\Listeners\Guardians\LogGuardians\LogGuardianRestored;
use Modules\Academic\app\Listeners\Guardians\LogGuardians\LogGuardianUpdated;
use Modules\Academic\app\Listeners\Guardians\LogGuardians\LogStudentAttachedToGuardian;
use Modules\Academic\app\Listeners\Guardians\LogGuardians\LogStudentDetachedFromGuardian;
use Modules\Academic\app\Listeners\Guardians\NotifyStudentAttachedToGuardian;
use Modules\Academic\app\Listeners\Guardians\NotifyStudentDetachedFromGuardian;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogCounselorAssignedToInspectionProgram;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogCounselorUnassignedFromInspectionProgram;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramCreated;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramDeleted;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramRestored;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramSetCurrent;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramStatusUpdated;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramUpdated;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogObservationSubmitted;
use Modules\Academic\app\Listeners\InspectionPrograms\NotifyCounselorAssignedToInspectionProgram;
use Modules\Academic\app\Listeners\InspectionPrograms\NotifyCounselorUnassignedFromInspectionProgram;
use Modules\Academic\app\Listeners\InspectionPrograms\NotifyInspectionProgramSetCurrent;
use Modules\Academic\app\Listeners\InspectionPrograms\NotifyInspectionProgramStatusUpdated;
use Modules\Academic\app\Listeners\InspectionPrograms\NotifyObservationSubmitted;
use Modules\Academic\app\Listeners\LeaveRequests\LogLeaveRequestApproved;
use Modules\Academic\app\Listeners\LeaveRequests\LogLeaveRequestCreated;
use Modules\Academic\app\Listeners\LeaveRequests\LogLeaveRequestDeleted;
use Modules\Academic\app\Listeners\LeaveRequests\LogLeaveRequestRejected;
use Modules\Academic\app\Listeners\LeaveRequests\LogLeaveRequestUpdated;
use Modules\Academic\app\Listeners\LeaveRequests\NotifyLeaveRequestApproved;
use Modules\Academic\app\Listeners\LeaveRequests\NotifyLeaveRequestRejected;
use Modules\Academic\app\Listeners\LogSubjects\LogSubjectCreated;
use Modules\Academic\app\Listeners\LogSubjects\LogSubjectDeleted;
use Modules\Academic\app\Listeners\LogSubjects\LogSubjectRestored;
use Modules\Academic\app\Listeners\LogSubjects\LogSubjectUpdated;
use Modules\Academic\app\Listeners\LogSubjects\LogTeacherAssignedToSubject;
use Modules\Academic\app\Listeners\LogSubjects\LogTeacherUnassignedFromSubject;
use Modules\Academic\app\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceBulkRecorded;
use Modules\Academic\app\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceDeleted;
use Modules\Academic\app\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceRecorded;
use Modules\Academic\app\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceUpdated;
use Modules\Academic\app\Listeners\StudentAttendance\NotifyStudentAbsence;
use Modules\Academic\app\Listeners\StudentAttendance\NotifyStudentLate;
use Modules\Academic\app\Listeners\StudentPoints\LogStudentPoints\LogStudentPointDeleted;
use Modules\Academic\app\Listeners\StudentPoints\LogStudentPoints\LogStudentPointGiven;
use Modules\Academic\app\Listeners\StudentPoints\LogStudentPoints\LogStudentPointsBulkGiven;
use Modules\Academic\app\Listeners\StudentPoints\NotifyStudentPointDeleted;
use Modules\Academic\app\Listeners\StudentPoints\NotifyStudentPointGiven;
use Modules\Academic\app\Listeners\StudentPoints\NotifyStudentPointsBulkGiven;
use Modules\Academic\app\Listeners\Students\LogSudents\LogMedicalRecordUpdated;
use Modules\Academic\app\Listeners\Students\LogSudents\LogStudentAssignedToSection;
use Modules\Academic\app\Listeners\Students\LogSudents\LogStudentDeleted;
use Modules\Academic\app\Listeners\Students\LogSudents\LogStudentPromoted;
use Modules\Academic\app\Listeners\Students\LogSudents\LogStudentRestored;
use Modules\Academic\app\Listeners\Students\LogSudents\LogStudentStatusUpdated;
use Modules\Academic\app\Listeners\Students\LogSudents\LogStudentTransferred;
use Modules\Academic\app\Listeners\Students\LogSudents\LogStudentUpdated;
use Modules\Academic\app\Listeners\Students\NotifyStudentAssignedToSection;
use Modules\Academic\app\Listeners\Students\NotifyStudentDeleted;
use Modules\Academic\app\Listeners\Students\NotifyStudentPromoted;
use Modules\Academic\app\Listeners\Students\NotifyStudentTransferred;
use Modules\Academic\app\Listeners\TeacherAttendance\LogTeacherAttendance\LogTeacherAttendanceDeleted;
use Modules\Academic\app\Listeners\TeacherAttendance\LogTeacherAttendance\LogTeacherAttendanceRecorded;
use Modules\Academic\app\Listeners\TeacherAttendance\LogTeacherAttendance\LogTeacherAttendanceUpdated;
use Modules\Academic\app\Listeners\Teachers\LogTeachers\LogTeacherCreated;
use Modules\Academic\app\Listeners\Teachers\LogTeachers\LogTeacherDeleted;
use Modules\Academic\app\Listeners\Teachers\LogTeachers\LogTeacherQualificationAdded;
use Modules\Academic\app\Listeners\Teachers\LogTeachers\LogTeacherQualificationDeleted;
use Modules\Academic\app\Listeners\Teachers\LogTeachers\LogTeacherRestored;
use Modules\Academic\app\Listeners\Teachers\LogTeachers\LogTeacherStatusToggled;
use Modules\Academic\app\Listeners\Teachers\LogTeachers\LogTeacherUpdated;
use Modules\Academic\app\Listeners\Teachers\NotifyTeacherAssignedToSubject;
use Modules\Academic\app\Listeners\Teachers\NotifyTeacherUnassignedFromSubject;
use Modules\Academic\app\Listeners\Timetables\LogTimetables\LogTimetableEntryCreated;
use Modules\Academic\app\Listeners\Timetables\LogTimetables\LogTimetableEntryDeleted;
use Modules\Academic\app\Listeners\Timetables\LogTimetables\LogTimetableEntryUpdated;
use Modules\Academic\app\Listeners\Timetables\NotifyTimetableEntryCreated;
use Modules\Academic\app\Listeners\Timetables\NotifyTimetableEntryDeleted;
use Modules\Academic\app\Listeners\Timetables\NotifyTimetableEntryUpdated;
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
       | Student
       |--------------------------------------------------------------------------
       */

        MedicalRecordUpdated::class => [
            LogMedicalRecordUpdated::class,
        ],

        StudentAssignedToSection::class=>[
            LogStudentAssignedToSection::class,
            NotifyStudentAssignedToSection::class,
        ],

        SectionCreated::class => [
            LogSectionCreated::class,
        ],

        StudentDeleted::class => [
            LogStudentDeleted::class,
            NotifyStudentDeleted::class,
        ],

        StudentPromoted::class => [
            LogStudentPromoted::class,
            NotifyStudentPromoted::class,
        ],

        StudentRestored::class => [
            LogStudentRestored::class,
        ],

        StudentStatusUpdated::class => [
            LogStudentStatusUpdated::class,
        ],

        StudentTransferred::class => [
            LogStudentTransferred::class,
            NotifyStudentTransferred::class,
        ],

        StudentUpdated::class => [
            LogStudentUpdated::class,
        ],

        /*
       |--------------------------------------------------------------------------
       | Student Point
       |--------------------------------------------------------------------------
       */

        StudentPointDeleted::class => [
            LogStudentPointDeleted::class,
            NotifyStudentPointDeleted::class,
        ],

        StudentPointGiven::class => [
            LogStudentPointGiven::class,
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
            LogSubjectCreated::class,
        ],

        SubjectDeleted::class => [
            LogSubjectDeleted::class,
        ],

        SubjectRestored::class => [
            LogSubjectRestored::class,
        ],

        SubjectUpdated::class => [
            LogSubjectUpdated::class,
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
            LogTeacherAttendanceRecorded::class,
        ],

        TeacherAttendanceUpdated::class => [
            LogTeacherAttendanceUpdated::class,
        ],

        TeacherAttendanceDeleted::class => [
            LogTeacherAttendanceDeleted::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Teacher
        |--------------------------------------------------------------------------
        */

        QualificationAdded::class => [
            LogTeacherQualificationAdded::class,
        ],

        QualificationDeleted::class => [
            LogTeacherQualificationDeleted::class,
        ],

        TeacherCreated::class => [
            LogTeacherCreated::class,
        ],

        TeacherDeleted::class => [
            LogTeacherDeleted::class,
        ],

        TeacherRestored::class => [
            LogTeacherRestored::class,
        ],

        TeacherStatusToggled::class => [
            LogTeacherStatusToggled::class,
        ],

        TeacherUpdated::class => [
            LogTeacherUpdated::class,
        ],

      /*
      |--------------------------------------------------------------------------
      | Teacher
      |--------------------------------------------------------------------------
      */

        TimetableEntryCreated::class => [
            LogTimetableEntryCreated::class,
            NotifyTimetableEntryCreated::class,
        ],

        TimetableEntryDeleted::class => [
            LogTimetableEntryDeleted::class,
            NotifyTimetableEntryDeleted::class,
        ],

        TimetableEntryUpdated::class => [
            LogTimetableEntryUpdated::class,
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
