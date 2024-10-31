<?php

namespace App\Listeners;

use App\Enums\MetaFieldKey;
use App\Jobs\MetaFieldHandlerJob;
use App\Mail\OnBoardMail;
use App\Models\Shop;
use App\Services\PlanService;
use App\Services\ShopifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Osiset\ShopifyApp\Messaging\Events\AppInstalledEvent;

class AfterAppInstalledListener implements ShouldQueue
{
    use Queueable;

    protected Shop $shop;

    public function __construct(protected ShopifyService $shopifyService, protected PlanService $planService)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param AppInstalledEvent $event
     * @return void
     */
    public function handle(AppInstalledEvent $event): bool
    {
        Log::info("AppInstalledEvent");
        $this->shop = Shop::find($event->shopId->toNative());
        if (!$this->shop->app_setup_completed) {
            $plan = $this->planService->defaultPlan();
            $shopDetail = $this->shopifyService->getShopFromApi($this->shop);
            $shopDetail['app_setup_completed'] = true;
            if ($plan) $shopDetail['plan_id'] = $plan->id;
            $this->shop->update($shopDetail);
        }
        // $this->shopMetaFieldHandler();
        Mail::to($this->shop->email)->send(new OnBoardMail($this->shop));
        return true;
    }

    private function shopMetaFieldHandler()
    {
        foreach (MetaFieldKey::values() as $value) {
            MetaFieldHandlerJob::dispatch($this->shop, MetaFieldKey::tryFrom($value));
        }
    }
}
