<?php

use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
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

Route::middleware('auth:api')->get('/user', function (UserStoreRequest $request) {
    return $request->user();
});

Route::post('/login', 'ApiController@login');
Route::post('/registration', 'ApiController@registration');
Route::post('/forget_password', 'ApiController@forgetPassword');
Route::post('/church_list', 'ApiController@churchListWithSearch');
Route::post('/church_referral', 'ApiController@churchReferral');
Route::post('/qr_code_church_verified', 'ApiController@qrCodeChurchVerification');
Route::post('/qr_code_user_verified', 'ApiController@qrCodeUserVerification');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::post('/change_password', 'ApiController@changePassword');
    Route::post('/update_user_profile', 'ApiController@userProfileUpdate');
    Route::post('/logout', 'ApiController@logout');
    Route::post('/project_list', 'ApiController@projectList');
    Route::post('/project_detail', 'ApiController@projectDetail');
    Route::post('/delete_project', 'ApiController@deleteProject');
    Route::post('/user_detail', 'ApiController@userDetailWithSetting');
    Route::post('/user_detail_accept_reject', 'ApiController@userDetailWithAcceptReject');
    Route::post('/edit_scripture', 'ApiController@editScripture');
    Route::post('/user_list', 'ApiController@userList');
    Route::post('/user_accept_reject', 'ApiController@userAcceptReject');
    Route::post('/add_user', 'ApiController@addUser');
    Route::post('/edit_user', 'ApiController@editUser');
    Route::post('/delete_user', 'ApiController@deleteUser');
    Route::post('/block_user', 'ApiController@blockUser');
    Route::post('/fund_list', 'ApiController@fundNameList');
    Route::post('/fund_add', 'ApiController@addFundName');
    Route::post('/fund_edit', 'ApiController@updateFundName');
    Route::post('/fund_delete', 'ApiController@deleteFundName');
    Route::post('/event_detail_datewise', 'ApiController@nextEventList');
    Route::post('/all_event', 'ApiController@allEventList');
    Route::post('/event_add', 'ApiController@addEvent');
    Route::post('/event_update', 'ApiController@updateEvent');
    Route::post('/event_delete', 'ApiController@deleteEvent');
    Route::post('/task_add', 'ApiController@addTask');
    Route::post('/task_edit', 'ApiController@updateTask');
    Route::post('/task_delete', 'ApiController@deleteTask');
    Route::post('/task_list', 'ApiController@taskList');
    Route::post('/task_list_datewise', 'ApiController@taskListDatewise');
    Route::post('/total_fund_collection', 'ApiController@totalFundCollected');
    Route::post('/donation_slab_list', 'ApiController@donationSlabsList');
    Route::post('/notification_list', 'ApiController@notificationList');
    Route::post('/delete_notification', 'ApiController@deleteNotification');
    Route::post('/notification_option_list', 'ApiController@notificationOptionList');
    Route::post('/next_event', 'ApiController@nextEvent');
    Route::post('/qr_code_verified', 'ApiController@qrCodeVerification');
    Route::post('/generate_church_qrcode', 'ApiController@generateChurchQrcode');
    Route::post('/generate_user_qrcode', 'ApiController@generateUserQrcode');
    Route::post('/show_scripture', 'ApiController@showScripture');
    Route::post('/project_add', 'ApiController@addProject');
    Route::post('/project_edit', 'ApiController@editProject');
    Route::post('/project_delete', 'ApiController@deleteProject');
    Route::post('/user_setting', 'ApiController@userProfileSetting');
    Route::post('/event_date_list', 'ApiController@dateEventList');
    Route::post('/task_priority_list', 'ApiController@taskPriority');
    Route::post('/church_dashboard', 'ApiController@churchDashboard');
    Route::post('/donor_dashboard', 'ApiController@donorDashboard');
    Route::post('/donor_setting', 'ApiController@editUserSetting');   
    Route::post('/update_event_calendar_id', 'ApiController@updateEventCalendarId');   
    Route::post('/payment_process', 'ApiController@paymentprocess');   
    Route::post('/savecard', 'ApiController@savecard');   
    Route::post('/getcarddetail', 'ApiController@getcarddetail');   
    Route::post('/trasactiondetail', 'ApiController@trasactiondetail');   
    Route::post('/sagepay_detail', 'ApiController@sagepay_detail');   
    Route::post('/action_sagepay', 'ApiController@action_sagepay');   
    Route::post('/get_payment_list', 'ApiController@get_payment_list');   
    Route::post('/church_group_list', 'ApiController@church_group_list');   
    Route::post('/church_join_group_list', 'ApiController@church_join_group_list');   
    Route::post('/church_group_create', 'ApiController@church_group_create');   
    Route::post('/church_group_edit', 'ApiController@church_group_edit');   
    Route::post('/church_group_delete', 'ApiController@church_group_delete');   
    Route::post('/taks_assign', 'ApiController@taks_assign');   
    Route::post('/taks_assign_detail', 'ApiController@taks_assign_detail');   
    Route::post('/update_taks_assign', 'ApiController@update_taks_assign');   
    Route::post('/send_test_push_notification', 'ApiController@send_test_push_notification');   
    Route::post('/testing_notification_update_weekly', 'ApiController@testing_notification_update_weekly');   
    Route::post('/update_fcm', 'ApiController@update_fcm');  
    Route::post('/get_allgroup_list', 'ApiController@get_allgroup_list');  
    Route::post('/user_join_group_list', 'ApiController@user_join_group_list');  
    Route::post('/church_group_detail', 'ApiController@church_group_detail');  
    Route::post('/update_read_notification', 'ApiController@update_read_notification'); 
    Route::post('/transactionDetails', 'ApiController@transactionDetails');
    Route::post('/giftaid_update_status', 'ApiController@giftaid_update_status');  
    Route::post('/switchChurchList', 'ApiController@switchChurchList');  
    Route::post('/userAddMultiChurch', 'ApiController@userAddMultiChurch');  
    Route::post('/selectedSwitchChurchList', 'ApiController@selectedSwitchChurchList');  
    Route::post('/upate_user_role', 'ApiController@upate_user_role');  
    Route::post('/check_user_status', 'ApiController@check_user_status');  
    Route::post('/project_default_image', 'ApiController@project_default_image');  
    Route::post('/transaction_report_church', 'ApiController@transaction_report_church');  
    Route::post('/transaction_report_user', 'ApiController@transaction_report_user');  
    Route::post('/transaction_report_year', 'ApiController@transaction_report_year');  
    Route::post('/filter_project_list', 'ApiController@filter_project_list');  
    Route::post('/filter_project_list_user', 'ApiController@filter_project_list_user');  
    Route::post('/user_dob_detail', 'ApiController@user_dob_detail');  
    Route::post('/single_event_detail', 'ApiController@singleEventDetail');  
    Route::post('/clear_all_notification', 'ApiController@clearAllNotification');  
    Route::post('/task_recurring_type', 'ApiController@taskRecurringType');  
    Route::post('/user_assign_task_detail', 'ApiController@userAssignTaskDetail');  
    Route::post('/church_update_detail', 'ApiController@churchUpdateDetail');  
    Route::post('/repeat_type', 'ApiController@repeattype');  
    Route::post('/add_to_project_archive', 'ApiController@addtoprojectarchive');  
    Route::post('/user_family_tree', 'ApiController@userfamilytree');  
    Route::post('/user_family_tree_list', 'ApiController@userfamilytreelist');  
    Route::post('/remove_family_member', 'ApiController@removefamilymember');  
    

        
});