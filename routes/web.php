<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DojangController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentCategoryController;
use App\Http\Controllers\StudentBillController;
use App\Http\Controllers\StudentPaymentController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\DojangSubscriptionController;
use App\Http\Controllers\DojangPaymentController;
use App\Http\Controllers\OwnerBillingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])
        ->name('dashboard');
});

Route::middleware(['auth', 'verified', 'role:super_admin'])
    ->group(function () {
        Route::resource('features', FeatureController::class);
        Route::resource('subscription-plans', SubscriptionPlanController::class);

        Route::get('/dojangs/{dojang}/subscription/edit', [DojangSubscriptionController::class, 'edit'])
            ->name('dojangs.subscription.edit');

        Route::put('/dojangs/{dojang}/subscription', [DojangSubscriptionController::class, 'update'])
            ->name('dojangs.subscription.update');
        
        Route::get('/dojang-payments', [DojangPaymentController::class, 'index'])
            ->name('dojang-payments.index');
    
        Route::post('/dojang-payments/generate-monthly', [DojangPaymentController::class, 'generateMonthly'])
            ->name('dojang-payments.generate-monthly');
    
        Route::get('/dojang-payments/{dojangPayment}', [DojangPaymentController::class, 'show'])
            ->name('dojang-payments.show');
    
        Route::patch('/dojang-payments/{dojangPayment}/mark-as-paid', [DojangPaymentController::class, 'markAsPaid'])
            ->name('dojang-payments.mark-as-paid');
    
        Route::patch('/dojang-payments/{dojangPayment}/mark-as-failed', [DojangPaymentController::class, 'markAsFailed'])
            ->name('dojang-payments.mark-as-failed');
    
        Route::patch('/dojang-payments/{dojangPayment}/cancel', [DojangPaymentController::class, 'cancel'])
            ->name('dojang-payments.cancel');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/billing', [OwnerBillingController::class, 'index'])
        ->name('owner.billing.index');

    Route::get('/billing/{dojangPayment}', [OwnerBillingController::class, 'show'])
        ->name('owner.billing.show');
});

Route::middleware(['auth', 'verified', 'role:super_admin|owner'])
    ->group(function () {
        Route::resource('dojangs', DojangController::class);
    });

Route::middleware(['auth', 'verified', 'role:super_admin|owner'])
    ->group(function () {
        Route::resource('rooms', RoomController::class);
    });

Route::middleware(['auth', 'verified', 'role:super_admin|owner'])
    ->group(function () {
        Route::resource('schedules', ScheduleController::class);
    });

Route::middleware(['auth', 'verified', 'role:super_admin|owner'])
    ->group(function () {
        Route::resource('trainers', TrainerController::class);
        Route::resource('students', StudentController::class);

        Route::post('/students/{student}/rooms', [StudentController::class, 'attachRoom'])
            ->name('students.rooms.attach');

        Route::delete('/students/{student}/rooms/{room}', [StudentController::class, 'detachRoom'])
            ->name('students.rooms.detach');
    });

Route::middleware(['auth', 'verified', 'role:super_admin|owner|trainer'])
    ->group(function () {
        Route::get('/attendances', [AttendanceController::class, 'index'])
            ->name('attendances.index');

        Route::get('/attendances/scan', [AttendanceController::class, 'scan'])
            ->name('attendances.scan');

        Route::post('/attendances/scan', [AttendanceController::class, 'storeScan'])
            ->name('attendances.scan.store');

        Route::get('/attendances/{schedule}/{date}', [AttendanceController::class, 'show'])
            ->name('attendances.show');
        
        Route::post('/attendances/manual', [AttendanceController::class, 'storeManual'])
            ->name('attendances.manual.store');
        
        Route::resource('payment-categories', PaymentCategoryController::class);

        Route::resource('student-bills', StudentBillController::class);

        Route::get('/student-bills/{studentBill}/payments/create', [StudentPaymentController::class, 'create'])
            ->name('student-payments.create');

        Route::post('/student-bills/{studentBill}/payments', [StudentPaymentController::class, 'store'])
            ->name('student-payments.store');

        Route::get('/student-payments', [StudentPaymentController::class, 'index'])
            ->name('student-payments.index');

        Route::get('/student-payments/{studentPayment}', [StudentPaymentController::class, 'show'])
            ->name('student-payments.show');

        Route::get('/student-payments/{studentPayment}/edit', [StudentPaymentController::class, 'edit'])
            ->name('student-payments.edit');

        Route::put('/student-payments/{studentPayment}', [StudentPaymentController::class, 'update'])
            ->name('student-payments.update');

        Route::patch('/student-payments/{studentPayment}/cancel', [StudentPaymentController::class, 'cancel'])
            ->name('student-payments.cancel');

        Route::delete('/student-payments/{studentPayment}', [StudentPaymentController::class, 'destroy'])
            ->name('student-payments.destroy');
    });
/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superAdmin'])
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Owner Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'owner'])
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Trainer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:trainer'])
    ->prefix('trainer')
    ->name('trainer.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'trainer'])
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'student'])
            ->name('dashboard');
    });

require __DIR__.'/auth.php';
