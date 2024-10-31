<?php

namespace App\Http\Resources;

use App\Traits\Pagination;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class ActivityLogWithIntegrationResource extends ResourceCollection
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
            'activity_logs' => $this->collection->map(function ($activity) {
                return [
                    'activity_uid' => $activity->log_uid,
                    'integration_title' => $activity->integration_title,
                    'activity_title' => $activity->activity_title,
                    'trigger_logo_url' => Storage::disk('public')->url($activity->trigger_logo),
                    'action_logo_url' => Storage::disk('public')->url($activity->action_logo),
                    'created' => $activity->created,
                    'response' => json_decode($activity->log_payload),
                    'status' => $activity->status,
                ];
            }),
            'links' => $this->links(),
            'meta' => $this->meta()
        ];

    }
}
