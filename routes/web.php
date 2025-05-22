<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\EventController;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserItemController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TenantUsersController;
use App\Http\Controllers\AssetBookingController;
use App\Http\Controllers\EventSpeakerController;
use App\Http\Controllers\JurusanProdiController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\MahasiswaUsersController;
use App\Http\Controllers\OrganizerUsersController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AttendanceEventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\StakeholderUsersController;
use App\Http\Controllers\AssetBookingEventController;
use App\Http\Controllers\DashboardOrganizerController;
use App\Http\Controllers\DashboardSuperAdminController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\DashboardAdminAssetBookingController;

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

/// Start Dashboard
Route::prefix('dashboard')->middleware('auth')->group(function () {
    // Super Admin
    Route::middleware(['role:Super Admin'])->controller(DashboardSuperAdminController::class)->group(function () {
        Route::get('/super-admin', 'index')->name('dashboard.super-admin');
        Route::get('/total-event/{year}', 'getTotalEventByYear')->name('dash.super.getTotalEventByYear');
        Route::get('/total-asset/{type}', 'getTotalAssetByType')->name('dash.super.getTotalAssetByType');
        Route::get('/get-data-asset-booking-chart', 'getDataStatusAssetBookingChart')->name('dash.super.getDataStatusAssetBookingChart');
        Route::get('/get-data-event-organizer-chart', 'getDataEventChart')->name('dash.super.getDataEventChart');
    });
    // Organizer
    Route::middleware(['role:Organizer'])->controller(DashboardOrganizerController::class)->group(function () {
        Route::get('/organizer/{shorten_name}', 'index')->name('dashboard.organizer');
        Route::get('/get-total-event/{shorten_name}/{year}', 'getDataEventByYear')->name('dash.organizer.getDataEventByYear');
        Route::get('/get-data-event-chart/{shorten_name}', 'getEventChart')->name('dash.organizer.getEventChart');
    });
    Route::controller(DashboardAdminAssetBookingController::class)->group(function () {
        Route::middleware('role:Kaur RT')->group(function () {
            Route::get('/get-total-asset/{type}', 'getDataAsset')->name('dash.kaurRT.getDataAsset');
        });
        Route::middleware('role:UPT PU')->group(function () {
            Route::get('/get-income-asset-booking/{year}', 'getAssetBookingIncome')->name('dash.uptPU.getAssetBookingIncome');
        });
        Route::middleware('role:Admin Jurusan')->group(function () {
            Route::get('/admin-jurusan/{kode_jurusan}', 'indexAdminJurusan')->name('dashboard.admin-jurusan');
            Route::get('/get-status-booking-chart/{kode_jurusan}', 'getStatusAssetBookingJurusanChart')->name('dash.admJur.getStatusAssetBookingJurusanChart');
            Route::get('/get-usage-asset-jurusan/{kode_jurusan}', 'getUsageAssetJurusanChart')->name('dash.kaurPU.getUsageAssetJurusanChart');
        });
        Route::middleware('role:Kaur RT|UPT PU')->group(function () {
            Route::get('/admin', 'indexRTPU')->name('dashboard.kaur-rt-pu');
            Route::get('/get-status-asset-booking-chart', 'getStatusAssetBookingChart')->name('dash.kaurPU.getStatusAssetBookingChart');
            Route::get('/get-usage-asset', 'getUsageAssetChart')->name('dash.kaurPU.getUsageAssetChart');
        });
    });
});
/// End Dashboard



// Home Page
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('event', 'event')->name('event');
    Route::get('event/detail_event/{id}', 'detail_event')->name('detail_event');
    Route::get('organizer', 'organizer')->name('organizer');
    Route::get('organizer/detail_organizer/{id}', 'detail_organizer')->name('detail_organizer');
    Route::get('calendar', 'calender')->name('calender');
    Route::get('tutorial', 'tutorial')->name('tutorial');

    Route::prefix('aset-bmn')->group(function () {
        Route::get('/', 'indexAsetBmn')->name('aset-bmn');
        Route::get('/detail-asset/{id}', 'getDataAssetBmn')->name('asset-bmn.getData');
        Route::get('/get-data-category/{id}', 'getDataCategoryAssetBooking')->name('asset-booking.getDataCategory');
        Route::get('/get-data-calendar-booking/{assetId}', 'getDataCalendarAssetBooking')->name('asset-booking.getDataCalendar');
        Route::get('/get-timeline-usage-asset', 'getTimelineUsageAsset')->name('asset-booking.getTimelineUsageAsset');
        // Route::get('/annual-asset-booking/{assetId}', 'getAnnualAssetBooking')->name('asset-booking.getAnnualAssetBooking');
    });
});

