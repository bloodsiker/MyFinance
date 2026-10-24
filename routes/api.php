<?php

use App\Http\Controllers\Api\ChildrenApiController;
use App\Http\Controllers\Api\PaymentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Children;
use App\Models\User;
use App\Http\Controllers\Api\AccountApiController;
use App\Http\Controllers\PaymentController;

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

Route::any('{id}/child', function ($id ,Request $request){
    return Children::where('moodle_name', $request->moodle_name)->where('moodle_id', $id)->first()->lesson;
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->post('/user', function (Request $request) {
    return $request->user();
});

// SBIT (
Route::post('/accounts/activate', [AccountApiController::class, 'activate'])->name('accounts-activate');
Route::post('/accounts/deactivate', [AccountApiController::class, 'deactivate'])->name('accounts-deactivate');

Route::post('/payment/user_invoicing', [PaymentController::class, 'userInvoicing'])->name('payment-invoicing');
Route::post('/accounts/parent_info', [AccountApiController::class, 'get_parent_info'])->name('parent-info');

Route::middleware('bass_token_valid')->group(function () {
    Route::get('/payments', [PaymentApiController::class, 'getPayments'])->name('payment.list');
    Route::get('/payment/{orderId}', [PaymentApiController::class, 'getPayment'])->name('payment.get');
    Route::get('/children/{id}', [ChildrenApiController::class, 'getChildrenById'])->name('children.get');
    Route::get('/childrens', [ChildrenApiController::class, 'getChildrens'])->name('children.list');
});


//Route::post('/payment/add', [PaymentApiController::class, 'createLiqpayInvoice'])->name('payment-add');
//Route::post('/payment/cancel', [PaymentApiController::class, 'cancelLiqpayInvoice'])->name('payment-cancel');
// )SBIT
