<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\NowpayController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\SquareController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AirWallexController;
use App\Http\Controllers\AiDocumentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PolicyController;

use App\Http\Controllers\Admin\AppUserController;
use App\Http\Controllers\Admin\SettingController;

use App\Http\Controllers\Admin\AppAdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BlackListController;

use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\AirWallexControllerController;



Route::get('/', [PageController::class, 'index']);
Route::get('/customer-terms-of-business', [PageController::class, 'customerTermsOfBusiness']);

Route::get('/privacy-policy', [PageController::class, 'privacyPolicy']);
Route::get('/cookies', [PageController::class, 'cookies']);
Route::get('/website-terms-of-use', [PageController::class, 'websiteTermsOfUse']);



Route::get('/my-account', [PageController::class, 'myAccount'])->name('myAccount');

Route::get('/view-order/{policy_number}', [PageController::class, 'viewPolicyPage']);
Route::post('/view-order', [PageController::class, 'viewPolicy']);



Route::get('/contact', [TicketController::class, 'showContactForm']);
Route::post('/contact', [TicketController::class, 'newTicket']);
Route::get('/ticket/{token}', [TicketController::class, 'viewTicket']);
Route::post('/ticket/reply', [TicketController::class, 'replyToTicket']);




Route::get('/order/get-quote', [PageController::class, 'policyGetQuote']);
Route::post('/order/get-quote', [PageController::class, 'processQuote']);

Route::get('/checkout', [PageController::class, 'checkout']);


Route::post('/promo-code', [PageController::class, 'getPromoCode']);
Route::post('/payment-intent', [StripeController::class, 'paymentIntent']);
Route::post('/confirm-payment', [StripeController::class, 'confirmPayment']);
Route::post('/checkout-registration', [StripeController::class, 'checkoutRegistration']);

Route::post('/airwallex/payment-intent', [AirWallexController::class, 'paymentIntent']);
Route::post('/airwallex/confirm-payment', [AirWallexController::class, 'confirmPayment']);
Route::post('/airwallex/registration', [AirWallexController::class, 'checkoutRegistration']);



Route::post('/square-confirm-payment', [SquareController::class, 'confirmPayment']);
Route::post('/square-checkout-registration', [SquareController::class, 'checkoutRegistration']);

Route::post('/paypal-action', [PaypalController::class, 'paypalAction']);
Route::post('/paypal-checkout-registration', [PaypalController::class, 'checkoutRegistration']);



Route::post('/nowpay-invoice', [NowpayController::class, 'paymentInvoice']);
Route::post('/nowpay-checkout-registration', [NowpayController::class, 'checkoutRegistration']);
Route::get('/now-confirm-payment', [NowpayController::class, 'confirmed']);
Route::get('/now-cancelled-payment', [NowpayController::class, 'cancelled']);
Route::post('/now-ipn', [NowpayController::class, 'webHook']);
// Route::get('/now-ipn', [NowpayController::class, 'webHook']);



Route::post('/checkout-bank-payment', [CheckoutController::class, 'bankPayment']);
Route::get('/bank-confirm-payment/{policy_number}', [CheckoutController::class, 'confirmed']);


Route::post('/checkcardetails/{reg_no}', [PageController::class, 'checkCarDetails']);

Route::get('/get-available-hours', [PageController::class, 'getAvailableHours']);
Route::get('/get-available-minutes', [PageController::class, 'getAvailableMinutes']);

Route::get('/search-address', [PageController::class, 'searchAddress']);


// AI document route 
Route::get('/ai-document', [PageController::class, 'aiDocumentShow'])->name('aidocument.show');



Route::post('/generate-ai-document', [AiDocumentController::class, 'generateDocument']);
Route::post('/generate-ai-document/paddle-webhook', [AiDocumentController::class, 'paddleWebhook'])->name('paddle.webhook');
Route::get('/paddle/payment-success', [AiDocumentController::class, 'paddlePaymentSuccess'])->name('paddle.success');
Route::get('/pp/paddle/token', [AiDocumentController::class, 'getToken']);



// ==========  ROUTE ONLY AVAILABLE TO LOGIN USERS   ============
Route::middleware(['auth'])->group(function () {
    Route::get('/generate-pay-link', [AiDocumentController::class, 'processAIPayments']);
    
    Route::get('/my-account/orders', [UserController::class, 'orders']);
    
    Route::get('/my-account/edit-account', [UserController::class, 'editAccount']);
    Route::post('/my-account/edit-account', [UserController::class, 'updateAccount']);

    Route::get('/my-account/logout', [UserController::class, 'logout']);

    Route::get('/my-account/order/{id}', [UserController::class, 'getPolicy']);

   

});

Route::post('/pdf/{type}', [PageController::class, 'policyCertificate']);
Route::get('/pdf/{type}', function(){
    return "This page can't be refresh. <a href='/my-account'>My account</a>";
});


Route::get('/confirmed', [StripeController::class, 'confirmed']);
Route::get('/cancelled', [StripeController::class, 'cancelled']);
Route::match(['get', 'post'], '/stripe-webhook-suizhide', [StripeController::class, 'webhook']);




Route::get('/airwallex/confirmed', [AirWallexController::class, 'confirmed']);
Route::get('/airwallex/cancelled', [AirWallexController::class, 'cancelled']);
Route::match(['get', 'post'], '/airwallex/webhook-suizhide', [AirWallexController::class, 'webhook']);






