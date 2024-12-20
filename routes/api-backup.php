<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GuardianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SexOffenderController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\HarmSpotController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\OffenderController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\DistressMessageController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::group(['middleware' => 'auth:sanctum'], function () {

    //UserController
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/users/{id}', [UserController::class, 'getUser']);
    Route::put('/users/{id}', [UserController::class, 'updateUser']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
    Route::post('/users/password', [UserController::class, 'changeUserPassword']);
    Route::post('/users/dp/{id}', [UserController::class, 'userPic']);
    Route::get('/users/dp/{id}', [UserController::class, 'viewPic']);
    Route::post('/users/search', [UserController::class, 'searchUser']);
    Route::post('/find/user', [UserController::class, 'searchUserByUsername']);



    //PostController
    Route::get('/posts', [PostController::class, 'allPosts']);
    Route::get('/show/post/{id}', [PostController::class, 'showPost']);
    Route::post('/like/post', [PostController::class, 'likePost']);
    Route::post('/comment/post', [PostController::class, 'commentPost']);
    Route::post('/reply/comment', [PostController::class, 'replyComment']);
    Route::get('/like/post/{id}', [PostController::class, 'showPostLikes']);
    Route::get('/like/comment/{id}', [PostController::class, 'showCommentLikes']);
    Route::post('/like/comment', [PostController::class, 'likeComment']);


    //GuardianController
    Route::post('/guardian/follow', [GuardianController::class, 'followGuardian']);
    Route::post('/guardian/unfollow', [GuardianController::class, 'unfollowGuardian']);
    Route::post('/guardian/ignore', [GuardianController::class, 'ignoreFollow']);
    Route::post('/guardian/accept', [GuardianController::class, 'acceptFollow']);
    Route::get('/guardian/pending/{id}', [GuardianController::class, 'getPendingGuardians']);
    Route::get('/guardians', [GuardianController::class, 'getAllGuardians']);
    Route::get('/wards/{id}', [GuardianController::class, 'getWards']);
    Route::get('/wards/guardians/{id}', [GuardianController::class, 'getAllWardGuardians']);



    //SexOffenderController
    Route::post('/offender/register', [SexOffenderController::class, 'register']);


    //TrackController
    Route::post('/track', [TrackController::class, 'trackUser']);
    Route::get('/track/{id}', [TrackController::class, 'showTrackHistory']);

 Route::get('/delete/user/tracks/{id}', [TrackController::class, 'deleteAllUserTracking']);


    //HarmSpotController
    Route::post('/report', [HarmSpotController::class, 'reportSpot']);
    Route::get('/report', [HarmSpotController::class, 'showReport']);
    Route::post('/report/true', [HarmSpotController::class, 'voteTrue']);
    Route::post('/report/false', [HarmSpotController::class, 'voteFalse']);
    Route::get('/report/trending', [HarmSpotController::class, 'showTrendingSpot']);
    Route::get('/report/view/{id}', [HarmSpotController::class, 'viewSpot']);



  //DistressMessageController
  Route::post('/message', [DistressMessageController::class, 'sendMessage']);
  Route::post('/message/priority', [DistressMessageController::class, 'changeDistressPriority']);
  Route::get('guardian/message/{id}', [DistressMessageController::class, 'guardianViewAllDistress']);
  Route::get('guardian/message/{id}/{priority}', [DistressMessageController::class, 'guardianViewDistressOnPriority']);

  Route::get('guardian/message/single/{id}/{msg_id}', [DistressMessageController::class, 'guardianViewSingleMessage']);





  Route::get('ward/message/{id}', [DistressMessageController::class, 'wardViewAllDistress']);

  Route::get('ward/message/{id}/{priority}', [DistressMessageController::class,'wardViewDistressOnPriority']);



  Route::get('ward/message/single/{id}/{msg_id}', [DistressMessageController::class,'wardViewSingleMessage']);





  Route::get('guardian/delete/distress/{id}', [DistressMessageController::class,'deleteGuardianDistressMessage']);
    Route::get('ward/delete/distress/{id}', [DistressMessageController::class,'deleteDistressMessage']);


    //DistressMessageController
    Route::post('/message', [DistressMessageController::class, 'sendMessage']);
    
    
    //Message Uploads
Route::post('/message/upload/video', [DistressMessageController::class, 'distressVideo64']);

Route::post('/message/upload/audio', [DistressMessageController::class, 'distressAudio64']);

Route::post('/message/upload/photo', [DistressMessageController::class, 'distressPhoto64']);


Route::post('/send/upload/photo', [DistressMessageController::class, 'sendMediaPhotoMessage']);

Route::post('/send/upload/video', [DistressMessageController::class, 'sendMediaVideoMessage']);

Route::post('/send/upload/audio', [DistressMessageController::class, 'sendMediaAudioMessage']);




//Notifications
Route::get('/notifications/{id}', [NotificationsController::class, 'getNotification']);
Route::get('/notifications/{id}/{postId}', [NotificationsController::class, 'readNotifications']);
Route::get('/not/delete/{id}', [NotificationsController::class, 'deleteNotification']);

Route::get('/check/notifications/{id}', [NotificationsController::class, 'checkNotifications']);


Route::post('/sos', [EmergencyController::class, 'store']);
Route::get('/sos/{id}', [EmergencyController::class, 'getAssignedNumber']);
Route::post('/sos/assign', [EmergencyController::class, 'assignNumber']);
Route::get('/sos/num/delete/{id}', [EmergencyController::class, 'deleteNumber']);
Route::get('/sos/num/{id}', [EmergencyController::class, 'getAllNumbers']);







//Offenders Controller
Route::get('/offenders', [OffenderController::class, 'showTrendingOffenders']);
Route::get('/offenders/true/{id}', [OffenderController::class, 'fetchTrues']);
Route::get('/offenders/false/{id}', [OffenderController::class, 'fetchFalse']);
Route::get('/offenders/notsure/{id}', [OffenderController::class, 'fetchNotSure']);


Route::post('/offenders/true', [OffenderController::class, 'voteTrue']);
Route::post('/offenders/false', [OffenderController::class, 'voteFalse']);
Route::post('/offenders/notsure', [OffenderController::class, 'voteNotSure']);

Route::post('/offence', [OffenderController::class, 'makeOffencePost']);


});






Route::post('/notifications', [NotificationsController::class, 'store']);


Route::get('/countries', [CountriesController::class, 'getCountries']);




Route::post('/users/resend-email', [UserController::class, 'resendActivationCode']);
Route::post('/users/activate', [UserController::class, 'activateAccount']);
Route::post("/login", [UserController::class, 'login']);
Route::post('/user', [UserController::class, 'signup']);

Route::get('/version', [UserController::class, 'checkVersion']);




Route::post('/users/password/forget', [UserController::class, 'forgetPassword']);
Route::post('/users/password/reset', [UserController::class, 'changePassword']);


Route::post('/show', [DistressMessageController::class, 'testing']);


