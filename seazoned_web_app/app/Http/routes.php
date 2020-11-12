<?php 

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

Route::get('/', 'HomeController@Index')->name('home');
Route::get('/user-register', 'HomeController@userRegister')->name('user-register');
Route::get('/forgot-password', 'HomeController@forgotPassword')->name('forgot-password');
Route::post('/verify-email', 'HomeController@verifyEmail')->name('verify-email');
Route::get('/confirm-otp', 'HomeController@confirmOtp')->name('confirm-otp');
Route::post('/verify-otp', 'HomeController@verifyOtp')->name('verify-otp');
Route::get('/reset-password', 'HomeController@resetPassword')->name('reset-password');
Route::post('/update-password', 'HomeController@updatePassword')->name('update-password');
Route::post('/add-user', 'HomeController@addUser')->name('add-user');
Route::get('/user-login', 'HomeController@userLogin');
Route::post('/user-login', 'HomeController@loginAccess')->name('user-login');
Route::get('/user-dashboard', 'HomeController@userDashboard')->name('user-dashboard');
Route::get('/landscaper-dashboard', 'HomeController@landscaperDashboard')->name('landscaper-dashboard');
Route::post('/add-landscapper', 'HomeController@addLandscapper');
Route::get('/add-landscapper-mobile', 'HomeController@addLandscapperMobile');
Route::post('/add-landscapper-final', 'HomeController@addLandscapperFinal');
Route::post('/add-landscapper-final-mobile', 'HomeController@addLandscapperFinalMobile');
Route::get('/ajax_pages/services/{page}', function ($page) {
    return View('ajax_pages/services/' . $page);
});
Route::post('/add-new-service', 'HomeController@addNewService');
Route::get("/log-out", function () {
    session()->flush();
    return redirect()->route("home");
});
Route::get('/landscaper-dashboard/view-service-details/{id}', 'LandscaperController@viewServiceDetails')->name('view-service-details');
Route::get('/landscaper-dashboard/update-service/{id}/{status}', 'LandscaperController@updateService')->name('update-service');
Route::get('/booking-history', 'LandscaperController@bookingHistory')->name('booking-history-landscaper');
Route::get('/landscaper/payment-info', 'PaymentController@paymentInfoLandscaper')->name('landscaper-payment-info');
Route::get('/landscaper/transcation-history', 'PaymentController@landscaperTransaction')->name('landscaper-transcation-history');
Route::post('/landscaper/add-landscaper-payment-info', 'PaymentController@addLandscaperPaymentInfo')->name('add-landscaper-payment-info');
Route::get('/landscaper/delete-landscaper-payment-info/{id}', 'PaymentController@deleteLandscaperPaymentInfo')->name('delete-landscaper-payment-info');
Route::get("/landscaper-profile", 'LandscaperController@myProfile')->name("landscaper-profile");
Route::get("/landscaper-load-service/{id}", 'LandscaperController@loadService');
Route::post("/Landscapper/Update-Profile", 'LandscaperController@updateProfile');
Route::post('/EndJob', 'LandscaperController@EndJob')->name('EndJob');

Route::get('/customer-terms-conditions', 'HomeController@get_terms_conditions')->name('get_terms_conditions');
Route::get('/provider-terms-conditions', 'HomeController@get_terms_conditions_provider')->name('get_terms_conditions_provider');


