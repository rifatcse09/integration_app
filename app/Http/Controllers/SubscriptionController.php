<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PlanName;
use App\Models\Charge;
use App\Models\Plan;
use App\Models\Shop;
use App\Services\ChargeService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Osiset\ShopifyApp\Actions\ActivatePlan;
use Osiset\ShopifyApp\Actions\CancelCurrentPlan as CancelCurrentPlanAction;
use Osiset\ShopifyApp\Contracts\Commands\Charge as IChargeCommand;
use Osiset\ShopifyApp\Objects\Enums\ChargeStatus;
use Osiset\ShopifyApp\Objects\Values\ChargeReference;
use Osiset\ShopifyApp\Objects\Values\NullablePlanId;
use Osiset\ShopifyApp\Objects\Values\PlanId;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use Osiset\ShopifyApp\Objects\Values\ShopId;
use Osiset\ShopifyApp\Storage\Queries\Shop as ShopQuery;
use Osiset\ShopifyApp\Contracts\Queries\Plan as IPlanQuery;
use Osiset\ShopifyApp\Util;

class SubscriptionController extends Controller
{
    public function __construct(
        protected IPlanQuery    $planQuery,
        protected ChargeService $chargeService,
        // protected CouponService $couponService,
    )
    {
    }

    /**
     * @param Request $request
     * @param ShopQuery $shopQuery
     * @param int|null $planId
     * @return \Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function createCharge(
        Request $request,
        int     $planId
    ): JsonResponse
    {
        $request->query->set('plan_id', $planId);
        $shop = shop();
        $nullablePlan = NullablePlanId::fromNative($planId);

        // Get the plan
        $plan = $nullablePlan->isNull() ? $this->planQuery->getDefault() : $this->planQuery->getById($nullablePlan);

        $response = $shop->api()->graph(
            load_graphql_blade_schema('charge-create'),
            $this->makeCreateChargeGraphQLVariable($shop, $plan)
        );

        $confirmationUrl = $response['body']['data']['appSubscriptionCreate'];

        return api([
            'shop' => shop(),
            'app_subscription' => [
                'confirmation_url' => $confirmationUrl['confirmationUrl'],
                // 'gid' => $confirmationUrl['appSubscription']['id'],

            ]
        ])->success("Charge created successfully!");
    }

    public function process(
        int          $planId,
        Request      $request,
        ShopQuery    $shopQuery,
        ActivatePlan $activatePlan,
    )
    {
        $shop = $shopQuery->getByDomain(ShopDomain::fromNative($request->query('shop')));

        if (!$request->has('charge_id')) {
            return Redirect::route(Util::getShopifyConfig('route_names.home'), [
                'shop' => $shop->getDomain()->toNative(),
                'host' => base64_encode($shop->getDomain()->toNative()),
            ]);
        }

        // Activate the plan and save
        $result = $activatePlan(
            $shop->getId(),
            PlanId::fromNative($planId),
            ChargeReference::fromNative((int)$request->query('charge_id')),
            $shop->name
        );

        return Redirect::route(Util::getShopifyConfig('route_names.home'), [
            'shop' => $shop->getDomain()->toNative(),
            'host' => base64_encode($shop->getDomain()->toNative()),
        ]);
    }

    public function useFree(
        CancelCurrentPlanAction $cancelCurrentPlanAction,
        Request                 $request,
        IChargeCommand          $chargeCommand,
    )
    {
        $shop = shop();
        $plan = Plan::where('name', PlanName::FREE->value)->first();
        if (!$plan) {
            return api([
                'shop' => $shop,
                'plan' => $plan,
            ])->fails("Invalid plan");
        }

        $charge = Charge::where('shop_id', $shop->id)
            ->where('status', ChargeStatus::ACTIVE()->toNative())
            ->first();

        if ($charge) {
            $chargeRef = ChargeReference::fromNative((int)$request->query('charge_id'));
            $shopId = ShopId::fromNative($shop->id);

            // Cancel the shop's current plan
            $cancelCurrentPlanAction($shopId);
            // Cancel the existing charge if it exists (happens if someone refreshes during)
            $chargeCommand->delete($chargeRef, $shopId);
        }

        $shop->plan_id = $plan->id;
        $shop->save();

        return api([
            'shop' => $shop,
            'plan' => $plan,
        ])->success("Plan activated successfully!");
    }


    protected function makeCreateChargeGraphQLVariable(Shop $shop, Plan $plan): array
    {

        $returnUrlParams = ['planId' => $plan->id, 'shop' => $shop->name];

        $payload = [
            'name' => $plan->name,
            'returnUrl' => route('subscription.process', $returnUrlParams),
            'trialDays' => $plan->trial_days,
            'test' => $plan->isTest(),
            'lineItems' => [
                [
                    'plan' => [
                        'appRecurringPricingDetails' => [
                            'price' => [
                                'amount' => $plan->price,
                                'currencyCode' => 'USD',
                            ],
                            'interval' => $plan->interval,
                        ],
                    ],
                ],
            ],
        ];

        return $payload;
    }

    protected function getRedirectChargeQueryParams(string $status = 'success', Shop $shop = null): string
    {
        if ($shop) {
            $host = $shop->getDomain()->toNative();
        } else {
            $host = ShopDomain::fromNative(request()->query('shop'))->toNative();
        }
        return http_build_query([
            'charge_status' => $status,
            'host' => base64_encode($host),
            'expires' => Carbon::now()->addSeconds(20)->timestamp,
        ]);
    }
}
