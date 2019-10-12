        <?php
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
        // Route::get('/', function () {
        //     return view('welcome');
        // });

        Route::get('/clear-cache', function() {
        $exitCode = Artisan::call('config:cache');
        $exitCode = Artisan::call('view:clear');
        $exitCode = Artisan::call('cache:clear');
        //$exitCode = Artisan::call('config:cache');$exitCode = Artisan::call('config:clear');

        // return what you want
        });
        Route::get('postEvent','ProjectController@redirectDeepLink');
        Route::post('/check_login', 'UserController@checkLogin')->name('check-login');
        Route::post('/forget_password', 'UserController@forgetPassword')->name('admin-forgetpassword');
        Route::get('/church_donation/{id}', 'ProjectController@churchDonation')->name('church_donation');
        Route::post('/ajax_church_donation', 'ProjectController@ajaxchurchDonation')->name('ajax_church_donation');

        Route::get('/declaration-form/{id}/{church_id}', 'DeclarationCmsController@declarationForm')->name('declaration-form');
        Route::get('/sagepay', 'SagepayPaymentController@sagepay');
        Route::get('/sagepay_confirm', 'SagepayPaymentController@sagepay_confirm');
        Route::get('/authorizenet_confirm', 'SagepayPaymentController@authorizenet_confirm');
        Route::get('/sagepay2', 'SagepayPaymentController2@sagepay2');
        Route::get('/sagepay_confirm2', 'SagepayPaymentController2@sagepay_confirm2');
        Route::get('/authorizenet_confirm2', 'SagepayPaymentController2@authorizenet_confirm2');
        /* CSV dontation*/
        Route::get('/csv', 'DynamicCSVController@csv');
        Route::get('/pdf/{id}', 'DynamicCSVController@pdf');


        Route::group(['middleware' => 'App\Http\Middleware\Auth::class'], function () {
        Route::get('/member_request', 'UserController@memberRequest')->name('member-request');
        Route::get('/get_member_user', 'UserController@getmemberUser')->name('get-member-user-list');
        Route::post('/request_member_user', 'UserController@requestmemberUser')->name('request-member-user-list');
        Route::post('/action_add_morechurch', 'UserController@actionAddMorechurch')->name('action-morechurch-add');
        Route::get('/dashboard', 'DashboardController@Dashboard')->name('dashboard');
        Route::get('/dashboard_list', 'DashboardController@dashboardList')->name('dashboard-list');
        Route::get('/user', 'UserController@UserManagement')->name('user-management');
        Route::get('/church', 'UserController@ChurchManagement')->name('church-management');
        /* pastor managment */
        Route::get('/pastor', 'UserController@PastorManagement')->name('pastor-management');
        Route::get('/get_pastor', 'UserController@getPastor')->name('get-pastor-list');
        Route::get('/edit_pastor/{id}', 'UserController@editPastor')->name('pastor-edit');
        Route::post('/action_edit_pastor', 'UserController@actionEditPastor')->name('action-pastor-edit');
        Route::get('/add_pastor', 'UserController@addPastor')->name('pastor-add');
        Route::post('/action_add_pastor', 'UserController@actionAddPastor')->name('action-pastor-add');
         Route::get('/delete_pastor/{id}', 'UserController@deletePastor')->name('pastor-delete');
        /* admin common */
        Route::get('/logout', 'UserController@Logout')->name('user-logout');
        Route::get('/change_password', 'UserController@changePassword')->name('change-password');
        Route::post('/action_change_password', 'UserController@actionChangePassword')->name('action-change-password');
        Route::get('/change_profile', 'UserController@adminProfile')->name('change-profile');
        Route::post('/action_change_profile', 'UserController@updateAdminProfile')->name('action-change-profile');

        /* user module */
        Route::get('/get_user', 'UserController@getUser')->name('get-user-list');
        Route::get('/add_user', 'UserController@addUser')->name('user-add');
        Route::post('/action_add_user', 'UserController@actionAddUser')->name('action-user-add');
        Route::get('/edit_user/{id}', 'UserController@editUser')->name('user-edit');
        Route::post('/action_edit_user', 'UserController@actionEditUser')->name('action-user-edit');
        Route::get('/delete_user/{id}', 'UserController@deleteUser')->name('user-delete');
        Route::get('/church_user', 'UserController@churchList')->name('church-list');
        Route::get('/get_church', 'UserController@getChurch')->name('get-church-list');
        Route::get('/add_church', 'UserController@addChurch')->name('church-add');
        Route::post('/action_add_church', 'UserController@actionAddChurch')->name('action-church-add');
        Route::get('/edit_church/{id}', 'UserController@editChurch')->name('church-edit');
        Route::post('/action_edit_church', 'UserController@actionEditChurch')->name('action-church-edit');
        Route::get('/delete_church/{id}', 'UserController@deleteChurch')->name('church-delete');
        Route::post('/update_status', 'UserController@updatesatus')->name('update-status');
        Route::get('/church_view_qr_code/{id}', 'UserController@churchViewQrCode')->name('church-qr-code-view');
        Route::get('/user_view_qr_code/{id}', 'UserController@userViewQrCode')->name('user-qr-code-view');
        Route::post('/church_generate_qr_code', 'UserController@getChurchGenerateQrCode')->name('church-generate-qr-code');
        Route::post('/user_generate_qr_code', 'UserController@getUserGenerateQrCode')->name('user-generate-qr-code');
         Route::post('/church_generate_qr_code_visitor', 'UserController@getChurchGenerateQrCodeVisitor')->name('church-generate-qr-code-visitor');
         Route::post('/upate_user_role', 'UserController@upate_user_role')->name('upate_user_role');




        /* project module */
        Route::get('/project', 'ProjectController@ProjectManagement')->name('project-management');
        Route::get('/project_list', 'ProjectController@getProject')->name('project-list');
        Route::get('/add_project', 'ProjectController@addProject')->name('project-add');
        Route::post('/action_add_project', 'ProjectController@actionAddProject')->name('action-project-add');
        Route::post('/action_edit_project', 'ProjectController@actionEditProject')->name('action-project-edit');
        Route::get('/edit_project/{id}', 'ProjectController@editProject')->name('project-edit');
        Route::get('/delete_project/{id}', 'ProjectController@deleteProject')->name('project-delete');
        Route::post('/add_project_image', 'ProjectController@projectImageUploader')->name('project-image-upload');
        Route::post('/upload_myfile/', 'ProjectController@upload_myfile')->name('upload_myfile');
        Route::post('/service-provider-image', 'ProjectController@service_provider_image_upload')->name('project.service_provider_image_upload');
        /* scripture module */
        Route::get('/scripture', 'ScriptureController@ScriptureManagement')->name('scripture-management');
        Route::get('/scripture_list', 'ScriptureController@getScripture')->name('scripture-list');
        Route::get('/edit_scripture/{id}', 'ScriptureController@editScripture')->name('scripture-edit');
        Route::post('/action_edit_scripture', 'ScriptureController@actionEditScripture')->name('action-scripture-edit');

        /* user role module */
        Route::get('/user_role', 'UserRoleController@UserRoleManagement')->name('user-role-management');
        Route::get('/user_role_list', 'UserRoleController@getUserRole')->name('user-role-list');
        Route::get('/add_user_role', 'UserRoleController@addUserRole')->name('user-role-add');
        Route::post('/action_add_userrole', 'UserRoleController@actionAddUserRole')->name('action-user-role-add');
        Route::get('/edit_user_role/{id}', 'UserRoleController@editUserRole')->name('user-role-edit');
        Route::post('/action_edit_user_role', 'UserRoleController@actionEditUserRole')->name('action-user-role-edit');
        Route::get('/delete_user_role/{id}', 'UserRoleController@deleteUserRole')->name('user-role-delete');

        /* event module */
        Route::get('/event', 'EventController@EventManagement')->name('event-management');
        Route::get('/event_list', 'EventController@getEvent')->name('event-list');
        Route::get('/add_event', 'EventController@addEvent')->name('event-add');
        Route::post('/action_add_event', 'EventController@actionAddEvent')->name('action-event-add');
        Route::get('/edit_event/{id}', 'EventController@editEvent')->name('event-edit');
        Route::post('/action_edit_event', 'EventController@actionEditEvent')->name('action-event-edit');
        Route::get('/delete_event/{id}', 'EventController@deleteEvent')->name('event-delete');

        /* task module */
        Route::get('/task', 'TaskController@TaskManagement')->name('task-management');
        Route::get('/task_list', 'TaskController@getTask')->name('task-list');
        Route::get('/add_task', 'TaskController@addTask')->name('task-add');
        Route::post('/action_add_task', 'TaskController@actionAddTask')->name('action-task-add');
        Route::get('/edit_task/{id}', 'TaskController@editTask')->name('task-edit');
        Route::post('/action_edit_task', 'TaskController@actionEditTask')->name('action-task-edit');
        Route::get('/delete_task/{id}', 'TaskController@deleteTask')->name('task-delete');

        /* task module */
        /* task gropu module*/
        Route::get('/task-group', 'TaskController@TaskGroupManagement')->name('task-group-management');
        Route::get('/task-group-list', 'TaskController@getTaskGroup')->name('task-group-list');
        Route::get('/add-group-task', 'TaskController@addGroupTask')->name('task-group-add');
        Route::post('/get-group-users', 'TaskController@getGroupUser')->name('get-group-users');
        Route::post('/get-group', 'TaskController@getGroup')->name('get-group');
        Route::post('/action-group-add-task', 'TaskController@actionAddGroupTask')->name('action-group-add-task');
        Route::get('/edit-group-task/{id}', 'TaskController@editGroupTask')->name('edit-group-task');
        Route::post('/action-edit-group-task', 'TaskController@actionEditGroupTask')->name('action-edit-group-task');
        Route::get('/delete_task_group/{id}', 'TaskController@deleteTaskGroup')->name('task-delete-group');
        /* task gropu module*/
        Route::get('/referrar', 'ReferrarController@ReferrarManagement')->name('referrar-management');
        Route::get('/referrar_list', 'ReferrarController@getReferrar')->name('referrar-list');
        Route::get('/add_referrar', 'ReferrarController@addReferrar')->name('referrar-add');
        Route::post('/action_add_referrar', 'ReferrarController@actionAddReferrar')->name('action-referrar-add');

        Route::get('/edit_referrar/{id}', 'ReferrarController@editReferrar')->name('referrar-edit');
        Route::post('/action_edit_referrar', 'ReferrarController@actionEditReferrar')->name('action-referrar-edit');
        Route::get('/delete_referrar/{id}', 'ReferrarController@deleteReferrar')->name('referrar-delete');
        Route::post('/get_referrer_id', 'ReferrarController@GetReferrerId')->name('get-referrer-id');

        /* global setting module */
        Route::get('/globalsetting', 'GlobalSettingController@GlobalSettingManagement')->name('global-setting-management');
        Route::get('/global_setting_list', 'GlobalSettingController@getGlobalSetting')->name('global-setting-list');
        Route::get('/add_global_setting', 'GlobalSettingController@addGlobalSetting')->name('global-setting-add');
        Route::post('/action_add_global_setting', 'GlobalSettingController@actionAddGlobalSetting')->name('action-global-setting-add');
        Route::get('/edit_global_setting/{id}', 'GlobalSettingController@editGlobalSetting')->name('global-setting-edit');
        Route::post('/action_edit_global_setting', 'GlobalSettingController@actionEditGlobalSetting')->name('action-global-setting-edit');
        Route::get('/delete_global_setting/{id}', 'GlobalSettingController@deleteGlobalSetting')->name('global-setting-delete');

         Route::get('/add_project_image_setting', 'GlobalSettingController@addProjectImageSetting')->name('add-project-image-setting');
          Route::post('/upload_myfile_default/', 'GlobalSettingController@upload_myfile_default')->name('upload_myfile_default');
           Route::post('/service-provider-image-default', 'GlobalSettingController@service_provider_image_upload_default')->name('project_global.service_provider_image_upload_default');

        /* project slab module */
        Route::get('/project_slab', 'ProjectSlabController@projectSlabManagement')->name('project-slab-management');
        Route::get('/project_slab_list', 'ProjectSlabController@getProjectSlab')->name('project-slab-list');
        Route::get('/add_project_slab', 'ProjectSlabController@addProjectSlab')->name('project-slab-add');
        Route::post('/action_add_project_slab', 'ProjectSlabController@actionAddProjectSlab')->name('action-project-slab-add');
        Route::get('/edit_project_slab/{id}', 'ProjectSlabController@editProjectSlab')->name('project-slab-edit');
        Route::post('/action_edit_project_slab', 'ProjectSlabController@actionEditProjectSlab')->name('action-project-slab-edit');
        Route::get('/delete_project_slab/{id}', 'ProjectSlabController@deleteProjectSlab')->name('project-slab-delete');

        /* user setting module */
        Route::get('/user_setting', 'UserSettingController@UserSettingManagement')->name('user-setting-management');
        Route::get('/user_setting_list', 'UserSettingController@getUserSetting')->name('user-setting-list');
        Route::get('/edit_user_setting/{id}', 'UserSettingController@editUserSetting')->name('user-setting-edit');
        Route::post('/action_edit_user_setting', 'UserSettingController@actionEditUserSetting')->name('action-user-setting-edit');
        // Route::get('/delete_task/{id}', 'TaskController@deleteTask')->name('task-delete');

        /* user card module */
        Route::get('/user_card', 'UserCardController@UserCardManagement')->name('user-card-management');
        Route::get('/user_card_list', 'UserCardController@getUserCard')->name('user-card-list');
        Route::get('/delete_user_card/{id}', 'UserCardController@deleteUserCard')->name('user-card-delete');

        /* cms management module */
        Route::get('/cms_management', 'CmsManagementController@cmsManagement')->name('cms-management');
        Route::get('/cms_management_list', 'CmsManagementController@getCmsList')->name('cms-management-list');
        Route::get('/cms_management_add', 'CmsManagementController@addCms')->name('cms-management-add');
        Route::post('/action_add_cms_management', 'CmsManagementController@actionAddCms')->name('action-cms-management-add');
        Route::get('/edit_cms_management/{id}', 'CmsManagementController@editCms')->name('cms-management-edit');
        Route::post('/action_cms_management', 'CmsManagementController@actionEditCms')->name('action-cms-management-edit');
        Route::get('/delete_cms_management/{id}', 'CmsManagementController@deleteCms')->name('cms-management-delete');
        // Route::get('/church_user', 'UserController@churchList')->name('church-list');

        /* QR Code module */
        Route::get('/qr_code', 'QrCodeController@qrCodeManagement')->name('qr-code-management');
        Route::get('/qr_code_list', 'QrCodeController@getQrCode')->name('qr-code-list');
        Route::post('/generate_qr_code', 'QrCodeController@getGenerateQrCode')->name('generate-qr-code');
        Route::get('/view_qr_code/{id}', 'QrCodeController@viewQrCode')->name('qr-code-view');

        // Route::get('qr-code-g', function () {
        //     \QrCode::size(500)
        //               ->format('png')
        //               ->generate('asdfqewrzcxv', public_path('storage/public/storage/image.png'));
        //     return view('qrcode/demo');
        //   });

        /* Payment/Donation module */
        Route::get('/payment', 'PaymentController@paymentManagement')->name('payment-management');
        Route::get('/payment_list', 'PaymentController@getPaymentList')->name('get-payment-list');
        Route::get('/view_payment_detail/{id}', 'PaymentController@viewPaymentDetail')->name('payment-view');


        /* other content module */
        Route::get('/check_email/', 'OtherController@check_email')->name('check-mail');
        Route::get('/check_mobile/', 'OtherController@check_mobile')->name('check-mobile');
        Route::get('/check_user_role/', 'OtherController@check_user_role')->name('check-user-role');
        Route::get('/check_reference/', 'OtherController@check_reference')->name('check-reference');

        /* Email Template module */
        Route::get('/email_template', 'EmailTemplateController@emailTemplateManagement')->name('email-template-management');
        Route::get('/email_template_list', 'EmailTemplateController@getEmailTemplate')->name('email-template-list');
        Route::get('/add_email_template', 'EmailTemplateController@addEmailTemplate')->name('email-template-add');
        Route::post('/action_add_email_template', 'EmailTemplateController@actionAddEmailTemplate')->name('action-email-template-add');
        Route::get('/edit_email_template/{id}', 'EmailTemplateController@editEmailTemplate')->name('email-template-edit');
        Route::post('/action_email_template', 'EmailTemplateController@actionEditEmailTemplate')->name('action-email-template-edit');
        // Route::get('/delete_email_template/{id}', 'EmailTemplateController@deleteEmailTemplate')->name('email-template-delete');

        /* fundname module */
        Route::get('/fundname', 'ProjectController@FundManagement')->name('fundname-management');
        Route::get('/fundname_list', 'ProjectController@getFundName')->name('fundname-list');
        Route::get('/add_fundname', 'ProjectController@addFundName')->name('fundname-add');
        Route::post('/action_add_fundname', 'ProjectController@actionAddFundName')->name('action-fundname-add');
        Route::get('/edit_fundname/{id}', 'ProjectController@editFundName')->name('fundname-edit');
        Route::get('/delete_fundname/{id}', 'ProjectController@deleteFundName')->name('fundname-delete');
        Route::post('/action_edit_fundname', 'ProjectController@actionEditFundName')->name('action-fundname-edit');

        /* other module */
        Route::post('/church_fund', 'ProjectController@churchFundList')->name('church-fund');
        /* declaration route */ 

        Route::get('/declarationCMS', 'DeclarationCmsController@index')->name('declaration-cms');
        Route::get('/declaration_cms_list', 'DeclarationCmsController@getCmsList')->name('declaration-cms-list');
        Route::get('/declaration_cms_add', 'DeclarationCmsController@addCms')->name('declaration-cms-management-add');
        Route::post('/action_add_declaration_management', 'DeclarationCmsController@actionAddCms')->name('action-declaration-management-add');

        Route::get('/edit_declaration_management/{id}', 'DeclarationCmsController@editCms')->name('declaration-management-edit');
        Route::post('/action_declaration_management', 'DeclarationCmsController@actionEditCms')->name('action-declaration-management-edit');


        // declaration-cms-management-add

        });

        Auth::routes();
        Route::get('/home', 'HomeController@index')->name('home');
