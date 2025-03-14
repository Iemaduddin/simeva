<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetBookingController;
use App\Http\Controllers\JurusanProdiController;
use App\Http\Controllers\AiapplicationController;
use App\Http\Controllers\ComponentpageController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\RoleandaccessController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\MahasiswaUsersController;
use App\Http\Controllers\OrganizerUsersController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\StakeholderUsersController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Authentication
Route::middleware('guest')->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        Route::get('/login', 'showLoginPage')->name('showLoginPage');
        Route::post('/login', 'login')->name('login');
        Route::get('/register', 'showRegisterPage')->name('showRegisterPage');
        Route::post('/register', 'register')->name('register');

        // Redirect ke halaman forgot password
        Route::get('/forgotpassword', 'forgotPassword')->name('password.request');
        // Request link address/email untuk reset password
        Route::post('/forgot-password',  'requestResetPassword')->name('password.email');
        // Redirect ke halaman reset password
        Route::get('/reset-password/{token}', 'resetPasswordPage')->name('password.reset');
        // Handle untuk reset password
        Route::post('/reset-password', 'resetPassword')->name('password.update');
    });
});

Route::middleware('auth')->group(function () {
    // Menampilkan halaman untuk notice bahwa email verifikasi telah dikirimkan ke email
    Route::get('/email/verify', function () {
        return view('authentication.verify-email');
    })->name('verification.notice');

    // Handle verifikasi email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/');
    })->middleware(['signed'])->name('verification.verify');

    // Resend Verifikasi Email
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Logout
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
});
// Home Page
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('event', 'event')->name('event');
    Route::get('detail_event', 'detail_event')->name('detail_event');
    Route::get('organizer', 'organizer')->name('organizer');
    Route::get('detail_organizer', 'detail_organizer')->name('detail_organizer');
    Route::get('calender', 'calender')->name('calender');
    Route::get('tutorial', 'tutorial')->name('tutorial');
});

// Users Management
Route::group(['middleware' => ['auth', 'role:Super Admin']], function () {
    // Assign Permission
    Route::get('/get-data-permissions', [RolePermissionController::class, 'getDataPermission'])->name('permissions.getData');
    Route::put('/assign-permission-to-user/{id}', [RolePermissionController::class, 'assignPermissionToUser'])->name('assignPermissionToUser');
    //    Stakeholder User (Super Admin, Admin Jurusan, Kaur RT, UPT PU)
    Route::prefix('stakeholder-users')->controller(StakeholderUsersController::class)->group(function () {
        Route::get('/', 'indexStakeholder')->name('stakeholderUsers');
        Route::get('/get-data-stakeholderuser', 'getDataStakeholderUser')->name('stakeholderUsers.getData');
        Route::post('/add-stakeholderUser', 'addStakeholderUser')->name('add.stakeholderUser');
        Route::put('/update-stakeholderUser/{id}', 'updateStakeholderUser')->name('update.stakeholderUser');
        Route::delete('/destroy-stakeholderUser/{id}', 'destroyStakeholderUser')->name('destroy.stakeholderUser');
    });
    // Organizer User
    Route::prefix('organizer-users')->controller(OrganizerUsersController::class)->group(function () {
        Route::get('/', 'indexOrganizer')->name('organizerUsers');
        Route::get('/get-data-organizerUser', 'getDataOrganizerUser')->name('organizerUsers.getData');
        Route::post('/add-organizeruser', 'addOrganizerUser')->name('add.organizerUser');
        Route::put('/update-organizeruser/{id}', 'updateOrganizerUser')->name('update.organizerUser');
        Route::delete('/destroy-organizeruser/{id}', 'destroyOrganizerUser')->name('destroy.organizerUser');
    });
    Route::prefix('mahasiswa-users')->controller(MahasiswaUsersController::class)->group(function () {
        Route::get('/', 'indexMahasiswa')->name('mahasiswaUsers');
        Route::get('/get-data-mahasiswauser', 'getDataMahasiswaUser')->name('mahasiswaUsers.getData');
        Route::post('/add-mahasiswauser', 'addMahasiswaUser')->name('add.mahasiswaUser');
        Route::put('/update-mahasiswauser/{id}', 'updateMahasiswaUser')->name('update.mahasiswaUser');
        Route::delete('/destroy-mahasiswauser/{id}', 'destroyMahasiswaUser')->name('destroy.mahasiswaUser');
        Route::get('/get-prodi-by-jurusan',  action: 'getProdiByJurusan')->name('getProdiByJurusan');
    });

    Route::prefix('jurusan-prodi')->controller(JurusanProdiController::class)->group(function () {
        Route::get('/', 'indexJurusanProdi')->name('jurusanProdi');
        // Jurusan
        Route::get('/get-data-jurusan', 'getDataJurusan')->name('jurusan.getData');
        Route::post('/add-urusan', 'addJurusan')->name('add.jurusan');
        Route::put('/update-jurusan/{id}', 'updateJurusan')->name('update.jurusan');
        Route::delete('/destroy-jurusan/{id}', 'destroyJurusan')->name('destroy.jurusan');
        // Prodi
        Route::get('/get-data-prodi', 'getDataProdi')->name('prodi.getData');
        Route::post('/add-prodi', 'addProdi')->name('add.prodi');
        Route::put('/update-prodi/{id}', 'updateProdi')->name('update.prodi');
        Route::delete('/destroy-prodi/{id}', 'destroyProdi')->name('destroy.prodi');
    });
});
Route::middleware(['auth', 'role:Tenant|Participant'])->controller(ProfileController::class)->group(function () {
    // Data Profile
    Route::prefix('profile')->group(function () {
        Route::get('/{id}', 'profile')->name('profile');
        Route::post('/update-data-user/{id}', 'updateProfile')->name('profile.update');
    });

    // Asset
    Route::prefix('my-asset-booking')->group(function () {
        Route::get('/{id}', 'myAssetBooking')->name('profile.myAssetBooking');
        Route::get('/get-data/{id}', 'getDataMyAssetBooking')->name('profile.getDataMyAssetBooking');
    });
});
Route::prefix('asset-booking')->middleware(['auth', 'role:Tenant|UPT PU'])->controller(AssetBookingController::class)->group(function () {});


