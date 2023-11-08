<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;


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

Route::any('/monnify-webhook', function (Request $request) {
    $req = \Request::all();
    $reservedAccountCallback = \App\Models\MonnifyCallback::create([
        'raw' => json_encode($req),
    ]);

    if($req && $req['eventData'] && $req['eventData']['product'] && $req['eventData']['product']['type'] && $req['eventData']['product']['type']=='RESERVED_ACCOUNT'){
        if($req && $req['eventData'] && $req['eventData']['product'] && $req['eventData']['product']['reference']){
            $ours = \App\Models\ReservedAccount::where('account_reference',$req['eventData']['product']['reference'])->first();
            if ($ours) {
                $evt = app('App\Http\Controllers\Wallet\ViewController')->virtualFunding($request, $req['eventData']['paymentReference']);
                if($evt){
                    \App\Models\MonnifyCallback::whereId($reservedAccountCallback->id)->update([
                        'status'=>'processed'
                    ]);
                }else{
                    \App\Models\MonnifyCallback::whereId($reservedAccountCallback->id)->update([
                        'status'=>'error'
                    ]);
                }
            }else{
                \App\Models\MonnifyCallback::whereId($reservedAccountCallback->id)->update([
                    'status'=>'skipped'
                ]);
            }
        }else{
            \App\Models\MonnifyCallback::whereId($reservedAccountCallback->id)->update([
                'status'=>'skipped'
            ]);
        }
    }

    return response()->json([
        'statusCode'=>'00',
        'message'=>'Thank you bruh!'
    ]);
});


Route::namespace('App\Http\Controllers\PartnerPackages')->group(function () {
    Route::get('/packages', 'ListController@showPage')->name('home');
    Route::get('/packages', 'ListController@showPage')->name('partner.home');
    Route::get('/onboard/{package?}', 'OnboardController@showForm')->name('partner.register.form');

    Route::prefix('partner')->group(function () {
        Route::get('/onboard-payment/{transactionId}', 'OnboardController@showPaymentForm')->name('partner.package.payment');
        Route::post('/onboard/confirm-payment', 'OnboardController@confirmPayment')->name('partner.confirm-payment');
        Route::get('/onboard-offline/{transactionId}', 'OnboardController@skipPayment')->name('partner.skip.payment');

        Route::get('/complete-payment', 'OnboardController@completePaymentForm')->name('partner.payment.complete');
        Route::post('/complete-payment', 'OnboardController@completePayment')->name('partner.register-complete');
    });


    // AJAX ROUTE
    Route::get('/verify-generated-code', 'OnboardController@verifyCode')->name('partner.code.verify');
    Route::get('/coupon-verify', 'OnboardController@couponVerify')->name('partner.coupon.verify');
});