Route::post("/landscapper/update-service-details", 'LandscaperController@updateServiceDetails');
Route::post("/landscapper/update-prof-img", 'LandscaperController@updateProfImg');
Route::post("/landscapper/update-feature-img", 'LandscaperController@updateFeatureImg');
Route::post("/landscapper/update-drivers-lisence", 'LandscaperController@updateDriversLisence');
Route::get('/privacyPolicy', 'HomeController@privacyPolicy')->name('privacy-policy');
Route::get('/FAQ', 'HomeController@faq')->name('faq');
Route::get('/customer-FAQ', 'HomeController@get_customer_faq')->name('get_customer_faq');
Route::get('/provider-FAQ', 'HomeController@get_provider_faq')->name('get_provider_faq');
Route::post('/FAQ', 'HomeController@faq')->name('home_faq');
Route::get('/why-work-with-us-view', 'HomeController@why_work_with_us_view')->name('why-work-with-us-view');
Route::get('/lawn-mowing-tips-view', 'HomeController@lawn_mowing_tips_view')->name('lawn-mowing-tips-view');
Route::get('/terms-services-view', 'HomeController@both_terms_condition')->name('terms-services-view');
Route::post('/terms-services-view', 'HomeController@both_terms_condition')->name('terms-services-view');
Route::get('/about-us-view', 'HomeController@about_us_view')->name('about-us-view');
Route::get('/add-contact', 'HomeController@add_contact')->name('add-contact');
Route::get('/user/booking-history', 'ServiceController@serviceBooking')->name('user-booking-history');
Route::get('/user/favorite-history', 'ServiceController@favoriteHistory')->name('user-favorite-history');
Route::get('/user/my-profile', 'ServiceController@userProfile')->name('user-my-profile');
Route::get('/user/booking-history-payment/{order_no}/', 'ServiceController@bookingPaymentDetails')->name('user-booking-history-payment');
Route::get('/user/paypal-payment/{order_no}', 'ServiceController@paypalPayment')->name('user-paypal-payment');
Route::post('/user/pay-from-escrow', 'ServiceController@payFromEscrow')->name('user-pay-from-escrow');
Route::get('/paypalSuccess', 'ServiceController@paypalSuccess')->name('paypalSuccess');
Route::post("/user/card-payment", 'ServiceController@cardPayment');
Route::get('/user/user-rating/{landscaper_id}/{order_no}', 'ServiceController@userRating')->name('user-rating');
Route::post("/user/edit-user-rating", 'ServiceController@editUserRating');
Route::post("/user/edit-landscaper-rating", 'ServiceController@editLandscaperRating');
Route::post("/updateNotification", 'ServiceController@updateNotification');
Route::post("/updateNotificationLandscaper", 'ServiceController@updateNotificationLandscaper');
Route::get('/user/payment-info', 'PaymentController@paymentInfo')->name('user-payment-info');

Route::get("/Home/Search-List/{id}", "ServiceController@servicePage");
//Route::get("/Home/Search-List/favourite/{id}", "ServiceController@likeFavorite");
Route::post("/home/add-favourite", "ServiceController@addFavorite");
Route::any("/Home/search-list-location", "ServiceController@servicePageLocation")->name('homeSearchListLocation');
Route::get("/Home/Service-Details/{id}/{latitude?}/{longitude?}", "ServiceController@serviceDetails")->name('homeServiceDetails');

Route::post("Home/Service/Add-To-Cart", "ServiceController@addToCart");
Route::post('Home/DistanceCheck', 'ServiceController@DistanceCheck')->name('DistanceCheck');
Route::post("Home/Service/Check-Out", "ServiceController@checkOut");
Route::post("Home/Service/Check-Out-Final", "ServiceController@checkOutFinal");
Route::post("User/Update-Profile", "UserController@updateProfile");

Route::post('/user/edit-profile', 'HomeController@editUser')->name('user-edit-profile');
Route::post('/user/add-address', 'HomeController@addAddress')->name('user-add-address');
Route::post('/user/edit-address', 'HomeController@editAddress')->name('user-edit-address');
Route::post('/user/match-password', 'ServiceController@matchPassword')->name('user-match-password');
Route::post('/user/update-password', 'ServiceController@updatePassword')->name('user-update-password');

Route::post('/lanscaper/edit-profile', 'LandscaperController@editLandscaper')->name('lanscaper-edit-profile');
Route::post('/lanscaper/get-service-hours', 'LandscaperController@getSeviceHours')->name('get-service-hours');
Route::post('/lanscaper/edit-service-hours', 'LandscaperController@editServiceHours')->name('lanscaper-edit-service-hours');
Route::post('/landscaper/update-profile-pic/', 'LandscaperController@updateProfilePic')->name('lanscaper-update-profile-pic');
Route::post('/landscaper/match-password', 'LandscaperController@matchPassword')->name('landscaper-match-password');
Route::post('/landscaper/update-password', 'LandscaperController@updatePassword')->name('landscaper-update-password');

