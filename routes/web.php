<?php

use App\Http\Controllers\Admin\ChangeDoctorPasswordController;
use App\Http\Controllers\Admin\ChangeDoctorPriceController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Admin\ManageDoctorController;
use App\Http\Controllers\Admin\ManageDoctorScheduleController;
use App\Http\Controllers\Admin\ManagePatientController;
use App\Http\Controllers\Admin\ManagePaymentController;
use App\Http\Controllers\Admin\SearchDoctorController;
use App\Http\Controllers\Chat\FetchMessageController;
use App\Http\Controllers\Chat\SendMessageController;
use App\Http\Controllers\Patient\ConsultationController;
use App\Http\Controllers\Patient\DoctorController;
use App\Http\Controllers\Patient\PaymentController;
use App\Http\Controllers\Doctor\ConsultationController as DoctorConsultationController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\ScheduleController;
use App\Http\Controllers\Doctor\StartConsultationController;
use App\Http\Controllers\Patient\FinishConsultationController;
use App\Http\Controllers\Patient\HistoryConsultationController;
use App\Http\Controllers\Patient\ReviewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::prefix('pasien')
    ->name('pasien.')
    ->middleware(['auth', 'role:pasien', 'verified'])
    ->group(function () {
        Route::get('/dashboard', PatientDashboardController::class)->name('dashboard');

        Route::prefix('dokter')
            ->name('dokter.')
            ->group(function () {
                Route::get('/', [DoctorController::class, 'index'])->name('index');
                Route::get('/search', SearchDoctorController::class)->name('search');
                Route::get('/{doctor}', [DoctorController::class, 'show'])->name('show');
            });

        Route::prefix('konsultasi')
            ->name('konsultasi.')
            ->group(function () {
                Route::get('/', [ConsultationController::class, 'index'])->name('index');
                Route::post('/', [ConsultationController::class, 'store'])->name('store');
                Route::get('/create', [ConsultationController::class, 'create'])->name('create');
                Route::get('/riwayat', HistoryConsultationController::class)->name('riwayat');
                Route::delete('/{consultation}/delete', [ConsultationController::class, 'destroy'])->name('delete');
                Route::post('/{consultation}/selesai', FinishConsultationController::class)->name('selesai');
                Route::post('/{consultation}/review', ReviewController::class)->name('review');
            });

        Route::prefix('pembayaran')
            ->name('pembayaran.')
            ->group(function () {
                Route::get('/', [PaymentController::class, 'index'])->name('index');
                Route::get('/{payment}/konfirmasi', [PaymentController::class, 'edit'])->name('edit');
                Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
            });
    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin', 'verified'])
    ->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

        Route::prefix('pasien')
            ->name('pasien.')
            ->group(function () {
                Route::get('/', [ManagePatientController::class, 'index'])->name('index');
                Route::get('/{patient}/edit', [ManagePatientController::class, 'edit'])->name('edit');
                Route::patch('/{patient}/update', [ManagePatientController::class, 'update'])->name('update');
                Route::delete('/{patient}/delete', [ManagePatientController::class, 'destroy'])->name('delete');
            });

        Route::prefix('dokter')
            ->name('dokter.')
            ->group(function () {
                Route::get('/', [ManageDoctorController::class, 'index'])->name('index');
                Route::post('/', [ManageDoctorController::class, 'store'])->name('store');
                Route::get('/create', [ManageDoctorController::class, 'create'])->name('create');
                Route::get('/search', SearchDoctorController::class)->name('search');

                Route::put('/jadwal/{jadwal}', [ManageDoctorScheduleController::class, 'update'])->name('jadwal.update');
                Route::delete('/jadwal/{jadwal}', [ManageDoctorScheduleController::class, 'destroy'])->name('jadwal.delete');
                Route::get('/{doctor}', [ManageDoctorController::class, 'show'])->name('show');
                Route::get('/{doctor}/edit', [ManageDoctorController::class, 'edit'])->name('edit');
                Route::patch('/{doctor}/update', [ManageDoctorController::class, 'update'])->name('update');
                Route::delete('/{doctor}/delete', [ManageDoctorController::class, 'destroy'])->name('delete');
                Route::put('/{doctor}/ubah-password', ChangeDoctorPasswordController::class)->name('ubah-password');
                Route::put('/{doctor}/harga', ChangeDoctorPriceController::class)->name('harga');



                Route::get('/{doctor}/jadwal', [ManageDoctorScheduleController::class, 'edit'])->name('jadwal.edit');
                Route::post('/{doctor}/jadwal', [ManageDoctorScheduleController::class, 'store'])->name('jadwal.store');
            });

        Route::prefix('pembayaran')
            ->name('pembayaran.')
            ->group(function () {
                Route::get('/', [ManagePaymentController::class, 'index'])->name('index');
                Route::patch('/{payment}/verifikasi', [ManagePaymentController::class, 'update'])->name('update');
            });
    });

Route::prefix('dokter')
    ->name('dokter.')
    ->middleware(['auth', 'role:dokter', 'verified'])
    ->group(function () {
        Route::get('/dashboard', DoctorDashboardController::class)->name('dashboard');

        Route::resource('jadwal', ScheduleController::class)->only('index', 'store', 'update', 'destroy');

        Route::prefix('konsultasi')
            ->name('konsultasi.')
            ->group(function () {
                Route::get('/', [DoctorConsultationController::class, 'index'])->name('index');
                Route::put('/{consultation}', [DoctorConsultationController::class, 'update'])->name('update');
                Route::post('/{consultation}/mulai', StartConsultationController::class)->name('mulai');
            });
    });


Route::middleware(['auth', 'role:dokter,pasien', 'verified'])
    ->group(function () {
        Route::get('/chat/messages/{consultation}', FetchMessageController::class);
        Route::post('/chat/send/{consultation}', SendMessageController::class);
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
