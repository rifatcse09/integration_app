<?php

namespace App\Http\Resources;

use App\Traits\Pagination;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class ActivityLogResource extends ResourceCollection
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
                    'uid' => $activity->uid,
                    'title' => $activity->title,
                    'created' => $activity->created_at,
                    'response' => $activity->log_payload,
                    'status' => $activity->status,
                ];
            }),
            'links' => $this->links(),
            'meta' => $this->meta()
        ];

    }

}
