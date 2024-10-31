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

class MetaFieldHandlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Shop $shop, protected MetaFieldKey $metaFieldKey)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(MetaFieldService $metaFieldService): void
    {
        $metaFieldService->updateOrCreateMetaField($this->shop, $this->metaFieldKey);
    }
}