// ==========  ROUTE ONLY AVAILABLE TO NON LOGIN USERS   ============
Route::middleware(['unauth'])->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

    Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordPage']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

});


Route::post('/auth/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/auth/resend-verification-code', [AuthController::class, 'resendVerificationcode']);




Route::prefix('admin')->group(function () {
    // Authentication
    Route::middleware(['unadmin'])->group(function () {
        Route::get('/login', [AdminAuthController::class, 'loginPage']);
        Route::post('/login', [AdminAuthController::class, 'login']);
        Route::post('/login-2fa', [AdminAuthController::class, 'login2fa']);
        
        Route::get('/forgot-password', [AdminAuthController::class, 'forgotPasswordPage']);
        Route::post('/forgot-password', [AdminAuthController::class, 'forgotPassword']);

        Route::get('/reset-password/{token}', [AdminAuthController::class, 'resetPasswordPage']);
        Route::post('/reset-password', [AdminAuthController::class, 'resetPassword']);

    });


    // Routes without the 'auth' middleware
    Route::middleware(['auth:admin'])->group(function () {

        //Route::view('/', 'admin.index');
        Route::get('/', [AdminController::class, 'index']);

        Route::get('/optimize', function () {
            Artisan::call('optimize:clear');
            Artisan::call('optimize');
            return "App optimized";
        });


        Route::get('/admins', [AppAdminController::class, 'index']);
        Route::get('/admins/data', [AppAdminController::class, 'data']);
        Route::post('/admins', [AppAdminController::class, 'addAdmin']);
        Route::patch('/admins', [AppAdminController::class, 'updateAdmin']);
        Route::delete('/admin/{admin_id}', [AppAdminController::class, 'deleteAdmin']);


        Route::get('/users', [AppUserController::class, 'index']);
        Route::get('/users/data', [AppUserController::class, 'data']);  
        Route::patch('/users', [AppUserController::class, 'updateUser']);
        Route::delete('/user/{user_id}', [AppUserController::class, 'deleteUser']);

        

        Route::get('/orders', [PolicyController::class, 'index']);
        Route::get('/orders/data', [PolicyController::class, 'data']);
        Route::delete('/orders/cancel/{id}', [PolicyController::class, 'cancelPolicy']);
        Route::delete('/orders/refund/{id}', [PolicyController::class, 'refundPolicy']);
        Route::delete('/orders/{id}', [PolicyController::class, 'deletePolicy']);

        Route::get('/order/edit/{id}', [PolicyController::class, 'editPolicy']);
        Route::get('/order/new', [PolicyController::class, 'newPolicy']);
        Route::post('/order/', [PolicyController::class, 'addPolicy']);
        Route::patch('/order/', [PolicyController::class, 'updatePolicy']);
        Route::get('/order/users', [PolicyController::class, 'getUsers']);



        Route::get('/unconfirmed-orders', [PolicyController::class, 'indexUn']);
        Route::get('/orders/data-un', [PolicyController::class, 'dataUn']);
        Route::post('/orders/confirm', [PolicyController::class, 'confirmPolicy']);
        


        Route::get('/coupons', [CouponController::class, 'index']);
        Route::get('/coupons/data', [CouponController::class, 'data']);  
        Route::post('/coupons', [CouponController::class, 'addCoupon']);
        Route::patch('/coupons', [CouponController::class, 'updateCoupon']);
        Route::delete('/coupons/{id}', [CouponController::class, 'deleteCoupon']);




        Route::get('/tickets', [AdminTicketController::class, 'index']);
        Route::get('/tickets/data', [AdminTicketController::class, 'data']); 
        Route::get('/ticket/{ticket_id}', [AdminTicketController::class, 'viewTicket']);
        Route::post('/ticket/state', [AdminTicketController::class, 'ticketState']);
        Route::post('/ticket/reply', [AdminTicketController::class, 'replyToTicket']);
        Route::delete('/ticket/{ticket_id}', [AdminTicketController::class, 'deleteTicket']);

        Route::post('/ticket/email', [AdminTicketController::class, 'emailUsers']);
        
        


        Route::get('/blacklists', [BlackListController::class, 'index']);
        Route::get('/blacklists/data', [BlackListController::class, 'data']);  
        Route::post('/blacklists', [BlackListController::class, 'addBlackList']);
        Route::patch('/blacklists', [BlackListController::class, 'updateBlackList']);
        Route::delete('/blacklists/{id}', [BlackListController::class, 'deleteBlackList']);



        Route::get('/page-editing', [SettingController::class, 'pageEditing']);
        Route::get('/page-template', [SettingController::class, 'pageTemplate']);
        Route::get('/quote-formula', [SettingController::class, 'quoteFormula']);

        Route::get('/settings', [SettingController::class, 'settings']);
        Route::get('/payment-settings', [SettingController::class, 'paymentSettings']);
        Route::post('/settings', [SettingController::class, 'updateSetting']);
        Route::post('/settings/openapi', [SettingController::class, 'updateOpenAPISetting'])->name('update.OpenAPI');
        Route::post('/evaluate-php-quote', [SettingController::class, 'evaluatePhpQuote']);


        
        
        



        Route::get('/update-password', [AdminController::class, 'showChangePassword']);
        Route::post('/update-password', [AdminController::class, 'updatePassword']);


        Route::get('/logout', [AdminController::class, 'logout']);

    });

});


Route::get('/unnecessary-cronjob', [AppAdminController::class, 'SendPolicyExpirationReminders']);
