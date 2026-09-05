<?php

namespace Modules\Academic\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Academic\Events\CounselorEvents\CounselorCreated;
use Modules\Academic\Events\CounselorEvents\CounselorDeleted;
use Modules\Academic\Events\CounselorEvents\CounselorRestored;
use Modules\Academic\Events\CounselorEvents\CounselorSectionAssigned;
use Modules\Academic\Events\CounselorEvents\CounselorSectionUnassigned;
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
use Modules\Academic\Events\StudentEvents\MedicalRecordUpdated;
use Modules\Academic\Events\StudentEvents\StudentAssignedToSection;
use Modules\Academic\Events\StudentEvents\StudentCreated;
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
use Modules\Academic\Listeners\Counselors\LogCounselors\LogCounselorCreated;
use Modules\Academic\Listeners\Counselors\LogCounselors\LogCounselorDeleted;
use Modules\Academic\Listeners\Counselors\LogCounselors\LogCounselorRestored;
use Modules\Academic\Listeners\Counselors\LogCounselors\LogCounselorSectionAssigned;
use Modules\Academic\Listeners\Counselors\LogCounselors\LogCounselorSectionUnassigned;
use Modules\Academic\Listeners\Counselors\LogCounselors\LogCounselorUpdated;
use Modules\Academic\Listeners\Counselors\NotifyCounselorSectionAssigned;
use Modules\Academic\Listeners\Counselors\NotifyCounselorSectionUnassigned;
use Modules\Academic\Listeners\Guardians\LogGuardians\LogGuardianCreated;
use Modules\Academic\Listeners\Guardians\LogGuardians\LogGuardianDeleted;
use Modules\Academic\Listeners\Guardians\LogGuardians\LogGuardianRestored;
use Modules\Academic\Listeners\Guardians\LogGuardians\LogGuardianUpdated;
use Modules\Academic\Listeners\Guardians\LogGuardians\LogStudentAttachedToGuardian;
use Modules\Academic\Listeners\Guardians\LogGuardians\LogStudentDetachedFromGuardian;
use Modules\Academic\Listeners\Guardians\NotifyStudentAttachedToGuardian;
use Modules\Academic\Listeners\Guardians\NotifyStudentDetachedFromGuardian;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogCounselorAssignedToInspectionProgram;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogCounselorUnassignedFromInspectionProgram;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramCreated;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramDeleted;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramRestored;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramSetCurrent;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramStatusUpdated;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogInspectionProgramUpdated;
use Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms\LogObservationSubmitted;
use Modules\Academic\Listeners\InspectionPrograms\NotifyCounselorAssignedToInspectionProgram;
use Modules\Academic\Listeners\InspectionPrograms\NotifyCounselorUnassignedFromInspectionProgram;
use Modules\Academic\Listeners\InspectionPrograms\NotifyInspectionProgramSetCurrent;
use Modules\Academic\Listeners\InspectionPrograms\NotifyInspectionProgramStatusUpdated;
use Modules\Academic\Listeners\InspectionPrograms\NotifyObservationSubmitted;
use Modules\Academic\Listeners\LogSubjects\LogSubjectCreated;
use Modules\Academic\Listeners\LogSubjects\LogSubjectDeleted;
use Modules\Academic\Listeners\LogSubjects\LogSubjectRestored;
use Modules\Academic\Listeners\LogSubjects\LogSubjectUpdated;
use Modules\Academic\Listeners\LogSubjects\LogTeacherAssignedToSubject;
use Modules\Academic\Listeners\LogSubjects\LogTeacherUnassignedFromSubject;
use Modules\Academic\Listeners\StudentPoints\LogStudentPoints\LogStudentPointDeleted;
use Modules\Academic\Listeners\StudentPoints\LogStudentPoints\LogStudentPointGiven;
use Modules\Academic\Listeners\StudentPoints\LogStudentPoints\LogStudentPointsBulkGiven;
use Modules\Academic\Listeners\StudentPoints\NotifyStudentPointDeleted;
use Modules\Academic\Listeners\StudentPoints\NotifyStudentPointGiven;
use Modules\Academic\Listeners\StudentPoints\NotifyStudentPointsBulkGiven;
use Modules\Academic\Listeners\Students\LogSudents\LogMedicalRecordUpdated;
use Modules\Academic\Listeners\Students\LogSudents\LogStudentAssignedToSection;
use Modules\Academic\Listeners\Students\LogSudents\LogStudentCreated;
use Modules\Academic\Listeners\Students\LogSudents\LogStudentDeleted;
use Modules\Academic\Listeners\Students\LogSudents\LogStudentPromoted;
use Modules\Academic\Listeners\Students\LogSudents\LogStudentRestored;
use Modules\Academic\Listeners\Students\LogSudents\LogStudentStatusUpdated;
use Modules\Academic\Listeners\Students\LogSudents\LogStudentTransferred;
use Modules\Academic\Listeners\Students\LogSudents\LogStudentUpdated;
use Modules\Academic\Listeners\Students\NotifyStudentAssignedToSection;
use Modules\Academic\Listeners\Students\NotifyStudentDeleted;
use Modules\Academic\Listeners\Students\NotifyStudentPromoted;
use Modules\Academic\Listeners\Students\NotifyStudentTransferred;
use Modules\Academic\Listeners\Teachers\LogTeachers\LogTeacherCreated;
use Modules\Academic\Listeners\Teachers\LogTeachers\LogTeacherDeleted;
use Modules\Academic\Listeners\Teachers\LogTeachers\LogTeacherQualificationAdded;
use Modules\Academic\Listeners\Teachers\LogTeachers\LogTeacherQualificationDeleted;
use Modules\Academic\Listeners\Teachers\LogTeachers\LogTeacherRestored;
use Modules\Academic\Listeners\Teachers\LogTeachers\LogTeacherStatusToggled;
use Modules\Academic\Listeners\Teachers\LogTeachers\LogTeacherUpdated;
use Modules\Academic\Listeners\Teachers\NotifyTeacherAssignedToSubject;
use Modules\Academic\Listeners\Teachers\NotifyTeacherUnassignedFromSubject;
use Modules\Academic\Listeners\Timetables\LogTimetables\LogTimetableEntryCreated;
use Modules\Academic\Listeners\Timetables\LogTimetables\LogTimetableEntryDeleted;
use Modules\Academic\Listeners\Timetables\LogTimetables\LogTimetableEntryUpdated;
use Modules\Academic\Listeners\Timetables\NotifyTimetableEntryCreated;
use Modules\Academic\Listeners\Timetables\NotifyTimetableEntryDeleted;
use Modules\Academic\Listeners\Timetables\NotifyTimetableEntryUpdated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [

        // =========================
        // TEACHER
        // =========================

        TeacherCreated::class => [
            LogTeacherCreated::class,
        ],

        TeacherUpdated::class => [
            LogTeacherUpdated::class,
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


        // =========================
        // TEACHER QUALIFICATIONS
        // =========================

        QualificationAdded::class => [
            LogTeacherQualificationAdded::class,
        ],

        QualificationDeleted::class => [
            LogTeacherQualificationDeleted::class,
        ],


        // =========================
        // STUDENTS
        // =========================

        StudentCreated::class => [
            LogStudentCreated::class,
        ],

        StudentUpdated::class => [
            LogStudentUpdated::class,
        ],

        StudentDeleted::class => [
            LogStudentDeleted::class,
            NotifyStudentDeleted::class,
        ],

        StudentRestored::class => [
            LogStudentRestored::class,
        ],

        StudentPromoted::class => [
            LogStudentPromoted::class,
            NotifyStudentPromoted::class,
        ],

        StudentTransferred::class => [
            LogStudentTransferred::class,
            NotifyStudentTransferred::class,
        ],

        StudentStatusUpdated::class => [
            LogStudentStatusUpdated::class,
        ],

        StudentAssignedToSection::class => [
            LogStudentAssignedToSection::class,
            NotifyStudentAssignedToSection::class,
        ],

        MedicalRecordUpdated::class => [
            LogMedicalRecordUpdated::class,
        ],


        // =========================
        // GUARDIANS
        // =========================

        GuardianCreated::class => [
            LogGuardianCreated::class,
        ],

        GuardianUpdated::class => [
            LogGuardianUpdated::class,
        ],

        GuardianDeleted::class => [
            LogGuardianDeleted::class,
        ],

        GuardianRestored::class => [
            LogGuardianRestored::class,
        ],

        StudentAttachedToGuardian::class => [
            LogStudentAttachedToGuardian::class,
            NotifyStudentAttachedToGuardian::class,
        ],

        StudentDetachedFromGuardian::class => [
            LogStudentDetachedFromGuardian::class,
            NotifyStudentDetachedFromGuardian::class,
        ],


        // =========================
        // COUNSELORS
        // =========================

        CounselorCreated::class => [
            LogCounselorCreated::class,
        ],

        CounselorUpdated::class => [
            LogCounselorUpdated::class,
        ],

        CounselorDeleted::class => [
            LogCounselorDeleted::class,
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


        // =========================
        // SUBJECTS
        // =========================

        SubjectCreated::class => [
            LogSubjectCreated::class,
        ],

        SubjectUpdated::class => [
            LogSubjectUpdated::class,
        ],

        SubjectDeleted::class => [
            LogSubjectDeleted::class,
        ],

        SubjectRestored::class => [
            LogSubjectRestored::class,
        ],

        TeacherAssignedToSubject::class => [
            LogTeacherAssignedToSubject::class,
            NotifyTeacherAssignedToSubject::class,
        ],

        TeacherUnassignedFromSubject::class => [
            LogTeacherUnassignedFromSubject::class,
            NotifyTeacherUnassignedFromSubject::class,
        ],


        // =========================
        // INSPECTION PROGRAMS
        // =========================

        InspectionProgramCreated::class => [
            LogInspectionProgramCreated::class,
        ],

        InspectionProgramUpdated::class => [
            LogInspectionProgramUpdated::class,
        ],

        InspectionProgramDeleted::class => [
            LogInspectionProgramDeleted::class,
        ],

        InspectionProgramRestored::class => [
            LogInspectionProgramRestored::class,
        ],

        CounselorAssignedToInspectionProgram::class => [
            LogCounselorAssignedToInspectionProgram::class,
            NotifyCounselorAssignedToInspectionProgram::class,
        ],

        CounselorUnassignedFromInspectionProgram::class => [
            LogCounselorUnassignedFromInspectionProgram::class,
            NotifyCounselorUnassignedFromInspectionProgram::class,
        ],

        ObservationSubmitted::class => [
            LogObservationSubmitted::class,
            NotifyObservationSubmitted::class,
        ],

        InspectionProgramStatusUpdated::class => [
            LogInspectionProgramStatusUpdated::class,
            NotifyInspectionProgramStatusUpdated::class,
        ],

        InspectionProgramSetCurrent::class => [
            LogInspectionProgramSetCurrent::class,
            NotifyInspectionProgramSetCurrent::class,
        ],


        // =========================
        // STUDENT POINTS
        // =========================

        StudentPointGiven::class => [
            LogStudentPointGiven::class,
            NotifyStudentPointGiven::class,
        ],

        StudentPointsBulkGiven::class => [
            LogStudentPointsBulkGiven::class,
            NotifyStudentPointsBulkGiven::class,
        ],

        StudentPointDeleted::class => [
            LogStudentPointDeleted::class,
            NotifyStudentPointDeleted::class,
        ],


        // =========================
        // TIMETABLES
        // =========================

        TimetableEntryCreated::class => [
            LogTimetableEntryCreated::class,
            NotifyTimetableEntryCreated::class,
        ],

        TimetableEntryUpdated::class => [
            LogTimetableEntryUpdated::class,
            NotifyTimetableEntryUpdated::class,
        ],

        TimetableEntryDeleted::class => [
            LogTimetableEntryDeleted::class,
            NotifyTimetableEntryDeleted::class,
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
