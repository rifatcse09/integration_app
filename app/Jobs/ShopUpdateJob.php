<?php

namespace App\Jobs;

use App\Models\Shop;
use App\Services\ShopService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;

class ShopUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var Shop
     */
    protected Shop $shop;

    /**
     * Shop's domain
     * @var ShopDomain|string
     */
    protected ShopDomain|string $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    protected object $data;

    /**
     * Create a new job instance.
     * @param string   $shopDomain The shop domain.
     * @param object $data The webhook data (JSON decoded).
     * @return void
     */
    public function __construct(string|ShopDomain $shopDomain, object $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }


    /**
     * Execute the job.
     */
    public function handle(ShopService $shopService): void
    {
        $this->shop = $shopService->retrieveShopByName($this->shopDomain);
        $this->updateShopData();
    }


    private function updateShopData(): void
    {
        $this->shop->update([
            'title' => $this->data->name,
            'name' => $this->data->myshopify_domain,
            'primary_domain' => $this->data->domain,
            'email' => $this->data->email,
        ]);
    }
}