//Route::get('/user/message', 'MessageController@userMsg')->name('user-message');
Route::get('/user/message', 'MessageController@userMsgFirebase')->name('user-message-firebase');
Route::post('/user/show-chat', 'MessageController@userChat')->name('user-show-chat');
Route::post('/user/show-chat-firebase', 'MessageController@userChatFirebase')->name('user-show-chat-firebase');
Route::post('/user/add-message', 'MessageController@userAddMessage')->name('user-add-message');
Route::post('/user/show-message', 'MessageController@userShowMessage')->name('user-show-message');
Route::get('/user/activity-check', 'MessageController@users_activity_check')->name('users-activity-check');
Route::get('/landscaper/message', 'MessageController@landscaperMsg')->name('landscaper-message');
Route::get('/landscaper/message-firebase', 'MessageController@landscaperMsgFirebase')->name('landscaper-message-firebase');
Route::post('/landscaper/show-chat', 'MessageController@landscaperChat')->name('landscaper-show-chat');
Route::post('/landscaper/show-chat-firebase', 'MessageController@landscaperChatFirebase')->name('landscaper-show-chat-firebase');
Route::post('/landscaper/add-message', 'MessageController@landscaperAddMessage')->name('landscaper-add-message');
Route::get('/landscapers/activity-check', 'MessageController@landscapers_activity_check')->name('landscapers-activity-check');

Route::get('/admin', 'AdminController@Login')->name('admin-login');
Route::get('/Admin/Login', 'AdminController@Login')->name('Admin');
Route::post('/AdminLoginAccess', 'AdminController@LoginAccess')->name('AdminLoginAccess');
Route::get('/Admin/Logout', 'AdminController@Logout')->name('Adminlogout');
Route::get('/AdminHome', 'AdminController@AdminHome')->name('AdminHome');
Route::get('/ManageLandscapers', 'AdminController@ManageLandscapers')->name('ManageLandscapers');
Route::get('/Admin/EditLandscaper/{id}', 'AdminController@EditLandscaper')->name('EditLandscaper');
Route::post('/Admin/EditLandscaperSubmit', 'AdminController@EditLandscaperSubmit');
Route::get('/Admin/CreateLandscaper', 'AdminController@CreateLandscaper')->name('CreateLandscaper');
Route::post('/Admin/CreateLandscaperSubmit', 'AdminController@CreateLandscaperSubmit');
Route::get('/Admin/DeleteLandscaper/{id}', 'AdminController@DeleteLandscaper')->name('DeleteLandscaper');
Route::get('/ManageUsers', 'AdminController@ManageUsers')->name('ManageUsers');
Route::get('/Admin/EditUser/{id}', 'AdminController@EditUser')->name('EditUser');
Route::post('/Admin/EditUserSubmit', 'AdminController@EditUserSubmit');
Route::get('/Admin/CreateUser', 'AdminController@CreateUser')->name('CreateUser');
Route::post('/Admin/CreateUserSubmit', 'AdminController@CreateUserSubmit');
Route::get('/Admin/DeleteUser/{id}', 'AdminController@DeleteUser')->name('DeleteUser');
Route::get('/ManageServices', 'AdminController@ManageServices')->name('ManageServices');
Route::get('/ManageBookings', 'AdminController@ManageBookings')->name('ManageBookings');
Route::get('/ServicePrices', 'AdminController@ServicePrices')->name('ServicePrices');
Route::get('/ServiceRatings', 'AdminController@ServiceRatings')->name('ServiceRatings');
Route::get('/AddressBooks', 'AdminController@AddressBooks')->name('AddressBooks');
Route::post('/Admin/getAddress', 'AdminController@getAddress')->name('getAddress');
Route::post('/Admin/getBookingHistory', 'AdminController@getBookingHistory')->name('getBookingHistory');
Route::get('/UserMessages', 'AdminController@UserMessages')->name('UserMessages');
Route::post('/Admin/getMessageDetails', 'AdminController@getMessageDetails')->name('getMessageDetails');
Route::get('/Home/AddLandscaper', 'HomeController@AddLandscaper')->name('AddLandscaper');
Route::get('/ViewPayments', 'AdminController@ViewPayments')->name('ViewPayments');
Route::post('/Admin/getLandscaperRevenue', 'AdminController@getLandscaperRevenue')->name('getLandscaperRevenue');
Route::post('/Admin/getServiceRevenue', 'AdminController@getServiceRevenue')->name('getServiceRevenue');
Route::post('/Admin/getOverallRating', 'AdminController@getOverallRating')->name('getOverallRating');
Route::get('/Admin/EditServices/{id}', 'AdminController@EditServices')->name('EditServices');
Route::post('/Admin/EditServicesSubmit', 'AdminController@EditServicesSubmit');
Route::get('/Admin/CreateServicePrices', 'AdminController@CreateServicePrices')->name('CreateServicePrices');
Route::post('/Admin/CreateServicePricesSubmit', 'AdminController@CreateServicePricesSubmit');
Route::get('/Admin/EditServicePrices/{id}', 'AdminController@EditServicePrices')->name('EditServicePrices');
Route::post('/Admin/EditServicePricesSubmit', 'AdminController@EditServicePricesSubmit');
Route::get('/Admin/DeleteServicePrices/{id}', 'AdminController@DeleteServicePrices')->name('DeleteServicePrices');

