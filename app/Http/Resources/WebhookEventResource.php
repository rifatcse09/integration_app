<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebhookEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       $response = [
            'uid' => $this->uid,
            'name' => $this->name,
            'topic' => $this->topic,
        ];

        if ($this->custom_webhook_id) {
            $response['payload'] = $this->payload;
        }

        return $response;
    }
}
