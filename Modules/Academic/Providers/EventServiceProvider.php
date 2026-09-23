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
use App\Events\StudentEvents\StudentAssignedToSection;
use App\Events\StudentEvents\StudentDeleted;
use App\Events\StudentEvents\StudentPromoted;
use App\Events\StudentEvents\StudentRestored;
use App\Events\StudentEvents\StudentStatusUpdated;
use App\Events\StudentEvents\StudentTransferred;
use App\Events\StudentEvents\StudentUpdated;
use App\Events\StudentPointEvents\StudentPointGiven;
use App\Events\StudentPointEvents\StudentPointsBulkGiven;
use App\Events\SubjectsEvents\SubjectDeleted;
use App\Events\SubjectsEvents\SubjectRestored;
use App\Events\SubjectsEvents\SubjectUpdated;
use App\Events\SubjectsEvents\TeacherAssignedToSubject;
use App\Events\SubjectsEvents\TeacherUnassignedFromSubject;
use App\Events\TeacherAttendance\TeacherAttendanceDeleted;
use App\Events\TeacherAttendance\TeacherAttendanceRecorded;
use App\Events\TeacherAttendance\TeacherAttendanceUpdated;
use App\Events\TeacherEvents\QualificationAdded;
use App\Events\TeacherEvents\QualificationDeleted;
use App\Events\TeacherEvents\TeacherCreated;
use App\Events\TeacherEvents\TeacherDeleted;
use App\Events\TeacherEvents\TeacherRestored;
use App\Events\TeacherEvents\TeacherStatusToggled;
use App\Events\TeacherEvents\TeacherUpdated;
use App\Events\TimetableEvents\TimetableEntryDeleted;
use App\Events\TimetableEvents\TimetableEntryUpdated;
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
use App\Listeners\LogSubjects\LogSubjectCreated;
use App\Listeners\LogSubjects\LogSubjectDeleted;
use App\Listeners\LogSubjects\LogSubjectRestored;
use App\Listeners\LogSubjects\LogSubjectUpdated;
use App\Listeners\LogSubjects\LogTeacherAssignedToSubject;
use App\Listeners\LogSubjects\LogTeacherUnassignedFromSubject;
use App\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceBulkRecorded;
use App\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceDeleted;
use App\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceRecorded;
use App\Listeners\StudentAttendance\LogStudentAttendance\LogStudentAttendanceUpdated;
use App\Listeners\StudentAttendance\NotifyStudentAbsence;
use App\Listeners\StudentAttendance\NotifyStudentLate;
use App\Listeners\StudentPoints\LogStudentPoints\LogStudentPointDeleted;
use App\Listeners\StudentPoints\LogStudentPoints\LogStudentPointGiven;
use App\Listeners\StudentPoints\LogStudentPoints\LogStudentPointsBulkGiven;
use App\Listeners\StudentPoints\NotifyStudentPointGiven;
use App\Listeners\StudentPoints\NotifyStudentPointsBulkGiven;
use App\Listeners\Students\LogSudents\LogStudentAssignedToSection;
use App\Listeners\Students\LogSudents\LogStudentDeleted;
use App\Listeners\Students\LogSudents\LogStudentPromoted;
use App\Listeners\Students\LogSudents\LogStudentRestored;
use App\Listeners\Students\LogSudents\LogStudentStatusUpdated;
use App\Listeners\Students\LogSudents\LogStudentTransferred;
use App\Listeners\Students\LogSudents\LogStudentUpdated;
use App\Listeners\Students\NotifyStudentDeleted;
use App\Listeners\Students\NotifyStudentPromoted;
use App\Listeners\Students\NotifyStudentTransferred;
use App\Listeners\TeacherAttendance\LogTeacherAttendance\LogTeacherAttendanceDeleted;
use App\Listeners\TeacherAttendance\LogTeacherAttendance\LogTeacherAttendanceRecorded;
use App\Listeners\TeacherAttendance\LogTeacherAttendance\LogTeacherAttendanceUpdated;
use App\Listeners\Teachers\LogTeachers\LogTeacherDeleted;
use App\Listeners\Teachers\LogTeachers\LogTeacherQualificationAdded;
use App\Listeners\Teachers\LogTeachers\LogTeacherQualificationDeleted;
use App\Listeners\Teachers\LogTeachers\LogTeacherRestored;
use App\Listeners\Teachers\LogTeachers\LogTeacherStatusToggled;
use App\Listeners\Teachers\LogTeachers\LogTeacherUpdated;
use App\Listeners\Teachers\NotifyTeacherUnassignedFromSubject;
use App\Listeners\Timetables\LogTimetables\LogTimetableEntryDeleted;
use App\Listeners\Timetables\LogTimetables\LogTimetableEntryUpdated;
use App\Listeners\Timetables\NotifyTimetableEntryDeleted;
use App\Listeners\Timetables\NotifyTimetableEntryUpdated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Academic\app\Events\CounselorEvents\CounselorCreated;
use Modules\Academic\app\Events\CounselorEvents\CounselorDeleted;
use Modules\Academic\app\Events\CounselorEvents\CounselorRestored;
use Modules\Academic\app\Events\CounselorEvents\CounselorSectionAssigned;
use Modules\Academic\app\Events\CounselorEvents\CounselorSectionUnassigned;
use Modules\Academic\app\Events\GuardianEvens\GuardianCreated;
use Modules\Academic\app\Events\InspectionProgramEvents\CounselorAssignedToInspectionProgram;
use Modules\Academic\app\Events\StudentEvents\MedicalRecordUpdated;
use Modules\Academic\app\Events\StudentPointEvents\StudentPointDeleted;
use Modules\Academic\app\Events\SubjectsEvents\SubjectCreated;
use Modules\Academic\app\Events\TimetableEvents\TimetableEntryCreated;
use Modules\Academic\app\Listeners\Counselors\LogCounselors\LogCounselorCreated;
use Modules\Academic\app\Listeners\Counselors\NotifyCounselorSectionAssigned;
use Modules\Academic\app\Listeners\Guardians\LogGuardians\LogGuardianCreated;
use Modules\Academic\app\Listeners\Guardians\NotifyStudentAttachedToGuardian;
use Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms\LogCounselorAssignedToInspectionProgram;
use Modules\Academic\app\Listeners\InspectionPrograms\NotifyCounselorAssignedToInspectionProgram;
use Modules\Academic\app\Listeners\StudentPoints\NotifyStudentPointDeleted;
use Modules\Academic\app\Listeners\Students\LogSudents\LogMedicalRecordUpdated;
use Modules\Academic\app\Listeners\Students\NotifyStudentAssignedToSection;
use Modules\Academic\app\Listeners\Teachers\LogTeachers\LogTeacherCreated;
use Modules\Academic\app\Listeners\Teachers\NotifyTeacherAssignedToSubject;
use Modules\Academic\app\Listeners\Timetables\LogTimetables\LogTimetableEntryCreated;
use Modules\Academic\app\Listeners\Timetables\NotifyTimetableEntryCreated;
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
