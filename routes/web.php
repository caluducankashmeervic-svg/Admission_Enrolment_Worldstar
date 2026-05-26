<?php

use App\Http\Controllers\Admin\AcademicTermController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageEnrolleesController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Applicant\AdmissionController;
use App\Http\Controllers\Applicant\PreRegistrationController;
use App\Http\Controllers\Applicant\StatusController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Document\CORController;
use App\Http\Controllers\Exam\ExamResultController;
use App\Http\Controllers\Exam\ExamScheduleController;
use App\Http\Controllers\Registrar\ApplicantListController;
use App\Http\Controllers\Registrar\EnrollmentController;
use App\Http\Controllers\Registrar\EnrollmentListController;
use App\Http\Controllers\Registrar\VerificationController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'courses' => Course::where('is_active', true)->orderBy('code')->get(),
    ]);
})->name('home');

/* ---------------- About (public info pages) ---------------- */
Route::prefix('about')->name('about.')->group(function () {
    Route::view('/story',          'about.story')->name('story');
    Route::view('/philosophy',     'about.philosophy')->name('philosophy');
    Route::view('/vision-mission', 'about.vision-mission')->name('vision-mission');
    Route::view('/core-values',    'about.core-values')->name('core-values');
});

/* ---------------- Auth ---------------- */
Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::get('/register',  [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

/* ---------------- Applicant (public pre-registration) ---------------- */
Route::prefix('apply')->name('applicant.')->group(function () {
    // Program-type chooser (entry point)
    Route::get('/',  [PreRegistrationController::class, 'choose'])->name('pre-register.form');
    Route::get('/choose', [PreRegistrationController::class, 'choose'])->name('pre-register.choose');

    // Reference-code apply (bind existing pre-registration to a new account)
    Route::get('/code',  [PreRegistrationController::class, 'codeForm'])->name('code.form');
    Route::post('/code', [PreRegistrationController::class, 'codeCheck'])->name('code.check');

    // Senior High School flow
    Route::get('/shs',  [PreRegistrationController::class, 'createShs'])->name('pre-register.shs.form');
    Route::post('/shs', [PreRegistrationController::class, 'storeShs'])->name('pre-register.shs.store');

    // TESDA / Diploma flow
    Route::get('/tesda',  [PreRegistrationController::class, 'createTesda'])->name('pre-register.tesda.form');
    Route::post('/tesda', [PreRegistrationController::class, 'storeTesda'])->name('pre-register.tesda.store');

    Route::get('/success/{code}', [PreRegistrationController::class, 'success'])
        ->name('pre-register.success');

    Route::get('/status',  [StatusController::class, 'form'])->name('status.form');
    Route::post('/status', [StatusController::class, 'check'])->name('status.check');
});

/* ---------------- Authenticated profile ---------------- */
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Authenticated student admission form
    Route::get('/admission',  [AdmissionController::class, 'create'])->name('applicant.admission.create');
});

/* ---------------- Admin ---------------- */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',          DashboardController::class)->name('dashboard');
    Route::get('/dashboard', DashboardController::class)->name('dashboard.alt');

    Route::get('/courses',            [CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses',           [CourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{course}',   [CourseController::class, 'update'])->name('courses.update');

    Route::get('/sections',  [SectionController::class, 'index'])->name('sections.index');
    Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');

    Route::get('/terms',             [AcademicTermController::class, 'index'])->name('terms.index');
    Route::post('/terms',            [AcademicTermController::class, 'store'])->name('terms.store');
    Route::put('/terms/{term}',      [AcademicTermController::class, 'update'])->name('terms.update');
    Route::post('/terms/{term}/activate',
        [AcademicTermController::class, 'activate'])->name('terms.activate');

    Route::get('/users',           [UserController::class, 'index'])->name('users.index');
    Route::post('/users',          [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}',    [UserController::class, 'update'])->name('users.update');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');

    Route::get('/enrollees',                    [ManageEnrolleesController::class, 'index'])->name('enrollees.index');
    Route::get('/enrollees/{applicant}',        [ManageEnrolleesController::class, 'show'])->name('enrollees.show');
    Route::post('/enrollees/{applicant}/confirm',[ManageEnrolleesController::class, 'confirm'])->name('enrollees.confirm');
    Route::post('/enrollees/{applicant}/deny',  [ManageEnrolleesController::class, 'deny'])->name('enrollees.deny');

    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/trends',           [AnalyticsController::class, 'trends'])->name('trends');
        Route::get('/course-capacities',[AnalyticsController::class, 'courseCapacities'])->name('capacities');
        Route::get('/demographics',     [AnalyticsController::class, 'demographics'])->name('demographics');
    });
});

/* ---------------- Exam (admin + registrar) ---------------- */
Route::middleware(['auth', 'role:admin,registrar'])->prefix('exam')->name('exam.')->group(function () {
    Route::get('/schedule',  [ExamScheduleController::class, 'index'])->name('schedule.index');
    Route::post('/schedule', [ExamScheduleController::class, 'store'])->name('schedule.store');

    // Manual roster overrides (Q2). Auto-assignment is performed on registrar approval.
    Route::post('/schedule/{schedule}/applicants/{applicant}/remove',
        [ExamScheduleController::class, 'removeApplicant'])->name('schedule.applicant.remove');
    Route::post('/schedule/{schedule}/applicants/{applicant}/move',
        [ExamScheduleController::class, 'moveApplicant'])->name('schedule.applicant.move');

    Route::get('/results/{schedule}',          [ExamResultController::class, 'index'])->name('results.index');
    Route::post('/results/{schedule}/scores',  [ExamResultController::class, 'postScores'])->name('results.post');
});

/* ---------------- Registrar ---------------- */
Route::middleware(['auth', 'role:registrar,admin'])->prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/lookup',           [VerificationController::class, 'lookup'])->name('lookup');
    Route::post('/lookup',          [VerificationController::class, 'find'])->name('lookup.find');
    Route::get('/verify/{applicant}',  [VerificationController::class, 'show'])->name('verify.show');
    Route::post('/verify/{applicant}', [VerificationController::class, 'store'])->name('verify.store');
    Route::post('/approve/{applicant}',[VerificationController::class, 'approve'])->name('approve');
    Route::post('/reject/{applicant}', [VerificationController::class, 'reject'])->name('reject');

    Route::get('/enrollment/{applicant}',   [EnrollmentController::class, 'show'])->name('enrollment.show');
    Route::post('/enrollment/finalize',     [EnrollmentController::class, 'finalize'])->name('enrollment.finalize');

    Route::get('/applicants',         [ApplicantListController::class, 'index'])->name('applicants.index');
    Route::get('/applicants/export',  [ApplicantListController::class, 'export'])->name('applicants.export');

    Route::get('/enrollments',        [EnrollmentListController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/export', [EnrollmentListController::class, 'export'])->name('enrollments.export');
});

/* ---------------- Documents ---------------- */
Route::middleware('auth')->prefix('enrollment')->name('enrollment.')->group(function () {
    Route::get('/cor/{enrollment}/download', [CORController::class, 'download'])->name('cor.download');
    Route::get('/cor/{enrollment}/preview',  [CORController::class, 'preview'])->name('cor.preview');
});
