<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use App\Models\Shop;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Osiset\ShopifyApp\Http\Middleware\VerifyShopify;
use Symfony\Component\HttpFoundation\Response;

class AuthVerifyShop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = 'shop'): Response
    {
        if (app()->environment() === 'local' && $request->header('X-Test-Mode', 'false') === 'true') {

            $token = $request->bearerToken();
            if (empty($token)) {
                throw new \Exception();
            }

            $shopName = base64_decode($token);
            $shop = Shop::where('name', $shopName)->where('password', '!=', '')->first();

            if ($shop == null) {
                throw new UnauthorizedException();
            }

            auth($guard)->setUser($shop);

            return $next($request);
        }

        /**
         * @var VerifyShopify $verifyShopifyMiddleware
         */
        $verifyShopifyMiddleware = app(VerifyShopify::class);
        return $verifyShopifyMiddleware->handle($request, $next);
    }
}
