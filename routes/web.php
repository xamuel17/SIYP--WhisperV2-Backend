<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebControllers\AdminUserController;
use App\Http\Controllers\WebControllers\AppUserController;
use App\Http\Controllers\WebControllers\DashboardController;
use App\Http\Controllers\WebControllers\AdminPostController;
use App\Http\Controllers\WebControllers\DistressMessageController;
use App\Http\Controllers\WebControllers\HarmspotController;
use App\Http\Controllers\WebControllers\UserTrackingController;
use App\Http\Controllers\WebControllers\ForgotPasswordController;
use App\Http\Controllers\WebControllers\OffendersController;
use App\Http\Controllers\WebControllers\AppRulesController;
use App\Http\Controllers\WebControllers\PostReportController;

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

Route::group(['middleware' => 'auth:sanctum', 'verified'], function () {
    // dashboard routes
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // admin user routes
    Route::get('/admin/browse', [AdminUserController::class, 'browseAdmin'])->name('admin.browse');
    Route::get('/admin/new', [AdminUserController::class, 'newAdmin'])->name('admin.new');
    Route::post('/admin/new', [AdminUserController::class, 'createAdmin'])->name('admin.create');
    Route::get('/admin/change-password', [AdminUserController::class, 'changePassword'])->name('admin.change-password');
    Route::post('/admin/change-password', [AdminUserController::class, 'changePasswordPost'])->name('admin.change-password');
    Route::get('/admin/profile', [AdminUserController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/save-profile', [AdminUserController::class, 'saveProfile'])->name('admin.save-profile');

    // app user routes
    Route::get('/user/browse', [AppUserController::class, 'browseUser'])->name('user.browse');
    Route::get('/user/view/{user}', [AppUserController::class, 'viewUser'])->name('user.view');
    Route::get('/user/deactivate/{user}', [AppUserController::class, 'deactivateUser'])->name('user.deactivate');
    Route::get('/user/reactivate/{user}', [AppUserController::class, 'reactivateUser'])->name('user.reactivate');
    Route::get('/user/send-message/{user}', [AppUserController::class, 'sendMessage'])->name('user.send-message');
    Route::post('/user/send-message', [AppUserController::class, 'sendMessagePost'])->name('user.send-message-post');

    // admin post routes
    Route::get('/admin/posts', [AdminPostController::class, 'index'])->name('admin.posts');
    Route::get('/admin/posts/new', [AdminPostController::class, 'newPost'])->name('admin.posts.new');
    Route::post('/admin/posts/store', [AdminPostController::class, 'store'])->name('admin.posts.store');
    Route::get('/admin/posts/show/{post}', [AdminPostController::class, 'show'])->name('admin.posts.show');
    Route::post('/admin/posts/update/{post}', [AdminPOstController::class, 'update'])->name('admin.posts.update');
    Route::get('/admin/posts/publish/{post}', [AdminPostController::class, 'publish'])->name('admin.posts.publish');
    Route::get('/admin/posts/hide/{post}', [AdminPostController::class, 'hide'])->name('admin.posts.hide');
    Route::get('/admin/posts/delete/{post}', [AdminPostController::class, 'destroy'])->name('admin.posts.delete');
    Route::post('/admin/posts/reply-comment/{comment}', [AdminPostController::class, 'replyComment'])->name('admin.posts.reply-comment');
    Route::get('/admin/posts/delete-reply/{reply}', [AdminPostController::class, 'deleteReply'])->name('admin.posts.delete-reply');
    Route::get('/admin/posts/delete-comment/{comment}', [AdminPostController::class, 'deleteComment'])->name('admin.posts.delete-comment');

    // distress Messages
    Route::get('/distress-message/browse/{userId?}', [DistressMessageController::class, 'browseDistressMessages'])->name('distress-message.browse');
    Route::get('/distress-message/show/{message}', [DistressMessageController::class, 'showDistressMessage'])->name('distress-message.show');
    Route::post('/distress-message/update-priority/{message}', [DistressMessageController::class, 'updatePriority'])->name('distress-message.update-priority');

    // harm spot routes
    Route::get('/harmspot/index', [HarmspotController::class, 'index'])->name('harmspot.index');
    Route::get('harmspot/new', [HarmspotController::class, 'newHarmpot'])->name('harmspot.new');
    Route::post('harmspot/create', [HarmspotController::class, 'createHarmspot'])->name('harmspot.create');
    Route::get('harmspot/show/{harmspot}', [HarmspotController::class, 'showHarmspot'])->name('harmspot.show');
    Route::post('harmspot/update/{harmspot}', [HarmspotController::class, 'updateHarmspot'])->name('harmspot.update');
    Route::get('harmspot/delete/{harmspot}', [HarmspotController::class, 'deleteHarmspot'])->name('harmspot.delete');
    Route::get('harmspot/publish/{harmspot}', [HarmspotController::class, 'publishHarmspot'])->name('harmspot.publish');
    Route::get('harmspot/unpublish/{harmspot}', [HarmspotController::class, 'unpublishHarmspot'])->name('harmspot.unpublish');
    Route::get('harmspot/view/{harmspot}', [HarmspotController::class, 'viewHarmspot'])->name('harmspot.view');

    // user tracking
    Route::get('/tracking/{user}', [UserTrackingController::class, 'viewTracking'])->name('user-tracking');

    // sex offenders
    Route::get('/offenders/browse', [OffendersController::class, 'browse'])->name('offenders.browse');
    Route::get('/offenders/new', [OffendersController::class, 'newOffender'])->name('offenders.new');
    Route::post('/offender/create', [OffendersController::class, 'create'])->name('offenders.create');
    Route::get('/offender/edit/{offender}', [OffendersController::class, 'edit'])->name('offenders.edit');
    Route::post('/offender/edit/{offender}', [OffendersController::class, 'editAction'])->name('offenders.edit-action');
    Route::get('/offender/delete/{offender}', [OffendersController::class, 'delete'])->name('offenders.delete');
    Route::get('/offender/view/{offender}', [OffendersController::class, 'viewOffender'])->name('offenders.view');

    // app rules
    Route::get('/rules', [AppRulesController::class, 'browseRules'])->name('rules.browse');
    Route::get('/rules/new', [AppRulesController::class, 'newRule'])->name('rules.new');
    Route::post('/rules/create', [AppRulesController::class, 'createRule'])->name('rules.create');
    Route::get('/rules/view/{ruleId}', [AppRulesController::class, 'viewRule'])->name('rules.view');
    Route::post('/rules/save/{ruleId}', [AppRulesController::class, 'saveRule'])->name('rules.save');
    Route::get('/rules/delete/{ruleId}', [AppRulesController::class, 'deleteRule'])->name('rules.delete');

    // post reports
    Route::get('/post-reports', [PostReportController::class, 'browseReports'])->name('post-reports');
    Route::get('/post-reports/delete/{reportId}', [PostReportController::class, 'deleteReport'])->name('post-reports.delete');
    Route::get('/post-reports/view/{reportId}/{userId?}', [PostReportController::class, 'viewReport'])->name('post-reports.view');
    Route::get('/post-reports/user/view/{userId}', [PostReportController::class, 'userPostReport'])->name('post-reports.user.view');

});

// activate account routes
Route::get('/admin/setup-password/{activation_code}', [AdminUserController::class, 'setupPassword'])->name('admin.setup-password');
Route::post('/admin/create-password', [AdminUserController::class, 'createPassword'])->name('admin.create-password');

// forgot password routes
Route::post('/admin/forgot-password/password-reset-link', [ForgotPasswordController::class, 'sendPasswordResetLink'])->name('admin.send-password-reset-link');
Route::get('/admin/reset-password/{code}', [ForgotPasswordController::class, 'resetPassword'])->name('admin.reset-password');
Route::post('/admin/reset-password', [ForgotPasswordController::class, 'resetPasswordAction'])->name('admin.reset-password-action');

Route::get('/get-country-name/{countryId}', [HarmspotController::class, 'getCountryName']);
