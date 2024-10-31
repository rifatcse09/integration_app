<?php

namespace App\Jobs;

use App\Mail\UninstallMail;
use App\Models\Shop;
use Osiset\ShopifyApp\Actions\CancelCurrentPlan;
use Osiset\ShopifyApp\Contracts\Commands\Shop as IShopCommand;
use Osiset\ShopifyApp\Contracts\Objects\Values\ShopDomain;
use Osiset\ShopifyApp\Contracts\Queries\Shop as IShopQuery;
use Osiset\ShopifyApp\Storage\Scopes\Namespacing;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AppUninstalledJob extends \Osiset\ShopifyApp\Messaging\Jobs\AppUninstalledJob
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's domain
     *
     * @var ShopDomain|string
     */
    public string|ShopDomain $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     * @param string|ShopDomain $shopDomain The shop's domain.
     * @param object $data The webhook data (JSON decoded).
     * @return void
     */
    public function __construct(string|ShopDomain $shopDomain, object $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
        parent::__construct($shopDomain, $data);
    }

    /**
     * Execute the job.
     *
     * @param IShopCommand $shopCommand The commands for shops.
     * @param IShopQuery $shopQuery The queried for shops.
     * @param CancelCurrentPlan $cancelCurrentPlanAction The action for cancelling the current plan.
     * @return bool
     */
    public function handle(
        IShopCommand      $shopCommand,
        IShopQuery        $shopQuery,
        CancelCurrentPlan $cancelCurrentPlanAction,
    ): bool {
        parent::handle($shopCommand, $shopQuery, $cancelCurrentPlanAction);
        Log:info("uninstall");
        $shop = Shop::withoutGlobalScope(Namespacing::class)->withTrashed()->where('name', $this->shopDomain)->first();
        $shop->app_setup_completed = false;
        $shop->password = '';
        $shop->save();
        Mail::to($shop->email)->send(new UninstallMail($shop));
        return true;
    }
}
