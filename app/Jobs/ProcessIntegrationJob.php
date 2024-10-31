<?php

namespace App\Jobs;

use App\Services\Factory\ServiceFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessIntegrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected $integrationId, protected $appPointer, protected $webhookRequestId = null, protected $integrationTest = false)
    {

        $this->onConnection('webhook_queue');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log:info("Executing");
        $actionService = ServiceFactory::getService($this->appPointer);
        $actionService->processWebhook($this->webhookRequestId, $this->integrationId, $this->integrationTest);
    }



    public function failed(?Throwable $exception): void
    {
        Log::error($exception);
    }
}