Route::post('/user/edit-password', 'HomeController@editPassword')->name('user-edit-password');

Route::get('/home/testurl', 'HomeController@testFunction')->name('test-url');
Route::post('/payment/add-payment-info', 'PaymentController@addPaymentInfo')->name('add-payment-info');
Route::post('/payment/savePrimaryCard', 'PaymentController@savePrimaryCard')->name('save-primary-card');
Route::get('/payment/delete-payment-info/{id}/{order_no?}', 'PaymentController@deletePaymentInfo')->name('delete-payment-info');
Route::get('/PaymentPercentage', 'AdminController@PaymentPercentage')->name('PaymentPercentage');
Route::post('Admin/AddpaymentPercentage', 'AdminController@AddpaymentPercentage')->name('AddpaymentPercentage');
Route::get('/PrivacyPolicy', 'AdminController@PrivacyPolicy')->name('PrivacyPolicy');
Route::post('Admin/AddprivacyPolicy', 'AdminController@AddprivacyPolicy')->name('AddprivacyPolicy');
Route::get('/faq', 'AdminController@faq')->name('faq');
//Route::post('/Admin/Addfaq', 'AdminController@Addfaq')->name('Addfaq');
Route::get('/new-faq', 'AdminController@new_faq')->name('new-faq');
Route::post('/Admin/Add_new_faq', 'AdminController@Add_new_faq')->name('Add_new_faq');
Route::get('/Admin/Editfaq/{id}', 'AdminController@Edit_faq')->name('Editfaq');
Route::post('/Admin/update-faq/{id}', 'AdminController@update_faq')->name('update-faq');
Route::get('/Admin/Deletefaq/{id}', 'AdminController@delete_faq')->name('Deletefaq');
Route::get('/why-work-with-us', 'AdminController@why_work_with_us')->name('why-work-with-us');
Route::post('Admin/Add-why-work-with-us', 'AdminController@Add_why_work_with_us')->name('Add-why-work-with-us');
Route::get('/lawn-mowing-tips', 'AdminController@lawn_mowing_tips')->name('lawn-mowing-tips');
Route::post('Admin/Add-lawn-mowing-tips', 'AdminController@Add_lawn_mowing_tips')->name('Add-lawn-mowing-tips');
Route::get('/terms-services', 'AdminController@terms_services')->name('terms-services');
Route::post('Admin/Add-terms-services', 'AdminController@Add_terms_services')->name('Add-terms-services');
Route::get('/about-us', 'AdminController@about_us')->name('about-us');
Route::post('Admin/Add-about-us', 'AdminController@Add_about_us')->name('Add-about-us');
Route::get('/ManagePayple', 'AdminController@ManagePayple')->name('ManagePayple');
Route::post('Admin/AddPaypleName', 'AdminController@AddPaypleName')->name('AddPaypleName');
Route::post('/Home/getDateTimeByTimezone','HomeController@getDateTimeByTimezone')->name('getDateTimeByTimezone');
 

/**
 * Api Routes
 */

Route::post('api/authenticate', 'ApiController@authenticate')->name("apiAuthenticate");
Route::post('api/user-registration', 'ApiController@userRegistration')->name('user-registration');
Route::post('api/user-fb-login', 'ApiController@userLoginFB')->name('user-fb-login');
Route::post('api/user-google-login', 'ApiController@userLoginGoogle')->name('user-google-login');
Route::post('api/landscaper-fb-login', 'ApiController@landscaperLoginFB')->name('landscaper-fb-login');
Route::post('api/landscaper-google-login', 'ApiController@landscaperLoginGoogle')->name('landscaper-google-login');

Route::get('api/service-list', 'ApiController@serviceList')->name('service-list');
Route::get('api/country-list', 'ApiController@countryList')->name('country-list');