// Auth and Registration Routes
Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::namespace('App\Http\Controllers\PartnerPackages')->group(function () {
        Route::prefix('partner')->group(function () {
            Route::get('/onboard-packages', 'OnboardController@onboardPackage')->name('partner.onboard.package');
            Route::get('/onboard-preview/{package?}', 'OnboardController@onboardPreview')->name('partner.onboard.preview');
            Route::post('/onboard-complete', 'OnboardController@onboardComplete')->name('partner.onboard.complete');
            
            Route::get('/onboard-success', 'OnboardController@onboardSuccess')->name('partner.onboard.success');
            Route::get('/onboard-failed', 'OnboardController@onboardFailed')->name('partner.onboard.failed');
        });


        // AJAX ROUTE
        Route::get('/verify-generated-code', 'OnboardController@verifyCode')->name('partner.code.verify');
        Route::get('/coupon-verify', 'OnboardController@couponVerify')->name('partner.coupon.verify');
    });

    Route::namespace('App\Http\Controllers\Dashboard')->group(function () {
        Route::get('/', 'ViewController@showPage')->name('partner.dashboard');
        Route::get('/home', 'ViewController@showPage')->name('partner.home');
    });

    Route::namespace('App\Http\Controllers\Profile')->group(function () {
        Route::get('/profile', 'ViewController@showPage')->name('partner.profile');
        Route::post('/update-profile', 'EditController@update')->name('partner.profile.update');
        Route::post('/update-bank', 'EditController@updateBankDetail')->name('partner.bank.update');
        
        Route::get('/verify-bank', 'EditController@ajaxGetAccountName')->name('partner.verify.account.ajax');
        
        Route::get('/change-password', 'ViewController@changePassword')->name('partner.change.account.password');
        Route::post('/update-password', 'EditController@updatePassword')->name('partner.update.account.password');
    });

    Route::namespace('App\Http\Controllers\Networks')->group(function () {
        Route::get('/networks/{pid?}', 'ViewController@showPage')->name('partner.networks');
        Route::post('/generate-code', 'ActionController@generateCode')->name('partner.code.create');
        Route::get('/generated-codes', 'ViewController@generatedCodes')->name('partner.code.generated');
        // Route::get('/verify-generated-code', 'ViewController@verifyCode')->name('partner.code.verify');
        Route::get('/edit-generated-code/{id}', 'ViewController@editCode')->name('partner.code.edit');
        Route::post('/update-generated-code/{id}', 'ActionController@updateCode')->name('partner.code.update');
    });

    Route::namespace('App\Http\Controllers\Earnings')->group(function () {
        Route::get('/earnings', 'ViewController@showPage')->name('partner.earnings');
        Route::get('/earnings-data', 'ViewController@earningsData')->name('partner.earnings.data');
        Route::post('/earnings-withdraw', 'ViewController@earningsWithdraw')->name('partner.earnings.withdraw');
        Route::get('/earnings-withdraw-view/{ref}', 'ViewController@earningsWithdrawView')->name('partner.earnings.withdraw.view');
        Route::post('/earnings-withdrawal', 'ViewController@earningsWithdrawal')->name('partner.earnings.withdrawal');
        Route::get('/earnings-withdrawn/{transId}', 'ViewController@earningsWithdrawn')->name('partner.earnings.withdrawn');

        Route::any('/transaction-commission', 'WebhookController@transactionCommission');
    });

    Route::namespace('App\Http\Controllers\Wallet')->group(function () {
        Route::get('/wallet', 'ViewController@showPage')->name('partner.wallet');
        Route::post('/fund-wallet', 'ViewController@initiateFundWallet')->name('partner.wallet.fund');
        Route::get('/fund-payment/{ref}', 'ViewController@fundPayment')->name('partner.wallet.fund-payment');
        Route::post('/fund-complete', 'ViewController@completeFund')->name('partner.wallet.complete-funding');
        Route::get('/fund-check/{ref}', 'ViewController@ajaxCheckFund')->name('partner.wallet.ajax-fund-check');
        Route::get('/get-account/{id}', 'ViewController@getAccount')->name('partner.wallet.get-account');
        
        Route::get('/fetch-account/{transactionId}/{amount}/{description?}', 'ViewController@createInvoice')->name('partner.wallet.fetch-account');
    });

    Route::namespace('App\Http\Controllers\License')->group(function () {
        Route::get('/licenses/{id?}', 'ViewController@showPage')->name('partner.licenses');
        Route::post('/license-purchase', 'ViewController@licensePurchase')->name('partner.license.purchase');
        Route::post('/license-upgrade', 'ViewController@licenseUpgrade')->name('partner.license.upgrade');
    });

    Route::namespace('App\Http\Controllers\Business')->group(function () {
        Route::get('/businesses', 'ViewController@showPage')->name('partner.businesses');
        Route::get('/businesses/{type?}', 'ViewController@showPage')->name('partner.businesses.inactive');
        Route::get('/top-businesses', 'ViewController@topBusiness')->name('partner.businesses.top');
        Route::get('/renewal-businesses', 'ViewController@renewalBusiness')->name('partner.businesses.renewal');

        Route::get('/create-business', 'ViewController@createBusiness')->name('partner.business.create');
        Route::post('/create-business', 'ViewController@createBusinessAction')->name('partner.business.add');
        Route::get('/view-business/{id}', 'ViewController@viewBusiness')->name('partner.business.view');

        Route::get('/verify-business-username/{username?}', 'ViewController@verifyUsername')->name('partner.business.verify-username');
    });

    Route::namespace('App\Http\Controllers\PosTerminal')->group(function () {
        Route::get('/pos-terminals', 'ViewController@showPage')->name('partner.pos-terminal');
    });
});
