<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Http\Middleware\AuthVerifyShop;
use Illuminate\Support\Facades\Route;
//use Osiset\ShopifyApp\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([AuthVerifyShop::class])->group(function () {

    Route::post('/store',[IntegrationController::class, 'store']);
    Route::get('/integrations', [IntegrationController::class, 'index']);
    Route::get('/integrations/{uid}', [IntegrationController::class, 'detail']);
    Route::get('/initial-data/{token}', [IntegrationController::class, 'initializeServiceData'])->name('initial-data');
    Route::get('/webhook-payload', [IntegrationController::class, 'webhookPayload']);
    Route::get('/process/{app:uid}', [IntegrationController::class, 'process']);
    Route::get('/integration/{app:uid}/{method}',[IntegrationController::class, 'handleAction'])->name('integration.handle-action');
    Route::get('/token/{triggerUid}/{credentialUid}/{eventUid}', [IntegrationController::class, 'createIntegrationToken']);
    Route::get('/auth/methods/{app:uid}',[CredentialController::class, 'getSupportedAuthMethods'])->name('integration.auth-methods');
    Route::delete('/credentials/{credential:uid}', [CredentialController::class, 'delete']);
    Route::post('/auth/selection',[CredentialController::class, 'handleAuthSelection']);

    Route::get('/apps', [AppController::class, 'index']);
    Route::get('/apps/{uid}', [AppController::class, 'show']);

    Route::get('/activities', [ActivityLogController::class, 'index']);
    Route::get('/integration-activities/{integration_uid}', [ActivityLogController::class, 'activitiesByIntegrationUid']);

    Route::get('/topics/{app:uid}', [WebhookController::class, 'getEventsByAppUid']);
    Route::get('/webhook/custom/{app:uid}/{unique_code}', [WebhookController::class, 'getEventsByUniqueCode']);

    Route::get('/webhook-url', [WebhookController::class, 'createWebhookUrl']);

    Route::prefix('/plan')->name('plan.')->group(function () {
        Route::get('/list', [PlanController::class, 'index'])->name('list');
        Route::get('/current-plan', [PlanController::class, 'currentPlan'])->name('current');
    });

    Route::prefix('/subscription')->name('subscription.')->group(function () {
        Route::post('/{plan}/create', [SubscriptionController::class, 'createCharge'])->name('create');
        Route::post('/free', [SubscriptionController::class, 'useFree'])->name('free.process');
    });

});


Route::post('/webhook/{type}', [WebhookController::class, 'handle'])
     ->middleware('auth.webhook')
    ->name(config('shopify-app.route_names.webhook'));

Route::post('/webhook/custom/{id}', [WebhookController::class, 'handleCustom'])
    ->name('handle.webhook');