// Calendar Event
Route::prefix('calendar-event')->controller(CalendarEventController::class)->group(function () {
    Route::get('/get-data', 'getDataCalendarEvent')->name('calendarEvents.getData');
    Route::middleware(['auth', 'role:Super Admin|Organizer'])->group(function () {
        Route::get('/', 'calendarEvent')->name('calendarEvent');
        Route::post('/add-event-calendar', 'addEventCalendar')->name('add.calendarEvent');
        Route::put('/update-event-calendar/{id}', 'updateEventCalendar')->name('update.calendarEvent');
        Route::delete('/destroy-event-calendar/{id}', 'destroyEventCalendar')->name('destroy.calendarEvent');
    });
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
        Route::put('/block-stakeholderUser/{type}/{id}', 'blockStakeholderUser')->name('block.stakeholderUser');
    });
    // Organizer User
    Route::prefix('organizer-users')->controller(OrganizerUsersController::class)->group(function () {
        Route::get('/', 'indexOrganizer')->name('organizerUsers');
        Route::get('/get-data-organizerUser', 'getDataOrganizerUser')->name('organizerUsers.getData');
        Route::post('/add-organizeruser', 'addOrganizerUser')->name('add.organizerUser');
        Route::put('/update-organizeruser/{id}', 'updateOrganizerUser')->name('update.organizerUser');
        Route::delete('/destroy-organizeruser/{id}', 'destroyOrganizerUser')->name('destroy.organizerUser');
        Route::put('/block-organizeruser/{type}/{id}', 'blockOrganizerUser')->name('block.organizerUser');
    });
    Route::prefix('mahasiswa-users')->controller(MahasiswaUsersController::class)->group(function () {
        Route::get('/', 'indexMahasiswa')->name('mahasiswaUsers');
        Route::get('/get-data-mahasiswauser', 'getDataMahasiswaUser')->name('mahasiswaUsers.getData');
        Route::post('/add-mahasiswauser', 'addMahasiswaUser')->name('add.mahasiswaUser');
        Route::put('/update-mahasiswauser/{id}', 'updateMahasiswaUser')->name('update.mahasiswaUser');
        Route::delete('/destroy-mahasiswauser/{id}', 'destroyMahasiswaUser')->name('destroy.mahasiswaUser');
        Route::put('/block-mahasiswauser/{type}/{id}', 'blockMahasiswaUser')->name('block.mahasiswaUser');
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
Route::prefix('tenant-users')->middleware(['auth', 'role:Super Admin|UPT PU'])->controller(TenantUsersController::class)->group(function () {
    Route::get('/', 'indexTenant')->name('tenantUsers');
    Route::get('/get-data-tenantuser', 'getDataTenantUser')->name('tenantUsers.getData');
    Route::post('/add-tenantuser', 'addTenantUser')->name('add.tenantUser');
    Route::put('/update-tenantuser/{id}', 'updateTenantUser')->name('update.tenantUser');
    Route::delete('/destroy-tenantuser/{id}', 'destroyTenantUser')->name('destroy.tenantUser');
    Route::put('/block-tenantuser/{type}/{id}', 'blockTenantUser')->name('block.tenantUser');
});
// Profile management
Route::controller(ProfileController::class)->group(function () {

    // Data Profile
    Route::middleware(['auth'])->prefix('profile')->group(function () {
        Route::get('/account-setting', 'profileUserHomepage')->name('profileUserHomepage');
        Route::get('/edit-user', 'profileUserDashboard')->name('profileUserDashboard');
        Route::post('/update-data-user/{id}', 'updateUser')->name('profileUser.update');
        Route::post('/update-data-organizer/{id}', 'updateOrganizer')->name('profileOrganizer.update');
    });

    // Asset
    Route::middleware(['auth', 'profile.complete', 'role:Tenant'])->prefix('my-asset-booking')->group(function () {
        Route::get('/', 'myAssetBooking')->name('profile.myAssetBooking');
        Route::get('/get-data/{id}', 'getDataMyAssetBooking')->name('profile.getDataMyAssetBooking');
    });
    // Event
    Route::middleware(['auth', 'profile.complete', 'role:Participant'])->prefix('my-event')->group(function () {
        Route::get('/', 'myEvent')->name('profile.myEvent');
    });
});
// Route::prefix('asset-booking')->middleware(['auth', 'role:Tenant|UPT PU'])->controller(AssetBookingController::class)->group(function () {});


// Assets Management
Route::group(['middleware' => ['auth']], function () {
    Route::prefix('assets')->group(function () {

        // Routes untuk AssetsController
        Route::controller(AssetsController::class)->group(function () {

            Route::group(['middleware' => ['role:Super Admin|Kaur RT']], function () {
                Route::get('/fasilitas-umum', 'indexAssetFasum')->name('assets.fasum');
            });

            Route::group(['middleware' => ['role:Super Admin|Kaur RT|Admin Jurusan']], function () {
                Route::get('/fasilitas-jurusan/{kode_jurusan}', 'indexAssetFasjur')->name('assets.fasjur');
                Route::get('/get-data-asset/{kode_jurusan?}', 'getDataAsset')->name('assets.getData');

                Route::get('/add-asset/{kode_jurusan?}', 'addAssetPage')->name('add.assetPage');
                Route::post('/store-asset', 'storeAsset')->name('store.asset');
                Route::put('/store-update-asset/{id}', 'updateAsset')->name('update.asset');
                Route::get('/update-asset/{id}', 'updateAssetPage')->name('update.assetPage');
                Route::delete('/destroy-asset/{id}', 'destroyAsset')->name('destroy.asset');
            });
        });
        // Routes untuk AssetBookingController
        Route::prefix('bookings')->controller(AssetBookingController::class)->group(function () {

            // Dashboard Booking Fasilitas Umum (Super Admin, Kaur RT, UPT PU)
            Route::group(['middleware' => ['role:Super Admin|UPT PU']], function () {
                Route::get('/fasilitas-bmn', 'assetBookingFasum')->name('asset.fasum.bookings');
                Route::get('get-data-asset-category/{id}', 'getDataAssetCategory')->name('getDataAssetCategory');
                Route::post('/add-asset-booking-eksternal', 'assetBookingManualEksternal')->name('assetBookingEksternal.addManual');
                Route::post('/confirm-payment-booking-eksternal/{id}', 'confirmPaymentEksternal')->name(name: 'confirmPaymentEksternal');
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

                // Upload Surat Disposisi
                Route::post('/upload-surat-disposisi/{id}', 'uploadSuratDisposisi')->name('assetBooking.uploadSuratDisposisi');
            });
            // Tenant Bisa Melakukan Booking Aset
            Route::group(['middleware' => ['auth', 'profile.complete', 'role:Tenant']], function () {
                Route::post('/asset-booking/{id}', 'assetBooking')->name('asset.booking.tenant');
                Route::post('/asset-rebooking/{id}', 'assetRebooking')->name('asset.rebooking.tenant');
                Route::post('/pay-complete-file-booking/{id}', 'payAndCompleteFile')->name('assetBooking.payAndCompleteFile');
                Route::post('/pay-infull-booking/{id}', 'payInFull')->name('assetBooking.payInFull');
            });

            // Tenant atau UPT PU/Admin Jurusan/Super Adminn membatalkan booking
            Route::post('/asset-booking/{id}/cancelled', 'cancelledBooking')->name('assetBooking.cancelled')
                ->middleware(['role:Super Admin|Kaur RT|UPT PU|Admin Jurusan|Tenant|Organizer']);
        });
    });
});

// Team Members Management
Route::group(['middleware' => ['auth', 'role:Super Admin|Organizer']], function () {
    Route::prefix('team_members')->controller(TeamMemberController::class)->group(function () {
        Route::get('/data/{shorten_name}', 'teamMemberPage')->name('data.team-members');
        Route::get('/get-data/{shorten_name}', 'getDataTeamMembers')->name('team-members.getData');
        Route::post('/add-team-member', 'addTeamMember')->name('add.team-member');
        Route::put('/update-team-member/{id}', 'updateTeamMember')->name('update.team-member');
        Route::delete('/destroy-team-member/{id}', 'destroyTeamMember')->name('destroy.team-member');
    });
});
// Events Management
Route::prefix('events')->group(function () {
    Route::controller(EventController::class)->group(function () {
        // ✅ Route untuk Super Admin & Organizer
        Route::middleware(['auth', 'role:Super Admin|Organizer'])->group(function () {
            Route::get('/data/{shorten_name}', 'eventPage')->name('data.events');
            Route::get('/get-data/{shorten_name}', 'getDataEvents')->name('events.getData');
            Route::get('/add-event', 'addEventPage')->name('add.event.page');
            Route::post('/store-event', 'storeEvent')->name('store.event');
            Route::get('/update-event/{id}', 'updateEventPage')->name('update.event.page');
            Route::put('/store-update-event/{id}', 'updateEvent')->name('update.event');
            Route::get('/detail-event/{id}', 'detailEventPage')->name('detail.event.page');
            Route::delete('/destroy-event/{id}', 'destroyEvent')->name('destroy.event');
        });
        // ✅ Route untuk Super Admin 
        Route::middleware(['auth', 'role:Super Admin|Organizer'])->group(function () {
            Route::get('/list-event', 'listEventPage')->name('data.listEvent');
            Route::get('/get-data-list-event', 'getDataListEvent')->name('listEvent.getData');
            Route::put('/block-event/{type}/{id}', 'blockEvent')->name('block.event');
        });
    });
    Route::controller(EventParticipantController::class)->group(function () {
        // Halaman Kelola Data Event Participant (Super Admin)
        Route::middleware(['auth', 'role:Super Admin'])->group(function () {
            Route::get('/event-participant', 'indexParticipant')->name('events.indexParticipant');
            Route::get('/get-event-participants', 'getDataParticipantsForAdmin')->name('events.getDataParticipantsForAdmin');
        });
        Route::middleware(['auth', 'role:Super Admin|Organizer'])->group(function () {
            // Data Participant
            Route::get('/get-data-participants/{id}', 'getDataParticipants')->name('events.getDataParticipants');
            Route::post('/confirm-registration-participants/{id}', 'confirmRegistration')->name('events.confirmRegistration');

            // CRUD Participant
            Route::post('/add-event-participant{eventId}', 'addEventParticipant')->name('add.eventParticipant');
            Route::put('/update-event-participant/{id}', 'updateEventParticipant')->name('update.eventParticipant');
            // Route::delete('/destroy-event-participant/{id}', 'destroyEventParticipant')->name('destroy.eventParticipant');
            Route::put('/block-event-participant/{type}/{id}', 'blockEventParticipant')->name('block.eventParticipant');

            // Export Participant
            Route::get('/export-participants/{eventId}', 'exportExcel')->name('participants.export-excel');
        });
        // ✅ Route untuk peserta (Participant) saja
        Route::middleware(['auth', 'profile.complete', 'role:Participant'])->group(function () {
            Route::post('/register/{id}', 'registerEvent')->name('register.event');
            Route::post('/repeat-register/{id}', 'repeatRegisterEvent')->name('repeatRegister.event');
        });

        Route::get('/e-ticket/{participant}', 'eTicket')->name('e-ticket.event')->middleware(['auth', 'role:Super Admin|Organizer|Participant']);
    });
    Route::controller(AssetBookingEventController::class)->group(function () {
        // Asset Booking Event
        Route::middleware(['auth', 'role:Organizer'])->group(function () {
            Route::get('/get-data-assetBooking/{id}', 'getDataAssetBookingEventsOrg')->name('assetBookingEvent.getData');
            Route::put('/asset-booking/{id}', 'updateAssetBooking')->name('assetBookingEvent.update');
            Route::post('/upload-doc-asset-booking/{eventId}', 'uploadDocumentAssetBooking')->name('assetBookingEvent.uploadDocument');
            Route::post('/get-loan-form/{id}', 'getLoanForm')->name('getLoanForm');
        });
        Route::group(['middleware' => ['auth', 'role:Super Admin|Kaur RT|Admin Jurusan']], function () {
            Route::post('/confirm-asset-booking-event/{eventId}', 'confirmAssetBookingEvent')->name('assetBookingEvent.confirm');
            Route::post('/cancel-asset-booking-event/{eventId}', 'cancelAssetBookingEvent')->name('assetBookingEvent.cancel');
            Route::get('/get-data-asset-booking-event/{kode_jurusan?}', 'getDataAssetBookingEvent')->name('assets.getDataBookingEvent');
            Route::post('/confirm-doc-asset-booking-event/{eventId}', 'confirmDocument')->name('assetBookingEvent.confirmDocument');
            Route::post('/confirm-done-asset-booking-event/{id}', 'confirmDone')->name('assetBookingEvent.confirmDone');

            // Tambah Aset Booking secara manual
            Route::post('/add-asset-booking', 'assetBookingManualInternal')->name('assetBookingEvent.addManual');
        });
        // Dashboard Booking Fasilitas Umum (Kaur RT)
        Route::group(['middleware' => ['auth', 'role:Super Admin|Kaur RT']], function () {
            Route::get('/fasilitas-umum', 'indexAssetBookingEvent')->name('asset.fasum.eventBookings');
        });
    });
    Route::middleware(['auth', 'role:Super Admin|Organizer'])->group(function () {
        Route::controller(AttendanceEventController::class)->group(function () {
            // get data member 
            Route::get('/get-data-members', 'getDataMembers')->name('events.getDataMembers');
            // Presensi Panitia Satu Persatu
            Route::post('/attendance-member/{memberId}/{checkType}', action: 'attendanceMember')->name('events.attendanceMember');
            // Presensi Semua Panitia (Sekaligus)
            Route::post('/attendance-member-all/{eventStepId}/{checkType}', action: 'attendanceMemberAll')->name('events.attendanceMemberAll');

            // get data participant 
            Route::get('/get-data-event-participants', 'getDataEventParticipants')->name('events.getDataEventParticipants');
            // Presensi Participant (Scan or Input)
            Route::post('/attendance-participant', action: 'attendanceParticipant')->name('events.attendanceParticipant');

            // get data attendance
            Route::get('/get-data-participant-attendance/{id}', 'getDataParticipantAttendance')->name('events.getDataParticipantAttendance');

            // Export Presensi Event
            Route::get('/export-attendance/{eventId}/{category}', 'exportExcel')->name('attendance.export-excel');
        });
        Route::controller(EventSpeakerController::class)->group(function () {
            // Data Speaker
            Route::get('/get-data-speakers/{id}', 'getDataSpeakers')->name('events.getDataSpeakers');
            Route::post('/add-speaker', 'addSpeaker')->name('add.speaker');
            Route::put('/update-speaker/{id}', 'updateSpeaker')->name('update.speaker');
            Route::delete('/destroy-speaker/{id}', 'destroySpeaker')->name('destroy.speaker');
            // Surat Undangan
            // Route::post('data-invitation-speaker/{id}', 'dataInvitationSpeaker')->name('data-invitation.speaker');
            Route::post('invitation-speaker/{id}', 'invitationSpeaker')->name('invitation.speaker');
        });
    });
});


Route::middleware(['role:Tenant|Participant'])->controller(UserItemController::class)->group(function () {
    Route::get('/item-saved/{itemType}', 'savedItem')->name('item.saved');
    Route::post('/saved/toggle', 'toggleSavedItem')->name('saved.item.toggle');
    Route::post('/saved/check', 'checkSavedItem')->name('saved.item.check');
    Route::post('/saved/remove-all', 'removeAllItem')->name('saved.item.removeAll');
});

// Notifikasi
Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead'])
    ->middleware('auth')
    ->name('notifications.read');
