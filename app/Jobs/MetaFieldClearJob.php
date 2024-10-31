<?php

namespace App\Jobs;

use App\Enums\MetaFieldKey;
use App\Models\Shop;
use App\Services\MetaFieldService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MetaFieldClearJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Shop $shop, protected string $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(MetaFieldService $metaFieldService): void
    {
        $keys = [
            MetaFieldKey::POPUP_SETTINGS,
            MetaFieldKey::SALES,
            MetaFieldKey::SOLD_COUNT,
            MetaFieldKey::VISITOR,
        ];

        $metaFieldService->clearMetaFieldValue($this->shop, $keys, $this->message);
    }
}