// Assets Management
Route::group(['middleware' => ['auth']], function () {
    Route::prefix('assets')->group(function () {

        // Routes untuk AssetsController
        Route::controller(AssetsController::class)->group(function () {

            Route::group(['middleware' => ['role:Super Admin|Kaur RT']], function () {
                Route::get('/fasilitas-umum', 'indexAssetFasum')->name('assets.fasum');
            });

            Route::group(['middleware' => ['role:Super Admin|Admin Jurusan']], function () {
                Route::get('/fasilitas-jurusan/{kode_jurusan}', 'indexAssetFasjur')->name('assets.fasjur');
                Route::get('/get-data-asset/{kode_jurusan?}', 'getDataAsset')->name('assets.getData');
            });

            Route::group(['middleware' => ['role:Super Admin|Kaur RT|Admin Jurusan']], function () {
                Route::post('/add-asset', 'addAsset')->name('add.asset');
                Route::put('/update-asset/{id}', 'updateAsset')->name('update.asset');
                Route::delete('/destroy-asset/{id}', 'destroyAsset')->name('destroy.asset');
            });
        });
        // Routes untuk AssetBookingController
        Route::prefix('bookings')->controller(AssetBookingController::class)->group(function () {

            // Dashboard Booking Fasilitas Umum (Super Admin, Kaur RT, UPT PU)
            Route::group(['middleware' => ['role:Super Admin|Kaur RT|UPT PU']], function () {
                Route::get('/fasilitas-umum', 'assetBookingFasum')->name('asset.fasum.bookings');
            });

            // Dashboard Booking Fasilitas Jurusan (Super Admin & Admin Jurusan)
            Route::group(['middleware' => ['role:Super Admin|Admin Jurusan']], function () {
                Route::get('/fasilitas-jurusan/{kode_jurusan}', 'assetBookingFasjur')->name('asset.fasjur.bookings');
            });


            Route::group(['middleware' => ['role:Super Admin|Kaur RT|UPT PU|Admin Jurusan']], function () {
                // Ambil Data Booking (Untuk Semua Fasilitas)
                Route::get('/get-data-asset-booking/{kode_jurusan?}', 'getDataAssetBooking')->name('assets.getDataBooking');

                // Konfirmasi Booking (Super Admin, Kaur RT, UPT PU, Admin Jurusan)
                Route::post('/confirm-booking/{id}', 'confirmBooking')->name('assetBooking.confirm');

                // Konfirmasi Pembayaran Booking (Super Admin, Kaur RT, UPT PU, Admin Jurusan)
                Route::post('/confirm-payment/{id}', 'confirmPayment')->name('assetBooking.confirmPayment');
            });
            // Tenant Bisa Melakukan Booking Aset
            Route::group(['middleware' => ['role:Tenant']], function () {
                Route::post('/asset-booking/{id}', 'assetBooking')->name('asset.booking.tenant');
                Route::post('/asset-rebooking/{id}', 'assetRebooking')->name('asset.rebooking.tenant');
                Route::post('/pay-complete-file-booking/{id}', 'payAndCompleteFile')->name('assetBooking.payAndCompleteFile');
                Route::post('/pay-infull-booking/{id}', 'payInFull')->name('assetBooking.payInFull');
            });

            // Tenant atau UPT PU/Admin Jurusan/Super Adminn membatalkan booking
            Route::post('/asset-booking/{id}/cancelled', 'cancelledBooking')->name('assetBooking.cancelled')
                ->middleware(['role:Super Admin|Kaur RT|UPT PU|Admin Jurusan|Tenant']);
        });
    });
});


Route::prefix('aset-bmn')->controller(AssetsController::class)->group(function () {
    Route::get('/', 'indexAsetBmn')->name('aset-bmn');
    Route::get('/{id}', 'getDataAssetBmn')->name('asset-bmn.getData');
    Route::get('/get-data-category/{id}', 'getDataCategoryAssetBooking')->name('asset-booking.getDataCategory');
    Route::get('/get-data-calendar-booking/{assetId}', 'getDataCalendarAssetBooking')->name('asset-booking.getDataCalendar');
});

// Notifikasi
Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead'])
    ->middleware('auth')
    ->name('notifications.read');
