<?php

use App\Http\Controllers\CredentialController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\SubscriptionController;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Osiset\ShopifyApp\Http\Controllers\AuthController as ShopifyAuthController;
use Illuminate\Support\Facades\Route;
use Osiset\ShopifyApp\Http\Middleware\VerifyShopify;

Route::middleware([VerifyShopify::class])->group(function () {

    Route::get('/home', function () {
        $shop = shop();
        list($subdomain) = explode('.', $shop->name);
        $host = base64_encode('admin.shopify.com/store/' . $subdomain);
        return redirect('/?shop=' . $shop->name . '&host=' . $host);
    })->name('home');


    Route::get(
        '/authenticate/token',
        ShopifyAuthController::class . '@token'
    )->name('authenticate.token');

    Route::get('subscription/{planId}/process', [SubscriptionController::class, 'process'])->name('subscription.process');
});

Route::get('auth/{serviceName}/callback',[CredentialController::class, 'callback'])->name('integration.callback');

Route::match(
    ['GET', 'POST'],
    '/authenticate',
    [ShopifyAuthController::class, 'authenticate']
)->name('authenticate');
