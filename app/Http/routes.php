<?php
use App\HelperDB;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('msg', 'MsgController@index');



Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
    Route::get('/', 'BusinessController@register');
    Route::post('get-state', 'BusinessController@getState');
    Route::post('business-register', 'BusinessController@businessRegister');
    Route::post('ajax-free-register', 'UserController@ajaxFreeRegister');
    
    Route::get('/home', 'ProfileController@index')->name('profile.index');
    Route::put('register/address', 'BusinessController@stepAddress');
    Route::get('register/address', 'BusinessController@stepAddress');
    Route::put('register/confirm-password', 'BusinessController@stepPassWord');
    Route::get('register/confirm-password', 'BusinessController@stepPassWord');
    Route::put('register/add-card', 'BusinessController@stepAddCard');
    Route::get('register/add-card', 'BusinessController@stepAddCard');
    Route::put('register/success-business', 'BusinessController@stepSuccessRegisBusiness');
    Route::get('register/success-business', 'BusinessController@stepSuccessRegisBusiness');
    Route::get('register/success', 'BusinessController@stepShowSuccess');
    Route::put('register/creator', 'BusinessController@stepCreator');
    Route::get('register/creator', 'BusinessController@stepCreator');
    
    Route::get('work-place', 'ProfileController@workPlace');
    Route::get('message-detail/{idMessage}', 'ProfileController@messageDetail');
    Route::get('message-user-detail/{idMessage}', 'ProfileController@messageUserDetail');
    Route::get('business/page/{idBusiness}', 'ProfileController@pageBusiness')->name('business.page');
    Route::get('business/page/add-message/{idBusiness}', 'ProfileController@addMessage');
    Route::get('search', 'ProfileController@search');
    Route::post('ajax-follow', 'ProfileController@ajaxFollow');
    Route::post('ajax-send-message', 'ProfileController@ajaxSendMessage');
    Route::post('ajax-replay-message', 'ProfileController@ajaxReplayMessage');
    
    Route::get('free/register', 'UserController@register');
    Route::get('free/register/address', 'UserController@stepAddress');
    Route::get('free/register/password', 'UserController@stepPassword');
    Route::get('free/register/save', 'UserController@stepSave');
    Route::get('free/register/success', 'UserController@stepShowSuccess');
    Route::get('search-city', 'UserController@searchCity');
    Route::post('upload', 'HomeController@ajaxUpload');
    Route::post('ajax-add-token', 'HomeController@ajaxAddToken');
    Route::post('ajax-add-local', 'HomeController@ajaxAddLocal');
    
    Route::post('check-business-acc', 'BusinessController@checkBusinessAcc');
    Route::post('/login', 'LoginController@index');
    Route::get('login', 'LoginController@index');
    Route::get('logout', 'LoginController@logout');
    
    // Route::get('/profile', 'ProfileController@index');
    Route::post('business/get-list-user', 'BusinessController@getListUser');
    Route::post('business/update-user', 'BusinessController@updateUser');
    Route::post('business/add-user', 'BusinessController@addUser');
    Route::post('business/add-indirect', 'BusinessController@addIndirect');
    Route::get('search-business', 'BusinessController@searchBusiness');
    Route::get('invitation/{biz_id}/{type}/{email}', 'BusinessController@invitationUser');
    Route::get('business/update/{idBusiness}', ['middleware' => 'auth', 'uses' => 'BusinessController@updatePage']);
    Route::post('business-update', 'BusinessController@updateBusiness');
    Route::get('profile/update', 'ProfileController@updatePage');
    Route::post('profile-update', 'ProfileController@updateProfile');
    Route::post('check-user-acc', 'ProfileController@checkUserAcc');
    Route::get('notifications', 'ProfileController@pageNotification');
    Route::post('update-notification', 'ProfileController@updateNotification');
    Route::get('business/verify/{biz_id}/{user_id}', 'BusinessController@verify');
    Route::post('business/add-invite', 'BusinessController@addInvite');
    Route::get('invitation-business/{email}', 'BusinessController@invitationBusiness');
    Route::post('business/status-user', 'BusinessController@changeStatus');
    //paging
    Route::get('right-content', function(){
        $helpDB = new HelperDB();
        $listBusiness = $helpDB->getBusinessAccWithCity(Auth::user()->city_id, true);
        return view('user.tpl.rightContent', ['listBusiness' => $listBusiness,]);
    });
    //forgot password
    Route::get('forgot-password', function(){
        return view('user.user.forgotPassword', [
            'title' => 'Register user::Zipidy'
        ]);
    });
    Route::post('send-forgot-password/', 'UserController@forgotPassword');
    Route::get('change-password/{email}/{token}', 'UserController@changePassword');
    Route::post('update-password', 'UserController@updatePassword');
});
// for admin 
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', "middleware" => "App\Http\Middleware\AuthenticatedWithBasic"], function () {
    Route::get('/', 'AdminController@index');
    Route::get('list-data', 'AdminController@listData');
    // group message controll
    Route::get('messages', 'MessagesController@index');

    Route::get('messages/ajax-businesses', 'MessagesController@ajaxListBusiness');
    
    Route::get('messages/messages-by-businesses/{id}', 'MessagesController@messageByBusiness');
    Route::get('messages/ajax-messages-by-businesses', 'MessagesController@ajaxMessageByBusiness');
    
    Route::get('messages/messages-detail/{id}', 'MessagesController@messageDetail');
    Route::get('messages/ajax-reply-list', 'MessagesController@ajaxReplyList');
    
    // group businesses controll
    Route::get('businesses', 'BusinessesController@index');
    Route::get('businesses/ajax-business-list', 'BusinessesController@ajaxListBusiness');
    Route::post('businesses/ajax-change-status', 'BusinessesController@ajaxChangeStatus');
    Route::get('businesses/detail/{id}', 'BusinessesController@detail');
    Route::get('businesses/ajax-list-user', 'BusinessesController@ajaxListUser');
    Route::get('businesses/ajax-list-deny-user', 'BusinessesController@ajaxListDenyUser');
    Route::post('businesses/change-info', 'BusinessesController@changeInfo');
    Route::get('businesses/detail-user/{businessId}/{id}', 'BusinessesController@detailUser');
    Route::get('businesses/user-business/{id}', 'BusinessesController@userBusiness');
    Route::post('businesses/change-info-business', 'BusinessesController@changeBusiness');
    Route::get('businesses/ajax-user-messages', 'BusinessesController@ajaxListUserMessage');
    Route::get('businesses/ajax-business-messages', 'BusinessesController@ajaxListBusinessMessage');
    Route::get('businesses/messages/{id}', 'BusinessesController@messages');
    Route::get('businesses/detail-message/{businessId}/{id}', 'BusinessesController@detailMessage');
    Route::get('businesses/ajax-user-receive', 'BusinessesController@ajaxListUserReceive');
    Route::get('businesses/ajax-user-reply', 'BusinessesController@ajaxListUserReply');
    // group users
    Route::get('users', 'UsersController@index');
    Route::get('users/ajax-get-list-user', 'UsersController@ajaxGetListUser');
    Route::post('users/ajax-change-status-user', 'UsersController@ajaxChangeStatusUser');
    Route::post('users/ajax-change-status-deny', 'UsersController@ajaxChangeStatusDeny');
    Route::get('users/detail/{id}', 'UsersController@detail');
    Route::get('users/update/{id}', 'UsersController@update');
    Route::put('users/save/{id}', 'UsersController@save');
    Route::get('users/ajax-list-user-business', 'UsersController@ajaxGetListUserBusinesses');
    Route::post('users/change-info-user', 'UsersController@changeUser');
    //group location
    Route::get('location', 'LocationController@index');
    Route::get('location/ajax-location-list', 'LocationController@ajaxGetListLocation');
    Route::get('location/detail/{city_name}/{state_id}/{zipcode}/{country_id}', 'LocationController@detail');
    Route::get('location/ajax-list-business', 'LocationController@ajaxGetListBusinesses');
    Route::get('location/ajax-list-indirect', 'LocationController@ajaxGetListIndirect');
    //mail 
    Route::get('mail', 'MailController@index');
    Route::get('mail/edit/{file}', 'MailController@edit');
    Route::post('mail/save', 'MailController@save');
    // Route::post('mail/remove', 'MailController@remove');
    Route::post('mail/view', 'MailController@view');
    Route::get('mail/ajax-list-mail', 'MailController@ajaxGetListMail');
    Route::get('mail/detail/{id}', 'MailController@detail');
    //group faq
    Route::get('faq', 'FaqController@index');
    Route::get('faq/ajax-list-faq', 'FaqController@ajaxListFaq');
    Route::get('faq/edit/{id}', 'FaqController@editPage');
    Route::post('faq/update', 'FaqController@update');
    Route::get('faq/add', 'FaqController@addPage');
    Route::post('faq/create', 'FaqController@create');
    Route::post('faq/delete', 'FaqController@delete');
    //group notification
    Route::get('notification', 'NotificationController@index');
    Route::get('notification/ajax-list-notification', 'NotificationController@ajaxListnotification');
    Route::get('notification/edit/{id}', 'NotificationController@editPage');
    Route::post('notification/update', 'NotificationController@update');
    Route::get('notification/add', 'NotificationController@addPage');
    Route::post('notification/create', 'NotificationController@create');
    Route::post('notification/delete', 'NotificationController@delete');
    //group support manager
    Route::get('support', 'SupportController@index');
    Route::get('support/ajax-list-support', 'SupportController@ajaxListSupport');
    Route::get('support/detail/{id}', 'SupportController@editPage');
    Route::post('support/send', 'SupportController@sendSupport');
});
//Route::auth();

Route::get('/', 'User\BusinessController@register')->name('home');
