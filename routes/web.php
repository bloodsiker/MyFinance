<?php

use App\Helpers\MoodleHelpers;
use App\Http\Controllers\Admin\ReLoginController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SettingGeneralController;
use App\Http\Controllers\Admin\SettingMasterPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LearningFormatController;
use App\Http\Controllers\MoodleCourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\YearController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DarkModeController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BitrixController;
use App\Http\Controllers\MoodleController;
use App\Http\Middleware\IsParent;

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


Route::match(['POST', 'GET'], 'webhook', [TelegramController::class, 'webhook'])->name('telegram.webhook');
Route::get('setWebhook', [TelegramController::class, 'setWebhook'])->name('telegram.setWebhook');
Route::get('testBot', [TelegramController::class, 'testBot'])->name('telegram.testBot');

Route::middleware('web')->group(function () {

    Route::get('login', [AuthController::class, 'loginView'])->name('login-view');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('forgot-password', [AuthController::class, 'forgotPasswordSend'])->name('forgot-password.send');
    Route::get('reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::get('/exchange/tickerRate', [ExchangeController::class, 'tickerRate'])->name('exchange.rate');
    Route::get('/exchange', [ExchangeController::class, 'index'])->name('exchange');

});

Route::middleware('auth')->group(function () {

    Route::get('/categories', [CategoryController::class, 'index'])->name('category.list');
    Route::post('/categories/create', [CategoryController::class, 'create'])->name('category.create');

    Route::post('/year/create', [YearController::class, 'create'])->name('year.create');
    Route::get('/year/{id}', [YearController::class, 'index'])->name('month.list');
    Route::get('/month/{id}', [MonthController::class, 'index'])->name('transactions.list');
    Route::post('/month/category/get', [MonthController::class, 'getBudget'])->name('month.category.get');
    Route::post('/month/category/create', [MonthController::class, 'create'])->name('month.category.create');
    Route::post('/month/category/update', [MonthController::class, 'update'])->name('month.category.update');
    Route::post('/transaction/get', [TransactionController::class, 'getTransaction'])->name('transactions.get');
    Route::post('/transaction/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transaction/update', [TransactionController::class, 'update'])->name('transactions.update');
    Route::get('/transaction/{id}/delete', [TransactionController::class, 'delete'])->name('transactions.delete');
    Route::post('/transactions/getBudgetAnalytics', [TransactionController::class, 'getBudgetAnalytics'])->name('transactions.budget.analytics');
    Route::get('/transactions', [TransactionController::class, 'list'])->name('transactions.all');

    Route::get('/tasks/{id}/delete', [TaskController::class, 'delete'])->name('task.delete');
    Route::get('/tasks/{id}/completed', [TaskController::class, 'completed'])->name('task.completed');
    Route::post('/tasks/create', [TaskController::class, 'create'])->name('task.create');
    Route::get('/tasks/{date}', [TaskController::class, 'listByDate'])->name('task.list_by_date');
    Route::get('/tasks', [TaskController::class, 'list'])->name('task.list');

    Route::prefix('admin')->middleware('is_admin')->group(function () {
        Route::get('/parents', [App\Http\Controllers\Admin\UserController::class, 'listParents'])->name('admin.parents');
        Route::get('/parent/{id}/children', [App\Http\Controllers\Admin\UserController::class, 'parentHasChildren'])->name('admin.parent.children');

        Route::get('/sms_history/{id}', [App\Http\Controllers\Admin\UserController::class, 'smsHistory'])->name('admin.sms_history');
        Route::get('/payment_history/{id}', [App\Http\Controllers\Admin\UserController::class, 'paymentHistory'])->name('admin.payment_history');
        Route::post('/user/{id}/add_transaction', [App\Http\Controllers\Admin\UserController::class, 'addTransaction'])->name('admin.user.transaction_add');

        Route::get('/sms', [App\Http\Controllers\Admin\UserController::class, 'sms'])->name('admin.sms');
        Route::get('/send_sms_period/{id}', [App\Http\Controllers\Admin\UserController::class, 'sendSmsPeriod'])->name('admin.send_sms_period');
        Route::post('/send_sms', [App\Http\Controllers\Admin\UserController::class, 'sendSms'])->name('admin.send_sms');

        Route::post('/toggle_enable_user', [App\Http\Controllers\Admin\UserController::class, 'toggleEnableUser'])->name('admin.toggle_enable_user');

        Route::get('/settings', [SettingController::class, 'list'])->name('admin.settings.list');

        Route::get('/settings/master_password', [SettingMasterPasswordController::class, 'list'])->name('admin.settings.master_pass.list');
        Route::match(['POST', 'GET'],'/settings/master_password/create', [SettingMasterPasswordController::class, 'create'])->name('admin.settings.master_pass.create');
        Route::match(['POST', 'GET'],'/settings/master_password/{id}/edit', [SettingMasterPasswordController::class, 'edit'])->name('admin.settings.master_pass.edit');
        Route::get('/settings/master_password/{id}/delete', [SettingMasterPasswordController::class, 'delete'])->name('admin.settings.master_pass.delete');

        Route::get('/settings/general', [SettingGeneralController::class, 'list'])->name('admin.settings.general.list');
        Route::post('/settings/general/training_update', [SettingGeneralController::class, 'updateTrainingFormat'])->name('admin.settings.general.training_update');
    });

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');


    Route::get('dashboard-overview-1-page', [PageController::class, 'dashboardOverview1'])->name('dashboard-overview-1');
    Route::get('dashboard-overview-2-page', [PageController::class, 'dashboardOverview2'])->name('dashboard-overview-2');
    Route::get('dashboard-overview-3-page', [PageController::class, 'dashboardOverview3'])->name('dashboard-overview-3');
    Route::get('inbox-page', [PageController::class, 'inbox'])->name('inbox');
    Route::get('file-manager-page', [PageController::class, 'fileManager'])->name('file-manager');
    Route::get('point-of-sale-page', [PageController::class, 'pointOfSale'])->name('point-of-sale');
    Route::get('chat-page', [PageController::class, 'chat'])->name('chat');
    Route::get('post-page', [PageController::class, 'post'])->name('post');
    Route::get('calendar-page', [PageController::class, 'calendar'])->name('calendar');
    Route::get('crud-data-list-page', [PageController::class, 'crudDataList'])->name('crud-data-list');
    Route::get('crud-form-page', [PageController::class, 'crudForm'])->name('crud-form');
    Route::get('users-layout-1-page', [PageController::class, 'usersLayout1'])->name('users-layout-1');
    Route::get('users-layout-2-page', [PageController::class, 'usersLayout2'])->name('users-layout-2');
    Route::get('users-layout-3-page', [PageController::class, 'usersLayout3'])->name('users-layout-3');
    Route::get('profile-overview-1-page', [PageController::class, 'profileOverview1'])->name('profile-overview-1');
    Route::get('profile-overview-2-page', [PageController::class, 'profileOverview2'])->name('profile-overview-2');
    Route::get('profile-overview-3-page', [PageController::class, 'profileOverview3'])->name('profile-overview-3');
    Route::get('wizard-layout-1-page', [PageController::class, 'wizardLayout1'])->name('wizard-layout-1');
    Route::get('wizard-layout-2-page', [PageController::class, 'wizardLayout2'])->name('wizard-layout-2');
    Route::get('wizard-layout-3-page', [PageController::class, 'wizardLayout3'])->name('wizard-layout-3');
    Route::get('blog-layout-1-page', [PageController::class, 'blogLayout1'])->name('blog-layout-1');
    Route::get('blog-layout-2-page', [PageController::class, 'blogLayout2'])->name('blog-layout-2');
    Route::get('blog-layout-3-page', [PageController::class, 'blogLayout3'])->name('blog-layout-3');
    Route::get('pricing-layout-1-page', [PageController::class, 'pricingLayout1'])->name('pricing-layout-1');
    Route::get('pricing-layout-2-page', [PageController::class, 'pricingLayout2'])->name('pricing-layout-2');
    Route::get('invoice-layout-1-page', [PageController::class, 'invoiceLayout1'])->name('invoice-layout-1');
    Route::get('invoice-layout-2-page', [PageController::class, 'invoiceLayout2'])->name('invoice-layout-2');
    Route::get('faq-layout-1-page', [PageController::class, 'faqLayout1'])->name('faq-layout-1');
    Route::get('faq-layout-2-page', [PageController::class, 'faqLayout2'])->name('faq-layout-2');
    Route::get('faq-layout-3-page', [PageController::class, 'faqLayout3'])->name('faq-layout-3');
    Route::get('login-page', [PageController::class, 'login'])->name('login');
    Route::get('register-page', [PageController::class, 'register'])->name('register');
    Route::get('error-page-page', [PageController::class, 'errorPage'])->name('error-page');
    Route::get('update-profile-page', [PageController::class, 'updateProfile'])->name('update-profile');
    Route::get('change-password-page', [PageController::class, 'changePassword'])->name('change-password');
    Route::get('regular-table-page', [PageController::class, 'regularTable'])->name('regular-table');
    Route::get('tabulator-page', [PageController::class, 'tabulator'])->name('tabulator');
    Route::get('modal-page', [PageController::class, 'modal'])->name('modal');
    Route::get('slide-over-page', [PageController::class, 'slideOver'])->name('slide-over');
    Route::get('notification-page', [PageController::class, 'notification'])->name('notification');
    Route::get('accordion-page', [PageController::class, 'accordion'])->name('accordion');
    Route::get('button-page', [PageController::class, 'button'])->name('button');
    Route::get('alert-page', [PageController::class, 'alert'])->name('alert');
    Route::get('progress-bar-page', [PageController::class, 'progressBar'])->name('progress-bar');
    Route::get('tooltip-page', [PageController::class, 'tooltip'])->name('tooltip');
    Route::get('dropdown-page', [PageController::class, 'dropdown'])->name('dropdown');
    Route::get('typography-page', [PageController::class, 'typography'])->name('typography');
    Route::get('icon-page', [PageController::class, 'icon'])->name('icon');
    Route::get('loading-icon-page', [PageController::class, 'loadingIcon'])->name('loading-icon');
    Route::get('regular-form-page', [PageController::class, 'regularForm'])->name('regular-form');
    Route::get('datepicker-page', [PageController::class, 'datepicker'])->name('datepicker');
    Route::get('tail-select-page', [PageController::class, 'tailSelect'])->name('tail-select');
    Route::get('file-upload-page', [PageController::class, 'fileUpload'])->name('file-upload');
    Route::get('wysiwyg-editor-page', [PageController::class, 'wysiwygEditor'])->name('wysiwyg-editor');
    Route::get('validation-page', [PageController::class, 'validation'])->name('validation');
    Route::get('chart-page', [PageController::class, 'chart'])->name('chart');
    Route::get('slider-page', [PageController::class, 'slider'])->name('slider');
    Route::get('image-zoom-page', [PageController::class, 'imageZoom'])->name('image-zoom');
});
