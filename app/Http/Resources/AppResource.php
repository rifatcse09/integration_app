<?php

namespace App\Http\Resources;

use App\Enums\AuthType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'uid' => $this->uid,
            'logo_url' => $this->logo_url,
            'type' => $this->type,
        ];

    }
}
