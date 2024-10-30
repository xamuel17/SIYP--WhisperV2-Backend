<?php

use App\Http\Controllers\CommunityController;
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
use App\Http\Controllers\RulesController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\VolunteerController;
use App\Models\NotificationPreference;

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
    Route::post('/users/language', [UserController::class, 'saveLanguage']);


    //PostController
    Route::get('/posts', [PostController::class, 'allPosts']);
    Route::get('/show/post/{id}', [PostController::class, 'showPost']);
    Route::post('/like/post', [PostController::class, 'likePost']);
    Route::post('/comment/post', [PostController::class, 'commentPost']);
    Route::post('/reply/comment', [PostController::class, 'replyComment']);
    Route::get('/like/post/{id}', [PostController::class, 'showPostLikes']);
    Route::get('/like/comment/{id}', [PostController::class, 'showCommentLikes']);
    Route::post('/like/comment', [PostController::class, 'likeComment']);


    //++New
    Route::get('/user/posts/{id}', [PostController::class, 'userFetchAllPosts']);
    Route::post('/user/post/flag', [PostController::class, 'userFlagPost']);
    Route::post('/user/post/report', [PostController::class, 'reportPost']);

    Route::post('/guardian/block', [GuardianController::class, 'blockGuardian']);
    Route::get('/guardian/blocks/{id}', [GuardianController::class, 'fetchBlockedUser']);
    Route::post('/guardian/unblock', [GuardianController::class, 'unblockUsers']);
    Route::post('/report/user', [RulesController::class, 'reportUser']);
    Route::get('/rules', [RulesController::class, 'viewRules']);
    Route::get('/harmspot/{id}', [HarmSpotController::class, 'showTrendingSpotByCountry']);





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
    Route::get('ward/message/{id}/{priority}', [DistressMessageController::class, 'wardViewDistressOnPriority']);
    Route::get('ward/message/single/{id}/{msg_id}', [DistressMessageController::class, 'wardViewSingleMessage']);
    Route::get('guardian/delete/distress/{id}', [DistressMessageController::class, 'deleteGuardianDistressMessage']);
    Route::get('ward/delete/distress/{id}', [DistressMessageController::class, 'deleteDistressMessage']);


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

    Route::get('/notification/preference', [NotificationPreference::class, 'fetchUserPreference']);
    Route::post('/notification/preference-update', [NotificationPreference::class, 'updateUserPreference']);



    Route::get('/check/notifications/{id}', [NotificationsController::class, 'checkNotifications']);


    Route::post('/sos/number', [EmergencyController::class, 'addNumber']);
    Route::get('/sos/number', [EmergencyController::class, 'getAssignedNumber']);
    Route::post('/sos/assign', [EmergencyController::class, 'assignNumber']);
    Route::post('/sos/number/delete', [EmergencyController::class, 'deleteNumber']);
    Route::get('/sos/numbers', [EmergencyController::class, 'getAllNumbers']);







    //Offenders Controller
    Route::get('/offenders', [OffenderController::class, 'showTrendingOffenders']);
    Route::get('/offenders/true/{id}', [OffenderController::class, 'fetchTrues']);
    Route::get('/offenders/false/{id}', [OffenderController::class, 'fetchFalse']);
    Route::get('/offenders/notsure/{id}', [OffenderController::class, 'fetchNotSure']);
    Route::post('/offenders/true', [OffenderController::class, 'voteTrue']);
    Route::post('/offenders/false', [OffenderController::class, 'voteFalse']);
    Route::post('/offenders/notsure', [OffenderController::class, 'voteNotSure']);
    Route::post('/offence', [OffenderController::class, 'makeOffencePost']);



    Route::post('/community', [CommunityController::class, 'createCommunity']);
    Route::get('/community', [CommunityController::class, 'getCommunities']);
    Route::get('/community-detail/{community_id}', [CommunityController::class, 'getCommunity']);
    Route::get('/user/community', [CommunityController::class, 'getCommunityByUser']);
    Route::post('/community/follow', [CommunityController::class, 'joinCommunity']);
    Route::get('/community/follows', [CommunityController::class, 'getFollowingCommunity']);
    Route::post('/community/post', [CommunityController::class, 'makeCommunityPost']);
    Route::get('/community/post-single/{post_id}', [CommunityController::class, 'getCommunitySinglePost']);
    Route::post('/community/post-second', [CommunityController::class, 'makeAnotherCommunityPost']);
    Route::get('/community/post/{community_id}/{page}', [CommunityController::class, 'getCommunityPost']);
    Route::post('/community/action', [CommunityController::class, 'likeDislikeCommunityPostOrReply']);
    Route::get('/community/home/{page}', [CommunityController::class, 'CommunityDashboard']);
    Route::get('/community/post/delete/{post_id}', [CommunityController::class, 'DeleteMyCommunityPost']);
    Route::get('/community/comment/delete/{comment_id}', [CommunityController::class, 'DeleteMyCommunityComment']);

    Route::post('/community/post/reply', [CommunityController::class, 'replyCommunityPost']);
    Route::post('/community/comment/reply', [CommunityController::class, 'commentReply']);
    Route::get('/community/comment/reply/{comment_id}', [CommunityController::class, 'getPostCommentReply']);
    Route::get('/community/rules', [CommunityController::class, 'viewCommunityRules']);
    Route::post('/community/report/comment', [CommunityController::class, 'reportCommunityComment']);
    Route::post('/community/report/post', [CommunityController::class, 'reportCommunityPost']);

    Route::post('/volunteer', [VolunteerController::class, 'createVolunteer']);
    Route::get('/volunteers', [VolunteerController::class, 'viewVolunteers']);
    Route::post('/volunteer/photo', [VolunteerController::class, 'uploadPhoto']);
    Route::post('/volunteer/action', [VolunteerController::class, 'activateDeactivateVolunteer']);
    Route::post('/volunteer/appointment', [VolunteerController::class, 'volunteerSetAppointmentDates']);
    Route::get('/volunteer/available-days/{id}', [VolunteerController::class, 'volunteerAvailableDays']);
    Route::delete('/volunteer/available-days/{id}', [VolunteerController::class, 'volunteerDeleteAvailableDays']);


    Route::post('/volunteer/free-time', [VolunteerController::class, 'retrieveVolunteerAvailableTime']);
  //  Route::get('/volunteer/delete/{id}', [VolunteerController::class, 'deleteAvailableTime']);
    Route::post('/volunteer/booking', [VolunteerController::class, 'userMakeBooking']);
    Route::get('/volunteer/booking', [VolunteerController::class, 'volunteerViewBooking']);
    Route::get('/volunteer/booking-update/{status}/{id}', [VolunteerController::class, 'updateBookingStatus']);

    Route::post('/volunteer/chat', [VolunteerController::class, 'createChat']);
    Route::get('/volunteer/chats/{chat_id}', [VolunteerController::class, 'retrieveChats']);
    Route::get('/volunteer/chat-list', [VolunteerController::class, 'retrieveChatList']);

});






Route::post('/notifications', [NotificationsController::class, 'store']);


Route::get('/countries', [CountriesController::class, 'getCountries']);

Route::get('/trust-me', [UserController::class, 'getCsrfToken']);



Route::post('/users/resend-email', [UserController::class, 'resendActivationCode']);
Route::post('/users/activate', [UserController::class, 'activateAccount']);
Route::post("/login", [UserController::class, 'login']);
Route::post('/user', [UserController::class, 'signup']);

Route::get('/version', [UserController::class, 'checkVersion']);




Route::post('/users/password/forget', [UserController::class, 'forgetPassword']);
Route::post('/users/password/reset', [UserController::class, 'changePassword']);



Route::post('/show', [DistressMessageController::class, 'testing']);


Route::get('/eula', [TermsController::class, 'viewEula']);

Route::get('/terms', [TermsController::class, 'termsAndConditions']);