Route::post('api/landscaper-registration', 'ApiController@landscaperRegistration')->name('landscaper-registration');
Route::post("api/add-lanscaper-details", 'ApiController@addLanscaperDetails')->name('add-lanscaper-details');
Route::post("api/add-lanscaper", 'ApiController@addLanscaper')->name('add-lanscaper');
Route::post("api/add-provider-details", 'ApiController@addProviderDetails')->name('add-provider-details');

Route::post("api/add-lawn-mawning-service", 'ApiController@addLawnMawningService')->name('add-lawn-mawning-service');
Route::post("api/add-lawn-treatment-service", 'ApiController@addLawnTreatmentService')->name('add-lawn-treatment-service');
Route::post("api/add-sprinkler-winterizing-service", 'ApiController@addSprinklerWinterizingService')->name('add-sprinkler-winterizing-service');
Route::post("api/add-aeration-service", 'ApiController@addAerationService')->name('add-aeration-service');
Route::post("api/add-pool-cleaning-service", 'ApiController@addPoolCleaningService')->name('add-pool-cleaning-service');
Route::post("api/add-leaf-removal-service", 'ApiController@addLeafRemovalService')->name('add-leaf-removal-service');
Route::post("api/add-snow-removal-service", 'ApiController@addSnowRemovalService')->name('add-snow-removal-service');
Route::post("api/add-service-days", 'ApiController@addServiceDays')->name('add-service-days');
Route::post("api/add-recurring-services", 'ApiController@addRecurringServices')->name('add-recurring-services');
Route::post("api/add-new-recurring-services", 'ApiController@addRecurringServicesNew')->name('add-new-recurring-services');

Route::post("api/sample-api", 'ApiController@sampleApi')->name('sample-api');
Route::post("api/get-country-name-byid", 'ApiController@getCountrynameByid')->name('get-country-name-byid');

Route::post('api/provider-list-by-service', 'ApiController@providerListByService')->name('provider-list-by-service');
Route::post('api/provider-list-by-location', 'ApiController@providerListByLocation')->name('provider-list-by-location');
Route::post("api/contact-us-landscaper", 'ApiController@contact_us_landscaper')->name('contact-us-landscaper');
Route::post("api/contact-us-user", 'ApiController@contact_us_user')->name('contact-us-user');
Route::post("api/emailCheck", 'ApiController@emailCheck')->name('email-check');
Route::post("api/otpCheck", 'ApiController@otpCheck')->name('otp-check');
Route::post("api/new-password", 'ApiController@enterNewPassword')->name('new-password');

