<?php

namespace App\Http\Resources;

use App\Traits\Pagination;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class IntegrationResource extends ResourceCollection
{
    use Pagination;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {

        return [
            'integrations' => $this->collection->map(function ($integration) {
                return [
                    'integration_uid' => $integration->integration_uid,
                    'integration_title' => $integration->integration_title,
                    'trigger_logo_url' => Storage::disk('public')->url($integration->trigger_logo),
                    'action_logo_url' => Storage::disk('public')->url($integration->action_logo),
                    'created' => $integration->created,
                    'total_activity' => $integration->total_activity,
                    'last_run_activity' => $integration->last_run_activity,
                    'status' => $integration->status,
                ];
            }),
            'links' => $this->links(),
            'meta' => $this->meta()
        ];

    }
}