Route::group(['middleware' => 'jwt-auth', 'prefix' => 'api'], function () {   
    Route::get('userinfo', 'ApiController@userInfo')->name('userinfo');
    Route::post('userinfo-edit', 'ApiController@userinfoEdit')->name('userinfo-edit');
    Route::get('service-request', 'ApiController@serviceRequestInfo')->name('service-request');
    Route::get('service-pending', 'ApiController@servicePendingInfo')->name('service-pending');
    Route::post('accept-reject-service', 'ApiController@acceptRejectService')->name('accept-reject-service');
    Route::post('confirm-job', 'ApiController@confirmJob')->name('confirm-job');
    Route::post('set-primary-card', 'ApiController@setPrimaryCard')->name('set-primary-card');
    Route::post('landscaper-edit', 'ApiController@editLandscaper')->name('landscaper-edit');
    Route::post('change-password', 'ApiController@changePassword')->name('change-password');
    Route::get("view-service-hours-and-others", 'ApiController@viewServiceHoursAndDetails')->name('view-service-hours-and-others');
    Route::post("edit-service-hours-and-others", 'ApiController@editServiceHoursAndDetails')->name('edit-service-hours-and-others');
    Route::get("address-book-list", 'ApiController@viewAddressBookList')->name('address-book-list');
    Route::post("add-address-book", 'ApiController@addAddressBook')->name('add-address-book');
    Route::post("edit-address-book", 'ApiController@editAddressBook')->name('edit-address-book');
    Route::post("confirm-booking", 'ApiController@confirmBooking')->name('confirm-booking');
    Route::get("booking-history", 'ApiController@bookingHistory')->name('booking-history');
    Route::post("booking-history-details", 'ApiController@bookingHistoryDetails')->name('booking-history-details');
    Route::get("landscaper-booking-history", 'ApiController@bookingHistoryLandscaper')->name('landscaper-booking-history');
    Route::post("booking-history-details-landscaper", 'ApiController@bookingHistoryDetailsLandscaper')->name('booking-history-details');
    
    Route::post("add-card", 'ApiController@addCard')->name('add-card');
    Route::post("check-primary-card", 'ApiController@checkPrimaryCard')->name('check-primary-card');
    Route::get("view-card-list", 'ApiController@viewCardList')->name('view-card-list');
    Route::post("edit-card", 'ApiController@editCard')->name('edit-card');
    Route::post("delete-card", 'ApiController@deleteCard')->name('delete-card');
    
    Route::post("add-paypal-account", 'ApiController@addPaypalAccount')->name('add-paypal-account');
    Route::get("view-paypal-account", 'ApiController@viewPaypalAccount')->name('view-paypal-account');
    Route::post("edit-paypal-account", 'ApiController@editPaypalAccount')->name('edit-paypal-account');
    Route::post("delete-paypal-account", 'ApiController@deletePaypalAccount')->name('delete-paypal-account');
    
    Route::post("add-favorite", "ApiController@addFavorite")->name('add-favorite');
    Route::post("remove-favorite", "ApiController@removeFavorite")->name('remove-favorite');
    Route::get("view-favorite-list", 'ApiController@viewFavoriteLandscaperList')->name('view-favorite-list');
    Route::post('provider-list-by-service-location', 'ApiController@providerListByServiceLocation')->name('provider-list-by-service-location');
    
    Route::post("give-rate-review", 'ApiController@giveRateReview')->name('give-rate-review');
    Route::get("view-transaction-history", 'ApiController@viewTransactionHistory')->name('view-transaction-history');
    
    Route::post("pay-using-card", 'ApiController@paymentUsingCard')->name('pay-using-card');
    Route::post("pay-using-paypal", 'ApiController@paymentUsingPaypal')->name('pay-using-paypal');    
    Route::post("end-job-landscaper", 'ApiController@endJobLandscaper')->name('end-job-landscaper');    
    
    Route::get("notification-list-user", 'ApiController@get_notification_list_user')->name('notification-list-user'); 
    Route::get("notification-list-landscaper", 'ApiController@get_notification_list_landscaper')->name('notification-list-landscaper'); 
    Route::post('subscribe', 'ApiController@subscribe');

    Route::post('lanscaper-details', 'ApiController@landscaperDetails')->name('landscaper-details');
    Route::get("user-chat-list", 'ApiController@user_chat_list')->name('user-chat-list');   
    Route::get("landscaper-chat-list", 'ApiController@landscaper_chat_list')->name('landscaper-chat-list');
    Route::get("get-notification-status", 'ApiController@notificationStatus')->name('update-notification');
});
Route::post("api/upload-landscaper-images", 'ApiController@uplaodLandscaperImages')->name('upload-landscaper-images');

Route::post("api/landscaper-paypal-details", 'ApiController@landscaper_paypal_details')->name('landscaper-paypal-details'); 
Route::post('api/change-profile-picture', 'ApiController@changeProfilePicture')->name('change-profile-picture');
Route::post('api/change-landscaper-feature-picture', 'ApiController@changeLandscaperFeaturePicture')->name('change-landscaper-feature-picture');
Route::post('api/change-landscaper-driver-image', 'ApiController@changeLandscaperDriverImage')->name('change-landscaper-driver-image');
Route::post("api/view-added-services", 'ApiController@viewAddedServiceList')->name('view-added-services');
Route::post("api/delete-added-services", 'ApiController@deleteAddedServiceList')->name('delete-added-services');
Route::post("api/view-service", 'ApiController@viewService')->name('view-service');
Route::post("api/edit-service", 'ApiController@editService')->name('edit-service');
Route::post("api/user-final-booking", 'ApiController@finalBooking')->name('user-final-booking');
Route::post("api/upload-service-image", 'ApiController@uploadServiceImage')->name('upload-service-image');
Route::get("api/admin-paypal-details", 'ApiController@admin_paypal_details')->name('admin-paypal-details'); 
Route::get("api/faq", 'ApiController@faq')->name('api-faq'); 
Route::post("api/get_faq", 'ApiController@get_faq')->name('api-get-faq'); 

Route::get('api/notification-test', 'ApiController@notification_test');
?>
